<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class CartController extends Controller
{
    protected function getCart(Request $request)
    {
        $userId = $request->user()?->id;
        $sessionId = $request->cookie('cart_session_id');
        if (!$sessionId && !$userId) {
            $sessionId = uniqid('cart_', true);
            cookie()->queue('cart_session_id', $sessionId, 60*24*30);
        }
        return Cart::firstOrCreate([
            'user_id' => $userId,
            'session_id' => $userId ? null : $sessionId
        ]);
    }

    public function index(Request $request)
    {
        $cart = $this->getCart($request);
        $cart->load('items.product');
        return response()->json($cart);
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'selected_variants' => 'nullable|array'
        ]);
        $product = Product::findOrFail($request->product_id);
        $cart = $this->getCart($request);
        $item = $cart->items()->where('product_id', $product->id)->first();
        if ($item) {
            $item->increment('quantity', $request->quantity);
        } else {
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $product->id,
                'quantity' => $request->quantity,
                'selected_variants' => $request->selected_variants,
                'price_at_time' => $product->final_price
            ]);
        }
        return response()->json(['success' => true]);
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        $item->update(['quantity' => $request->quantity]);
        return response()->json(['success' => true]);
    }

    public function remove($itemId)
    {
        CartItem::destroy($itemId);
        return response()->json(['success' => true]);
    }

    public function clear(Request $request)
    {
        $cart = $this->getCart($request);
        $cart->items()->delete();
        return response()->json(['success' => true]);
    }
}
