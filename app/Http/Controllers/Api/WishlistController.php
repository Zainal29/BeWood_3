<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;

class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlists = Wishlist::where('user_id', $request->user()->id)->with('product')->get();
        return response()->json($wishlists);
    }

    public function toggle(Request $request)
    {
        $request->validate(['product_id' => 'required|exists:products,id']);
        $exists = Wishlist::where('user_id', $request->user()->id)
            ->where('product_id', $request->product_id)
            ->first();
        if ($exists) {
            $exists->delete();
            return response()->json(['action' => 'removed']);
        } else {
            Wishlist::create([
                'user_id' => $request->user()->id,
                'product_id' => $request->product_id
            ]);
            return response()->json(['action' => 'added']);
        }
    }
}
