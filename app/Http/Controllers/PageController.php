<?php
namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\Faq;
use Illuminate\Http\Request;

class PageController extends Controller
{
    // Halaman semua produk (Toko)
    public function products(Request $request)
    {
        $query = Product::with('category', 'variants')->where('is_active', true);

        // Search
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Filter kategori
        if ($request->filled('category')) {
            $query->whereHas('category', function($q) use ($request) {
                $q->where('slug', $request->category);
            });
        }

        // Filter harga
        if ($request->filled('min_price')) {
            $query->where(function($q) use ($request) {
                $q->where('price', '>=', $request->min_price)
                  ->orWhere('discount_price', '>=', $request->min_price);
            });
        }
        if ($request->filled('max_price')) {
            $query->where(function($q) use ($request) {
                $q->where('price', '<=', $request->max_price)
                  ->orWhere('discount_price', '<=', $request->max_price);
            });
        }

        // Urutkan
        $sort = $request->get('sort', 'latest');
        switch ($sort) {
            case 'price_asc':
                $query->orderByRaw('COALESCE(discount_price, price) ASC');
                break;
            case 'price_desc':
                $query->orderByRaw('COALESCE(discount_price, price) DESC');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name_desc':
                $query->orderBy('name', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12)->appends($request->query());
        $categories = Category::where('is_active', true)->get();
        $minPrice = Product::min('price');
        $maxPrice = Product::max('price');

        return view('pages.products', compact('products', 'categories', 'minPrice', 'maxPrice'));
    }

    // Halaman Tentang Kami
    public function about()
    {
        return view('pages.about');
    }

    // Halaman FAQ
    // public function faq()
    // {
    //     $faqs = Faq::where('is_active', true)->orderBy('order')->get();
    //     return view('pages.faq', compact('faqs'));
    // }

    // Halaman Kontak
    public function contact()
    {
        return view('pages.contact');
    }

    // Submit form kontak (opsional)
    public function submitContact(Request $request)
    {
        $request->validate([
            'name'    => 'required|string|max:255',
            'email'   => 'required|email|max:255',
            'message' => 'required|string',
        ]);

        // Simpan ke database atau kirim email
        // Contoh simpan ke tabel contacts jika sudah ada migration
        // Contact::create($request->all());

        // Atau kirim email ke admin
        // Mail::to('admin@bewood.com')->send(new ContactMail($request->all()));

        return back()->with('success', 'Pesan berhasil terkirim. Terima kasih!');
    }
}
