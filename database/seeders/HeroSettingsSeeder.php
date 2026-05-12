<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class HeroSettingsSeeder extends Seeder
{
    public function run()
    {
        $defaults = [
            ['key' => 'hero_top_text', 'value' => 'Koleksi Furniture Premium 2025'],
            ['key' => 'hero_title_top', 'value' => 'Ruang yang'],
            ['key' => 'hero_title_bottom', 'value' => 'Bercerita'],
            ['key' => 'hero_description', 'value' => 'Dari tangan pengrajin terbaik Indonesia — setiap detail dirancang dengan hati untuk menciptakan rumah yang benar-benar terasa seperti rumah.'],
            ['key' => 'hero_badge_1_text', 'value' => 'Garansi 5 Tahun'],
            ['key' => 'hero_badge_2_text', 'value' => 'Pengiriman Gratis'],
            ['key' => 'hero_badge_3_text', 'value' => 'Konsultasi Gratis'],
            ['key' => 'hero_image', 'value' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000&auto=format&fit=crop'],
        ];
        foreach ($defaults as $item) {
            Setting::firstOrCreate(['key' => $item['key']], $item);
        }
    }
}
