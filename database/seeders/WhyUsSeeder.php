<?php

namespace Database\Seeders;

use App\Models\WhyUsItem;
use App\Models\WhyUsStat;
use App\Models\WhyUsSetting;
use Illuminate\Database\Seeder;

class WhyUsSeeder extends Seeder
{
    public function run(): void
    {
        // Setting judul & subjudul
        WhyUsSetting::updateOrCreate(
            ['key' => 'title'],
            ['value' => 'Mengapa BeWood?']
        );
        WhyUsSetting::updateOrCreate(
            ['key' => 'subtitle'],
            ['value' => 'Kami percaya furniture bukan sekadar benda, tapi warisan yang menemani cerita hidup Anda.']
        );

        // Items
        $items = [
            ['title' => 'Kayu Pilihan Premium', 'description' => 'Kami hanya menggunakan kayu jati, walnut, dan oak bersertifikat legal — dipilih satu per satu oleh master pengrajin untuk memastikan tekstur terbaik.', 'order' => 1],
            ['title' => 'Pengiriman Aman & Terproteksi', 'description' => 'Pengemasan berlapis khusus furniture dengan busa anti-guncang dan asuransi penuh. Dikirim ke seluruh Indonesia — gratis untuk order di atas Rp 5.000.000.', 'order' => 2],
            ['title' => 'Garansi 5 Tahun Penuh', 'description' => 'Kami percaya penuh pada setiap karya yang kami buat. Jika ada cacat struktural dalam 5 tahun, kami perbaiki atau ganti — tanpa biaya tersembunyi.', 'order' => 3],
        ];
        foreach ($items as $item) {
            WhyUsItem::updateOrCreate(['title' => $item['title']], $item);
        }

        // Stats
        $stats = [
            ['label' => 'Pelanggan Puas', 'value' => '7K+', 'order' => 1],
            ['label' => 'Produk Unik', 'value' => '250+', 'order' => 2],
            ['label' => 'Tahun Berpengalaman', 'value' => '12', 'order' => 3],
            ['label' => 'Kota Terjangkau', 'value' => '38', 'order' => 4],
        ];
        foreach ($stats as $stat) {
            WhyUsStat::updateOrCreate(['label' => $stat['label']], $stat);
        }
    }
}
