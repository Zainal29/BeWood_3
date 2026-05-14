<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarqueeItem;
use Illuminate\Http\Request;

class MarqueeController extends Controller
{
    public function index()
    {
        $items = MarqueeItem::orderBy('order')->paginate(10);
        return view('admin.marquee.index', compact('items'));
    }

    public function store(Request $request)
    {
        $request->validate(['text' => 'required|string|max:255']);
        MarqueeItem::create($request->only('text', 'order', 'is_active'));
        return back()->with('success', 'Item marquee berhasil ditambahkan.');
    }

    public function update(Request $request, MarqueeItem $marquee)
    {
        $request->validate(['text' => 'required|string|max:255']);
        $marquee->update($request->only('text', 'order', 'is_active'));
        return back()->with('success', 'Item marquee diperbarui.');
    }

    public function destroy(MarqueeItem $marquee)
    {
        $marquee->delete();
        return back()->with('success', 'Item marquee dihapus.');
    }
}
