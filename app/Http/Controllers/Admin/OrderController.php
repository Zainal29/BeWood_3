<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::latest()->paginate(10);
        $allProducts = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.index', compact('orders', 'allProducts'));
    }

    public function show(Order $order)
    {
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'payment_status' => 'required|in:pending,paid,failed',
            'delivery_status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);

        $order->update([
            'payment_status' => $request->payment_status,
            'delivery_status' => $request->delivery_status,
        ]);

        return back()->with('success', 'Status pesanan berhasil diperbarui.');
    }

    public function createManual()
    {
        $products = Product::where('is_active', true)->orderBy('name')->get();
        return view('admin.orders.create-manual', compact('products'));
    }

    public function storeManual(Request $request)
    {
        $validator = validator($request->all(), [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_email' => 'nullable|email|max:255',
            'shipping_address' => 'required|string',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'shipping_cost' => 'nullable|integer|min:0',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $validator->errors()
            ], 422);
        }

        DB::beginTransaction();
        try {
            $subtotal = 0;
            $orderItemsData = [];

            foreach ($request->items as $item) {
                $product = Product::findOrFail($item['product_id']);
                $price = $product->discount_price ?? $product->price;
                $quantity = $item['quantity'];

                if ($product->stock < $quantity) {
                    throw new \Exception("Stok produk {$product->name} tidak mencukupi (tersisa: {$product->stock})");
                }

                $subtotal += $price * $quantity;
                $orderItemsData[] = [
                    'product_id' => $product->id,
                    'product_name' => $product->name,
                    'quantity' => $quantity,
                    'price' => $price,
                    'product_image' => $product->main_image, // Tambahkan product_image
                ];
            }

            $shippingCost = $request->shipping_cost ?? 0;
            $total = $subtotal + $shippingCost;
            $orderNumber = 'ORD-' . strtoupper(Str::random(8)) . date('ymd');

            $order = Order::create([
                'order_number' => $orderNumber,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'note' => $request->note,
                'subtotal' => $subtotal,
                'shipping_cost' => $shippingCost,
                'total' => $total,
                'payment_status' => 'pending',
                'delivery_status' => 'pending',
                'payment_method' => 'manual',
                'order_date' => now(),
            ]);

            foreach ($orderItemsData as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'product_image' => $item['product_image'], // Tambahkan product_image
                ]);
                Product::where('id', $item['product_id'])->decrement('stock', $item['quantity']);
            }

            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil ditambahkan.',
                'redirect' => route('admin.orders.show', $order)
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
