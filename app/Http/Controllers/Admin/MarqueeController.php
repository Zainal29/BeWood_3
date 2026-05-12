<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MarqueeItem;
use Illuminate\Http\Request;

class MarqueeController extends Controller
{
    public function index()
    {
        $items = MarqueeItem::orderBy('order')->get();
        return view('admin.marquee.index', compact('items'));
    }

    public function update(Request $request, MarqueeItem $item)
    {
        $request->validate([
            'text' => 'required|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        $item->update($request->only(['text', 'order', 'is_active']));
        return redirect()->back()->with('success', 'Item marquee berhasil diperbarui.');
    }

    public function store(Request $request)
    {
        $request->validate([
            'text' => 'required|string|max:255',
        ]);

        MarqueeItem::create($request->all());
        return redirect()->back()->with('success', 'Item marquee berhasil ditambahkan.');
    }

    public function destroy(MarqueeItem $item)
    {
        $item->delete();
        return redirect()->back()->with('success', 'Item marquee berhasil dihapus.');
    }
}