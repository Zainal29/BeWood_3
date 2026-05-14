<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use App\Models\Review;   // <-- tambahkan import ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('total');
        $totalProducts = Product::count();
        $totalCustomers = User::where('role', 'customer')->count();
        $recentOrders = Order::latest()->take(5)->get();

        $deliveredCount = Order::where('delivery_status', 'delivered')->count();
        $processingCount = Order::where('delivery_status', 'processing')->count();
        $outOfStockCount = Product::where('stock', '<=', 0)->count();

        // Ambil rating rata-rata dari semua review yang disetujui
        $averageRating = Review::where('is_approved', true)->avg('rating') ?? 4.8;

        // Data untuk grafik (7 hari terakhir)
    $chartLabels = [];
    $orderCounts = [];
    $revenueAmounts = [];

    for ($i = 6; $i >= 0; $i--) {
        $date = now()->subDays($i);
        $chartLabels[] = $date->translatedFormat('D'); // Sen, Sel, Rab, ...

        $orderCounts[] = Order::whereDate('created_at', $date)->count();
        $revenueAmounts[] = Order::whereDate('created_at', $date)->sum('total');
    }

    return view('admin.dashboard', compact(
        'totalOrders', 'totalRevenue', 'totalProducts', 'totalCustomers',
        'recentOrders', 'deliveredCount', 'processingCount', 'outOfStockCount',
        'averageRating', 'chartLabels', 'orderCounts', 'revenueAmounts'
    ));
    }
}
