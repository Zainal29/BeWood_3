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
            'name' => 'required|string|max:255|unique:products,name',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'main_image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;
        $data['is_active'] = true;

        if ($request->hasFile('main_image')) {
            $path = $request->file('main_image')->store('products', 'public');
            $data['main_image'] = $path;
        }

        $product = Product::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function show(Product $product)
    {
        // Redirect ke halaman edit karena admin mungkin ingin melihat detail sekaligus edit
        return redirect()->route('admin.products.edit', $product);
        // Atau buat view show terpisah: return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products')->ignore($product->id),
            ],
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|integer|min:0',
            'discount_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string',
            'specifications' => 'nullable|string',
            'main_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_featured' => 'nullable|boolean',
            'is_bestseller' => 'nullable|boolean',
            'is_new' => 'nullable|boolean',
        ]);

        $data = $request->all();
        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->where('id', '!=', $product->id)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }
        $data['slug'] = $slug;

        if ($request->hasFile('main_image')) {
            if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
                Storage::disk('public')->delete($product->main_image);
            }
            $path = $request->file('main_image')->store('products', 'public');
            $data['main_image'] = $path;
        }

        $product->update($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('products', 'public');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $path,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
        }
        foreach ($product->images as $image) {
            if (Storage::disk('public')->exists($image->image)) {
                Storage::disk('public')->delete($image->image);
            }
            $image->delete();
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }

    public function destroyMainImage(Product $product)
    {
        if ($product->main_image && Storage::disk('public')->exists($product->main_image)) {
            Storage::disk('public')->delete($product->main_image);
            $product->update(['main_image' => null]);
            return response()->json(['success' => true, 'message' => 'Gambar utama berhasil dihapus.']);
        }
        return response()->json(['success' => false, 'message' => 'Gambar tidak ditemukan.'], 404);
    }

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
