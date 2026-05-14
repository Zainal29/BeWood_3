@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Page Title -->
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Dashboard</h1>
        <p class="text-slate-500 mt-1">Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }}. Berikut ringkasan toko Anda.</p>
    </div>

    <!-- Stat Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                ['label' => 'Total Produk', 'value' => number_format($totalProducts,0,',','.'), 'icon' => 'M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375', 'color' => 'emerald'],
                ['label' => 'Total Pesanan', 'value' => number_format($totalOrders,0,',','.'), 'icon' => 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z', 'color' => 'blue'],
                ['label' => 'Pendapatan', 'value' => 'Rp ' . number_format($totalRevenue,0,',','.'), 'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'amber'],
                ['label' => 'Customer', 'value' => number_format($totalCustomers,0,',','.'), 'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z', 'color' => 'purple'],
            ];
        @endphp
        @foreach($cards as $card)
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm hover:-translate-y-1 transition-all duration-300">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-slate-500">{{ $card['label'] }}</p>
                    <p class="text-3xl font-bold text-slate-800 mt-1">{{ $card['value'] }}</p>
                </div>
                <div class="w-10 h-10 rounded-full bg-{{ $card['color'] }}-50 flex items-center justify-center">
                    <svg class="w-5 h-5 text-{{ $card['color'] }}-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="{{ $card['icon'] }}"/>
                    </svg>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Recent Orders Table + Summary -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Orders Table (2/3 width) -->
        <div class="lg:col-span-2 bg-white rounded-3xl border border-slate-200 overflow-hidden shadow-sm">
            <div class="px-6 py-5 border-b border-slate-200 flex justify-between items-center">
                <h2 class="font-semibold text-slate-800">Pesanan Terbaru</h2>
                <a href="{{ route('admin.orders.index') }}" class="text-sm text-emerald-600 hover:text-emerald-700 font-medium">Lihat semua →</a>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-200">
                    <thead class="bg-slate-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">No. Order</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-slate-600 uppercase tracking-wider"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($recentOrders as $order)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">{{ $order->order_number }}</td>
                            <td class="px-6 py-4 text-sm text-slate-600">{{ $order->customer_name }}</td>
                            <td class="px-6 py-4 text-sm font-medium text-slate-800">Rp {{ number_format($order->total,0,',','.') }}</td>
                            <td class="px-6 py-4">
                                @php
                                    $statusClass = match($order->delivery_status) {
                                        'delivered' => 'bg-green-100 text-green-700',
                                        'processing' => 'bg-blue-100 text-blue-700',
                                        'shipped' => 'bg-slate-100 text-slate-700',
                                        default => 'bg-yellow-100 text-yellow-700',
                                    };
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClass }}">
                                    {{ ucfirst($order->delivery_status) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.orders.show', $order) }}" class="text-emerald-600 hover:text-emerald-800 text-sm font-medium">Detail</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center py-8 text-slate-400">Belum ada pesanan</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Quick Summary -->
        <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm space-y-4">
            <h2 class="font-semibold text-slate-800">Ringkasan Cepat</h2>
            <div class="space-y-3">
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Pesanan selesai</span>
                    <span class="font-semibold">{{ $deliveredCount ?? 0 }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Pesanan diproses</span>
                    <span class="font-semibold">{{ $processingCount ?? 0 }}</span>
                </div>
                <div class="flex justify-between py-2 border-b border-slate-100">
                    <span class="text-slate-500">Stok habis</span>
                    <span class="font-semibold text-red-600">{{ $outOfStockCount ?? 0 }} produk</span>
                </div>
                <div class="flex justify-between py-2">
                    <span class="text-slate-500">Rating pelanggan</span>
                    <span class="font-semibold text-amber-600">{{ number_format($averageRating ?? 4.8, 1) }} ★</span>
                </div>
            </div>
            <a href="{{ route('admin.products.index') }}" class="inline-flex items-center text-sm text-emerald-600 hover:text-emerald-700 font-medium">Kelola produk →</a>
        </div>
    </div>

    <!-- Simple Chart Placeholder (Optional) -->
    @if(isset($chartLabels) && count($chartLabels))
    <div class="bg-white rounded-3xl border border-slate-200 p-6 shadow-sm">
        <h2 class="font-semibold text-slate-800 mb-4">Tren Penjualan (7 Hari)</h2>
        <div class="h-64 flex items-center justify-center text-slate-400 bg-slate-50 rounded-2xl border border-slate-200">
            [Grafik penjualan akan ditampilkan di sini]
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script>
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil',
            text: "{{ session('success') }}",
            confirmButtonColor: '#059669',
            timer: 3000,
            showConfirmButton: false
        });
    @endif
</script>
@endpush
