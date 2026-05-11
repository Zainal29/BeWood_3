<?php

namespace Database\Seeders;

use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class TestimonialSeeder extends Seeder
{
    public function run(): void
    {
        Setting::updateOrCreate(['key' => 'testimonials_title'], ['value' => 'Kata Mereka']);
        Setting::updateOrCreate(['key' => 'testimonials_subtitle'], ['value' => 'Pengalaman nyata dari keluarga Indonesia yang telah mempercayakan rumah mereka pada BeWood.']);

        $testimonials = [
            [
                'customer_name' => 'Amelia Rasyid',
                'location' => 'Surabaya',
                'product_name' => 'Kursi Santai Velvet',
                'content' => 'Sofa yang saya pesan tiba dalam kondisi sempurna. Kualitas kayu dan kainnya jauh melampaui ekspektasi. Layanan pengiriman pun sangat profesional.',
                'rating' => 5,
                'order' => 1,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Budi Wicaksono',
                'location' => 'Bandung',
                'product_name' => 'Meja Makan Kayu Jati',
                'content' => 'Meja makan dari kayu jati yang saya beli sudah 2 tahun, tidak ada tanda-tanda kerusakan sama sekali. BeWood memang benar-benar menjaga kualitas.',
                'rating' => 5,
                'order' => 2,
                'is_active' => true,
            ],
            [
                'customer_name' => 'Sinta Noviana',
                'location' => 'Yogyakarta',
                'product_name' => 'Paket Kamar Tidur',
                'content' => 'Tim BeWood sangat responsif saat saya konsultasi desain untuk kamar tidur. Hasilnya, ruangan saya sekarang terasa seperti dari majalah interior.',
                'rating' => 5,
                'order' => 3,
                'is_active' => true,
            ],
        ];
        foreach ($testimonials as $t) {
            Testimonial::updateOrCreate(['customer_name' => $t['customer_name']], $t);
        }
    }
}
