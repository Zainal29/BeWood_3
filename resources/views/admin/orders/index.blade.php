@extends('admin.layouts.app')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
    <div>
        <h1 class="text-2xl font-serif text-sage-900 font-light">Pesanan</h1>
        <p class="text-sage-500 text-sm mt-1">Kelola semua pesanan pelanggan</p>
    </div>
    <a href="{{ route('admin.orders.create-manual') }}" class="btn-primary px-5 py-2.5 text-sm font-sans font-medium inline-flex items-center gap-2 shadow-sm hover:shadow transition-all">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>
        Tambah Pesanan (WhatsApp)
    </a>
</div>

    <!-- Filter Status (opsional) -->
    <div class="bg-white rounded-xl shadow-sm p-4 flex flex-wrap gap-3">
        <a href="{{ route('admin.orders.index') }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ !request('status') ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Semua</a>
        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'pending' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Pending</a>
        <a href="{{ route('admin.orders.index', ['status' => 'processing']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'processing' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Processing</a>
        <a href="{{ route('admin.orders.index', ['status' => 'shipped']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'shipped' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Shipped</a>
        <a href="{{ route('admin.orders.index', ['status' => 'delivered']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'delivered' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Delivered</a>
        <a href="{{ route('admin.orders.index', ['status' => 'cancelled']) }}" class="px-4 py-2 rounded-lg text-sm font-medium {{ request('status') == 'cancelled' ? 'bg-sage-700 text-white' : 'bg-sage-100 text-sage-700 hover:bg-sage-200' }}">Cancelled</a>
    </div>

    <!-- Tabel Pesanan -->
    <div class="bg-white rounded-xl shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-sage-200">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">No. Order</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status Bayar</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status Kirim</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100 bg-white">
                    @forelse ($orders as $order)
                    <tr class="hover:bg-sage-50/50 transition">
                        <td class="px-6 py-4 font-medium text-sage-800">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sage-600">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-sage-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $paymentBadge = match($order->payment_status) {
                                    'paid' => 'bg-green-100 text-green-700',
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'failed' => 'bg-red-100 text-red-700',
                                    default => 'bg-sage-100 text-sage-700'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $paymentBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ ucfirst($order->payment_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            @php
                                $deliveryBadge = match($order->delivery_status) {
                                    'delivered' => 'bg-green-100 text-green-700',
                                    'shipped' => 'bg-blue-100 text-blue-700',
                                    'processing' => 'bg-sage-100 text-sage-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                    default => 'bg-yellow-100 text-yellow-700'
                                };
                            @endphp
                            <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium {{ $deliveryBadge }}">
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span> {{ ucfirst($order->delivery_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sage-500 text-sm">{{ $order->created_at->format('d/m/Y') }}</td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="inline-flex items-center gap-1 text-sage-600 hover:text-sage-800 transition text-sm font-medium">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-sage-500">
                            <svg class="w-12 h-12 mx-auto text-sage-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                            </svg>
                            <p>Belum ada pesanan.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $orders->appends(request()->query())->links() }}
    </div>
</div>
@endsection