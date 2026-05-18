<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category')->paginate(10);
        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255|unique:products',
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|integer|min:0',
            'discount_price'=> 'nullable|integer|min:0',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'specifications'=> 'nullable|string',
            'main_image'    => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured'   => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_new'        => 'nullable|boolean',
        ]);

        $data = $request->except(['main_image', 'images']);
        $data['slug'] = Str::slug($request->name);
        $data['is_active'] = true;
        $data['price'] = (int) $request->price;  //

        // Upload main image (wajib)
        if (!$request->hasFile('main_image') || !$request->file('main_image')->isValid()) {
            return back()->withInput()->withErrors(['main_image' => 'Gagal mengupload gambar utama.']);
        }
        $path = $request->file('main_image')->store('products', 'public');
        $data['main_image'] = $path;

        $product = Product::create($data);

        // Upload additional images (optional)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        // Optional: redirect to edit page
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:255', Rule::unique('products')->ignore($product->id)],
            'category_id'   => 'required|exists:categories,id',
            'price'         => 'required|integer|min:0',
            'discount_price'=> 'nullable|integer|min:0',
            'stock'         => 'required|integer|min:0',
            'description'   => 'nullable|string',
            'specifications'=> 'nullable|string',
            'main_image'    => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured'   => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_new'        => 'nullable|boolean',
        ]);

        $data = $request->except(['main_image', 'images']);
        $data['slug'] = Str::slug($request->name);
        $data['price'] = (int) $request->price;  //

        // Hapus gambar lama jika user minta (remove_image=1)
        if ($request->has('remove_image') && $request->remove_image == 1) {
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = null;
        }

        // Upload gambar utama baru
        if ($request->hasFile('main_image')) {
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }
            $path = $request->file('main_image')->store('products', 'public');
            $data['main_image'] = $path;
        }

        $product->update($data);

        // Upload gambar tambahan baru (tidak menghapus yg lama)
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                if ($image->isValid()) {
                    $path = $image->store('products', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image'      => $path,
                    ]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        // Hapus gambar utama
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }
        // Hapus semua gambar tambahan
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    // Hapus gambar utama via AJAX
    public function destroyMainImage(Product $product)
    {
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
            $product->update(['main_image' => null]);
            return response()->json(['success' => true, 'message' => 'Gambar utama berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan.'], 404);
    }

    // Hapus gambar tambahan via AJAX
    public function destroyImage(ProductImage $image)
    {
        if ($image->image && Storage::disk('public')->exists($image->image)) {
            Storage::disk('public')->delete($image->image);
            $image->delete();
            return response()->json(['success' => true, 'message' => 'Gambar tambahan berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan.'], 404);
    }
}
