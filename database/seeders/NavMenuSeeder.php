<?php

namespace Database\Seeders;

use App\Models\NavMenu;
use Illuminate\Database\Seeder;

class NavMenuSeeder extends Seeder
{
    public function run()
    {
        // Hapus data lama (opsional)
        NavMenu::truncate();

        $menus = [
            ['label' => 'Beranda', 'url' => '/', 'order' => 1, 'is_active' => true],
            ['label' => 'Toko', 'url' => '/produk', 'order' => 2, 'is_active' => true],
            ['label' => 'Tentang', 'url' => '/tentang', 'order' => 3, 'is_active' => true],
            ['label' => 'FAQ', 'url' => '/faq', 'order' => 4, 'is_active' => true],
            ['label' => 'Kontak', 'url' => '/kontak', 'order' => 5, 'is_active' => true],
        ];

        foreach ($menus as $menu) {
            NavMenu::create($menu);
        }
    }
}