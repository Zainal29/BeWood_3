<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
   public function index()
{
    $testimonials = Testimonial::orderBy('order')->paginate(10); // ✅ paginate, not get()
    $title = Setting::get('testimonials_title', 'Kata Mereka');
    $subtitle = Setting::get('testimonials_subtitle', 'Pengalaman nyata dari keluarga Indonesia...');
    return view('admin.testimonials.index', compact('testimonials', 'title', 'subtitle'));
}
    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);
        Setting::updateOrCreate(['key' => 'testimonials_title'], ['value' => $request->title]);
        Setting::updateOrCreate(['key' => 'testimonials_subtitle'], ['value' => $request->subtitle]);
        return redirect()->back()->with('success', 'Pengaturan section testimonial berhasil diperbarui.');
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        Testimonial::create([
            'customer_name' => $request->customer_name,
            'location' => $request->location,
            'product_name' => $request->product_name,
            'content' => $request->content,
            'rating' => $request->rating,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial berhasil ditambahkan.');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
            'product_name' => 'nullable|string|max:255',
            'content' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $testimonial->update([
            'customer_name' => $request->customer_name,
            'location' => $request->location,
            'product_name' => $request->product_name,
            'content' => $request->content,
            'rating' => $request->rating,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial berhasil diperbarui.');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Testimonial berhasil dihapus.');
    }
}
