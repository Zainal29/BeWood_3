<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $products = Product::where('name', 'like', "%{$query}%")
                    ->orWhereHas('category', function($q) use ($query) {
                        $q->where('name', 'like', "%{$query}%");
                    })
                    ->with('category')
                    ->limit(10)
                    ->get()
                    ->map(function($product) {
                        return [
                            'id' => $product->id,
                            'name' => $product->name,
                            'price' => $product->final_price,
                            'category' => $product->category->name ?? 'Umum',
                            'slug' => $product->slug,
                            'image' => Storage::url($product->main_image),
                        ];
                    });

        return response()->json($products);
    }
}
