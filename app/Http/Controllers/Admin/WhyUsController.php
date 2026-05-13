<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyUsItem;
use App\Models\WhyUsStat;
use App\Models\WhyUsSetting;
use Illuminate\Http\Request;

class WhyUsController extends Controller
{
    public function index()
    {
        $settings = WhyUsSetting::pluck('value', 'key');
        $items = WhyUsItem::orderBy('order')->get();
        $stats = WhyUsStat::orderBy('order')->get();
        return view('admin.why-us.index', compact('settings', 'items', 'stats'));
    }

    public function updateSettings(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string',
            'subtitle' => 'nullable|string',
        ]);
        WhyUsSetting::updateOrCreate(['key' => 'title'], ['value' => $request->title]);
        WhyUsSetting::updateOrCreate(['key' => 'subtitle'], ['value' => $request->subtitle]);
        return redirect()->back()->with('success', 'Pengaturan berhasil diperbarui.');
    }

    public function updateItem(Request $request, WhyUsItem $item)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'required|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);
        $item->update($request->only(['title', 'description', 'order', 'is_active']));
        return redirect()->back()->with('success', 'Item berhasil diperbarui.');
    }

    // Store new item
public function storeItem(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'order' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
    ]);

    WhyUsItem::create($request->all());

    return redirect()->route('admin.why-us.index')->with('success', 'Item berhasil ditambahkan.');
}

// Store new stat
public function storeStat(Request $request)
{
    $request->validate([
        'label' => 'required|string|max:100',
        'value' => 'required|string|max:50',
        'order' => 'nullable|integer',
        'is_active' => 'nullable|boolean',
    ]);

    WhyUsStat::create($request->all());

    return redirect()->route('admin.why-us.index')->with('success', 'Statistik berhasil ditambahkan.');
}


    public function updateStat(Request $request, WhyUsStat $stat)
    {
        $request->validate([
            'label' => 'required|string',
            'value' => 'required|string',
            'order' => 'integer',
            'is_active' => 'boolean',
        ]);
        $stat->update($request->only(['label', 'value', 'order', 'is_active']));
        return redirect()->back()->with('success', 'Statistik berhasil diperbarui.');
    }
}
