<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SettingsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function edit()
    {
        $settings = SettingsService::getAll();
        return view('admin.settings.edit', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'hero_top_text' => 'nullable|string|max:255',
            'hero_title_top' => 'nullable|string|max:255',
            'hero_title_bottom' => 'nullable|string|max:255',
            'hero_description' => 'nullable|string',
            'hero_badge_1_text' => 'nullable|string|max:255',
            'hero_badge_2_text' => 'nullable|string|max:255',
            'hero_badge_3_text' => 'nullable|string|max:255',
            'hero_image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('hero_image');

        if ($request->hasFile('hero_image')) {
            $oldImage = SettingsService::get('hero_image');
            if ($oldImage && Storage::disk('public')->exists($oldImage)) {
                Storage::disk('public')->delete($oldImage);
            }
            $path = $request->file('hero_image')->store('hero', 'public');
            $data['hero_image'] = $path;
        }

        SettingsService::update($data);

        return redirect()->route('admin.settings.edit')
            ->with('success', 'Hero section berhasil diperbarui.');
    }
}
