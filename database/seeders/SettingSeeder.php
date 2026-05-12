<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Hero Section
            [
                'key' => 'hero_top_text',
                'value' => 'Koleksi Furniture Premium 2025',
                'type' => 'text',
            ],
            [
                'key' => 'hero_title_top',
                'value' => 'Ruang yang',
                'type' => 'text',
            ],
            [
                'key' => 'hero_title_bottom',
                'value' => 'Bercerita',
                'type' => 'text',
            ],
            [
                'key' => 'hero_description',
                'value' => 'Dari tangan pengrajin terbaik Indonesia — setiap detail dirancang dengan hati untuk menciptakan rumah yang benar-benar terasa seperti rumah.',
                'type' => 'textarea',
            ],
            [
                'key' => 'hero_image',
                'value' => 'https://images.unsplash.com/photo-1618221195710-dd6b41faaea6?q=80&w=2000&auto=format&fit=crop',
                'type' => 'image',
            ],
            // Trust Badges
            [
                'key' => 'hero_badge_1_text',
                'value' => 'Garansi 5 Tahun',
                'type' => 'text',
            ],
            [
                'key' => 'hero_badge_2_text',
                'value' => 'Pengiriman Gratis',
                'type' => 'text',
            ],
            [
                'key' => 'hero_badge_3_text',
                'value' => 'Konsultasi Gratis',
                'type' => 'text',
            ],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }
    }
}