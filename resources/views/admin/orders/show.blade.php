@extends('admin.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <!-- Header -->
    <div class="flex items-center gap-4">
        <a href="{{ route('admin.orders.index') }}" class="text-sage-500 hover:text-sage-700 transition">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-serif text-sage-900 font-light">Detail Pesanan</h1>
            <p class="text-sage-500 text-sm mt-0.5">#{{ $order->order_number }}</p>
        </div>
    </div>

    <!-- Info Pesanan -->
    <div class="bg-white rounded-xl shadow-sm p-6 md:p-8 space-y-6">
        <div class="grid md:grid-cols-2 gap-6">
            <div>
                <h3 class="font-serif text-lg text-sage-900 mb-3">Informasi Pelanggan</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-sage-500 w-28 inline-block">Nama:</span> <span class="text-sage-800">{{ $order->customer_name }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Email:</span> <span class="text-sage-800">{{ $order->customer_email }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Telepon:</span> <span class="text-sage-800">{{ $order->customer_phone }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Alamat:</span> <span class="text-sage-800">{{ $order->shipping_address }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Catatan:</span> <span class="text-sage-800">{{ $order->note ?? '-' }}</span></p>
                </div>
            </div>
            <div>
                <h3 class="font-serif text-lg text-sage-900 mb-3">Rincian Pembayaran</h3>
                <div class="space-y-2 text-sm">
                    <p><span class="text-sage-500 w-28 inline-block">Subtotal:</span> <span class="text-sage-800">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Ongkir:</span> <span class="text-sage-800">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span></p>
                    <p><span class="text-sage-500 w-28 inline-block">Total:</span> <span class="text-sage-800 font-semibold">Rp {{ number_format($order->total, 0, ',', '.') }}</span></p>
                </div>
            </div>
        </div>

        <!-- Produk yang dipesan -->
        <div>
            <h3 class="font-serif text-lg text-sage-900 mb-3">Produk yang Dipesan</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-sage-200">
                    <thead class="bg-sage-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-xs font-sans font-semibold text-sage-600">Produk</th>
                            <th class="px-4 py-2 text-left text-xs font-sans font-semibold text-sage-600">Qty</th>
                            <th class="px-4 py-2 text-left text-xs font-sans font-semibold text-sage-600">Harga</th>
                            <th class="px-4 py-2 text-left text-xs font-sans font-semibold text-sage-600">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-sage-100">
                        @foreach ($order->items as $item)
                        <tr>
                            <td class="px-4 py-2 text-sage-800">{{ $item->product_name }}</td>
                            <td class="px-4 py-2 text-sage-600">{{ $item->quantity }}</td>
                            <td class="px-4 py-2 text-sage-600">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td class="px-4 py-2 text-sage-800">Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Update Status -->
        <div class="pt-4 border-t border-sage-100">
            <h3 class="font-serif text-lg text-sage-900 mb-3">Update Status Pesanan</h3>
            <form method="POST" action="{{ route('admin.orders.status', $order) }}" class="flex flex-wrap items-end gap-4">
                @csrf @method('PUT')
                <div>
                    <label class="block text-sage-600 text-sm mb-1">Status Pembayaran</label>
                    <select name="payment_status" class="px-4 py-2 border border-sage-200 rounded-lg focus:ring-2 focus:ring-sage-300">
                        <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Paid</option>
                        <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Failed</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sage-600 text-sm mb-1">Status Pengiriman</label>
                    <select name="delivery_status" class="px-4 py-2 border border-sage-200 rounded-lg focus:ring-2 focus:ring-sage-300">
                        <option value="pending" {{ $order->delivery_status == 'pending' ? 'selected' : '' }}>Pending</option>
                        <option value="processing" {{ $order->delivery_status == 'processing' ? 'selected' : '' }}>Processing</option>
                        <option value="shipped" {{ $order->delivery_status == 'shipped' ? 'selected' : '' }}>Shipped</option>
                        <option value="delivered" {{ $order->delivery_status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                        <option value="cancelled" {{ $order->delivery_status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                    </select>
                </div>
                <button type="submit" class="btn-primary px-5 py-2 text-sm font-medium rounded-lg">Update Status</button>
            </form>
        </div>
    </div>
</div>
@endsection