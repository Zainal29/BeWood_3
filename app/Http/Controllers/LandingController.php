<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\Testimonial;
use App\Models\InstagramPost;
use App\Models\Faq;
use App\Services\SettingsService;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Featured categories (3 kategori dengan produk terbanyak)
        $featuredCategories = Category::withCount('products')
            ->where('is_active', true)
            ->orderBy('products_count', 'desc')
            ->limit(3)
            ->get();

        // Featured products (unggulan, terlaris, atau baru) - tanpa relasi 'variants' untuk menghindari error
        $products = Product::with('category') // Hanya relasi category, hapus variants jika belum ada
            ->where('is_active', true)
            ->where(function($q) {
                $q->where('is_featured', true)
                  ->orWhere('is_bestseller', true)
                  ->orWhere('is_new', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(8);

        // Testimonials
        $testimonials = Testimonial::where('is_active', true)
            ->latest()
            ->take(3)
            ->get();

        // Instagram posts
        $instagramPosts = InstagramPost::where('is_active', true)
            ->latest()
            ->limit(6)
            ->get();

        // FAQ (pertanyaan umum)
        $faqs = Faq::where('is_active', true)
            ->orderBy('order')
            ->get();

        // Hero section settings (dinamis untuk edit admin)
        $heroSettings = SettingsService::getAll();

        return view('landing.index', compact(
            'featuredCategories',
            'products',
            'testimonials',
            'instagramPosts',
            'faqs',
            'heroSettings'
        ));
    }
}
