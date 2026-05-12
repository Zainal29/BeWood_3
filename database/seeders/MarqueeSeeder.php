<?php

namespace Database\Seeders;

use App\Models\MarqueeItem;
use Illuminate\Database\Seeder;

class MarqueeSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['text' => 'Kayu Jati Premium Bersertifikat', 'order' => 1],
            ['text' => 'Buatan Tangan Pengrajin Lokal', 'order' => 2],
            ['text' => 'Pengiriman Gratis Se-Indonesia', 'order' => 3],
            ['text' => 'Garansi 5 Tahun Penuh', 'order' => 4],
            ['text' => 'Konsultasi Desain Gratis', 'order' => 5],
        ];

        foreach ($items as $item) {
            MarqueeItem::updateOrCreate(['text' => $item['text']], $item);
        }
    }
}