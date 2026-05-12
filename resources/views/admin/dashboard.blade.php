@extends('admin.layouts.app')

@section('content')
<div class="space-y-8">
    <!-- Header -->
    <div class="flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-serif text-sage-900 font-light">Dashboard</h1>
            <p class="text-sage-500 text-sm mt-1">Selamat datang kembali, {{ Auth::user()->name ?? 'Admin' }}</p>
        </div>
        <div class="text-sage-400 text-sm">{{ now()->translatedFormat('l, d F Y') }}</div>
    </div>

    <!-- Statistik Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        @php
            $cards = [
                [
                    'title' => 'Total Pesanan',
                    'value' => number_format($totalOrders, 0, ',', '.'),
                    'icon' => 'M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z',
                    'color' => 'sage-400'
                ],
                [
                    'title' => 'Total Pendapatan',
                    'value' => 'Rp ' . number_format($totalRevenue, 0, ',', '.'),
                    'icon' => 'M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0z',
                    'color' => 'sage-500'
                ],
                [
                    'title' => 'Total Produk',
                    'value' => number_format($totalProducts, 0, ',', '.'),
                    'icon' => 'M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z',
                    'color' => 'sage-600'
                ],
                [
                    'title' => 'Total Customer',
                    'value' => number_format($totalCustomers, 0, ',', '.'),
                    'icon' => 'M18 18.72a9.094 9.094 0 003.741-.479 3 3 0 00-4.682-2.72m.94 3.198l.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0112 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 016 18.719m12 0a5.971 5.971 0 00-.941-3.197m0 0A5.995 5.995 0 0012 12.75a5.995 5.995 0 00-5.058 2.772m0 0a3 3 0 00-4.681 2.72 8.986 8.986 0 003.74.477m.94-3.197a5.971 5.971 0 00-.94 3.197M15 6.75a3 3 0 11-6 0 3 3 0 016 0z',
                    'color' => 'sage-700'
                ]
            ];
        @endphp
        @foreach ($cards as $card)
        <div class="bg-white rounded-xl shadow-premium p-5 flex items-center justify-between border-l-4 border-{{ $card['color'] }}">
            <div>
                <p class="text-sage-500 text-xs uppercase tracking-wider font-semibold">{{ $card['title'] }}</p>
                <p class="text-2xl font-serif font-light mt-1">{{ $card['value'] }}</p>
            </div>
            <svg class="w-10 h-10 text-{{ $card['color'] }}/60" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $card['icon'] }}" />
            </svg>
        </div>
        @endforeach
    </div>

    <!-- Grafik & Ringkasan -->
    <div class="grid lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 bg-white rounded-xl shadow-premium p-5">
            <div class="flex justify-between items-center mb-4">
                <h3 class="font-serif text-xl font-light text-sage-900">Tren Penjualan (7 Hari)</h3>
                <div class="flex gap-3 text-xs text-sage-400">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-sage-400"></span> Pesanan</span>
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-gold"></span> Pendapatan</span>
                </div>
            </div>
            <canvas id="salesChart" height="200"></canvas>
        </div>
        <div class="bg-white rounded-xl shadow-premium p-5">
            <h3 class="font-serif text-xl font-light text-sage-900 mb-4">Ringkasan Cepat</h3>
            <div class="space-y-3 text-sm">
                <div class="flex justify-between"><span class="text-sage-500">Pesanan selesai</span><span class="font-semibold">{{ $deliveredCount ?? 0 }}</span></div>
                <div class="flex justify-between"><span class="text-sage-500">Pesanan diproses</span><span class="font-semibold">{{ $processingCount ?? 0 }}</span></div>
                <div class="flex justify-between"><span class="text-sage-500">Stok produk habis</span><span class="font-semibold text-red-600">{{ $outOfStockCount ?? 0 }}</span></div>
                <div class="flex justify-between"><span class="text-sage-500">Rating rata-rata</span><span class="font-semibold">{{ number_format($averageRating ?? 4.8, 1) }} / 5</span></div>
            </div>
            <div class="mt-6 pt-4 border-t border-sage-100">
                <a href="{{ route('admin.orders.index') }}" class="text-xs text-sage-600 hover:text-sage-800 flex items-center gap-1">Lihat semua pesanan →</a>
            </div>
        </div>
    </div>

    <!-- Tabel Pesanan Terbaru -->
    <div class="bg-white rounded-xl shadow-premium overflow-hidden">
        <div class="px-6 py-4 border-b border-sage-100 flex justify-between items-center">
            <h3 class="font-serif text-xl font-light text-sage-900">Pesanan Terbaru</h3>
            <a href="{{ route('admin.orders.index') }}" class="text-xs text-sage-500 hover:text-sage-700">Lihat semua</a>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full">
                <thead class="bg-sage-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">No. Order</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Total</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-sans font-semibold text-sage-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-sage-100">
                    @forelse ($recentOrders as $order)
                    <tr class="hover:bg-sage-50 transition">
                        <td class="px-6 py-4 text-sm font-medium text-sage-800">{{ $order->order_number }}</td>
                        <td class="px-6 py-4 text-sm text-sage-600">{{ $order->customer_name }}</td>
                        <td class="px-6 py-4 text-sm text-sage-800">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 text-xs rounded-full font-medium 
                                @if($order->delivery_status == 'delivered') bg-green-100 text-green-700
                                @elseif($order->delivery_status == 'processing') bg-blue-100 text-blue-700
                                @elseif($order->delivery_status == 'shipped') bg-sage-100 text-sage-700
                                @else bg-amber-100 text-amber-700 @endif">
                                {{ ucfirst($order->delivery_status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.orders.show', $order) }}" class="text-sage-600 hover:text-sage-800 text-sm">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-8 text-sage-500">Belum ada pesanan</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('salesChart')?.getContext('2d');
        if (ctx) {
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($chartLabels ?? []),
                    datasets: [
                        {
                            label: 'Jumlah Pesanan',
                            data: @json($orderCounts ?? []),
                            borderColor: '#7da47d',
                            backgroundColor: 'rgba(125, 164, 125, 0.05)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 3,
                            pointBackgroundColor: '#5f7e5f'
                        },
                        {
                            label: 'Pendapatan (Rp)',
                            data: @json($revenueAmounts ?? []),
                            borderColor: '#c9a03d',
                            backgroundColor: 'rgba(201, 160, 61, 0.05)',
                            tension: 0.3,
                            fill: true,
                            pointRadius: 3,
                            pointBackgroundColor: '#a07e2e'
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: true,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: { boxWidth: 12, font: { size: 11 } }
                        }
                    }
                }
            });
        }
    });
</script>
@endpush
@endsection