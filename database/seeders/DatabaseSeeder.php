<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Testimonial;
use App\Models\InstagramPost;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. USER ADMIN
        User::firstOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin Gua',
                'password' => Hash::make('12345678'),
                'role' => 'admin',
            ]
        );

        // 2. KATEGORI UTAMA
        $categories = [
            ['name' => 'Ruang Tamu', 'slug' => 'ruang-tamu', 'image' => 'categories/living-room.jpg'],
            ['name' => 'Kamar Tidur', 'slug' => 'kamar-tidur', 'image' => 'categories/bedroom.jpg'],
            ['name' => 'Ruang Makan', 'slug' => 'ruang-makan', 'image' => 'categories/dining-room.jpg'],
        ];

        foreach ($categories as $catData) {
            Category::firstOrCreate(
                ['slug' => $catData['slug']],
                [
                    'name' => $catData['name'],
                    'slug' => $catData['slug'],
                    'image' => $catData['image'],
                    'is_active' => true,
                ]
            );
        }

        $livingCat = Category::where('slug', 'ruang-tamu')->first();

        // 3. PRODUK CONTOH
        Product::firstOrCreate(
            ['slug' => 'sofa-lounge-velvet'],
            [
                'category_id' => $livingCat->id,
                'name' => 'Sofa Lounge Velvet Sagara',
                'slug' => 'sofa-lounge-velvet',
                'description' => 'Sofa premium dengan bahan velvet anti gores, rangka kayu jati solid. Nyaman untuk bersantai bersama keluarga.',
                'price' => 12500000,
                'discount_price' => null,
                'stock' => 8,
                'main_image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?q=80&w=600&auto=format&fit=crop',
                'is_featured' => true,
                'is_bestseller' => true,
                'is_new' => false,
                'is_active' => true,
            ]
        );

        // 4. TESTIMONIAL (gunakan updateOrCreate untuk menghindari error static analysis)
        $testimonials = [
            [
                'customer_name' => 'Amelia Rasyid',
                'location' => 'Surabaya',
                'product_name' => 'Kursi Santai Velvet',
                'content' => 'Sofa yang saya pesan tiba dalam kondisi sempurna. Kualitas kayu dan kainnya jauh melampaui ekspektasi. Layanan pengiriman pun sangat profesional.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Budi Wicaksono',
                'location' => 'Bandung',
                'product_name' => 'Meja Makan Kayu Jati',
                'content' => 'Meja makan dari kayu jati yang saya beli sudah 2 tahun, tidak ada tanda-tanda kerusakan sama sekali. BeWood memang benar-benar menjaga kualitas.',
                'rating' => 5,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Sinta Noviana',
                'location' => 'Yogyakarta',
                'product_name' => 'Paket Kamar Tidur',
                'content' => 'Tim BeWood sangat responsif saat saya konsultasi desain untuk kamar tidur. Hasilnya, ruangan saya sekarang terasa seperti dari majalah interior.',
                'rating' => 5,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testi) {
            Testimonial::updateOrCreate(
                [
                    'customer_name' => $testi['customer_name'],
                    'product_name' => $testi['product_name'],
                ],
                $testi
            );
        }

        // 5. INSTAGRAM POSTS
        $instagramPosts = [
            [
                'instagram_url' => 'https://www.instagram.com/p/DYGy9-wlJgw/?utm_source=ig_web_copy_link&igsh=MzRlODBiNWFlZA==',
                'image' => '',
                'is_active' => true,
            ],
            [
                'instagram_url' => 'https://www.instagram.com/p/example2',
                'image' => 'https://images.unsplash.com/photo-1555041469-a586c61ea9bc?w=600&auto=format',
                'is_active' => true,
            ],
            [
                'instagram_url' => 'https://www.instagram.com/p/example3',
                'image' => 'https://images.unsplash.com/photo-1616594039964-ae9021a400a0?w=600&auto=format',
                'is_active' => true,
            ],
            [
                'instagram_url' => 'https://www.instagram.com/p/example4',
                'image' => 'https://images.unsplash.com/photo-1617806118233-18e1de247200?w=600&auto=format',
                'is_active' => true,
            ],
            [
                'instagram_url' => 'https://www.instagram.com/p/example5',
                'image' => 'https://images.unsplash.com/photo-1586023492125-27b2c045efd7?w=600&auto=format',
                'is_active' => true,
            ],
            [
                'instagram_url' => 'https://www.instagram.com/p/example6',
                'image' => 'https://images.unsplash.com/photo-1616486338812-3dadae4b4ace?w=600&auto=format',
                'is_active' => true,
            ],
        ];

        foreach ($instagramPosts as $post) {
            InstagramPost::updateOrCreate(
                ['instagram_url' => $post['instagram_url']],
                $post
            );
        }
    }
}