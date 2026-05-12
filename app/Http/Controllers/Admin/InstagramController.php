<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\InstagramPost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class InstagramController extends Controller
{
    public function index()
    {
        $posts = InstagramPost::latest()->paginate(10);
        return view('admin.instagram.index', compact('posts'));
    }

    public function create()
    {
        return view('admin.instagram.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'instagram_url' => 'nullable|url|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('instagram', 'public');
            $data['image'] = $path;
        }

        InstagramPost::create($data);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Postingan Instagram berhasil ditambahkan.');
    }

    public function edit(InstagramPost $instagram)
    {
        return view('admin.instagram.edit', compact('instagram'));
    }

    public function update(Request $request, InstagramPost $instagram)
    {
        $request->validate([
            'instagram_url' => 'nullable|url|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_active' => 'boolean',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            // hapus gambar lama
            if ($instagram->image && Storage::disk('public')->exists($instagram->image)) {
                Storage::disk('public')->delete($instagram->image);
            }
            $path = $request->file('image')->store('instagram', 'public');
            $data['image'] = $path;
        }

        $instagram->update($data);

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Postingan Instagram berhasil diperbarui.');
    }

    public function destroy(InstagramPost $instagram)
    {
        if ($instagram->image && Storage::disk('public')->exists($instagram->image)) {
            Storage::disk('public')->delete($instagram->image);
        }
        $instagram->delete();

        return redirect()->route('admin.instagram.index')
            ->with('success', 'Postingan Instagram berhasil dihapus.');
    }
}
