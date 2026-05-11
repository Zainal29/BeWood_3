<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category', 'images', 'variants')->where('is_active', true);
        if ($request->category) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }
        $products = $query->latest()->paginate(12);
        return response()->json($products);
    }


public function show($slug)
{
    $product = Product::where('slug', $slug)
        ->with('category', 'images', 'variants')
        ->firstOrFail();

    return response()->json([
        'id' => $product->id,
        'name' => $product->name,
        'category' => $product->category->name ?? 'Umum',
        'price' => $product->discount_price ?? $product->price,
        'original_price' => $product->price,
        'rating' => (float) $product->rating,
        'reviews' => $product->reviews_count,
        'sold' => $product->sold,
        'stock' => $product->stock,
        'desc' => $product->description,
        'mainImg' => Storage::url($product->main_image),
        'thumbs' => $product->images->map(fn($img) => Storage::url($img->image))->values(),
        'specs' => $product->specifications ?? [],
        'variants' => $product->variants->where('type', 'warna')->pluck('value'),
        'finishes' => $product->variants->where('type', 'finishing')->pluck('value'),
    ]);
}

    public function featured()
    {
        $products = Product::where('is_featured', true)->limit(8)->get();
        return response()->json($products);
    }

    public function bestseller()
    {
        $products = Product::where('is_bestseller', true)->limit(8)->get();
        return response()->json($products);
    }
}
