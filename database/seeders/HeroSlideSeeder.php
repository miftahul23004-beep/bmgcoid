<?php

namespace Database\Seeders;

use App\Models\HeroSlide;
use Illuminate\Database\Seeder;

class HeroSlideSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $slides = [
            [
                'title_id' => 'Supplier Besi Terpercaya untuk Industri & Konstruksi',
                'title_en' => 'Your Trusted Steel Partner for Industry & Construction',
                'subtitle_id' => 'Distributor besi baja berkualitas dengan pelayanan terbaik untuk kebutuhan konstruksi dan industri Anda. Dipercaya oleh ratusan perusahaan di seluruh Indonesia.',
                'subtitle_en' => 'Quality steel distributor with the best service for your construction and industrial needs. Trusted by hundreds of companies across Indonesia.',
                'image' => '/images/hero-1.jpg',
                'gradient_class' => 'from-primary-900/95 via-primary-800/90 to-primary-700/85',
                'text_color' => 'white',
                'primary_button_text_id' => 'Lihat Produk',
                'primary_button_text_en' => 'View Products',
                'primary_button_url' => '/products',
                'secondary_button_text_id' => 'Minta Penawaran',
                'secondary_button_text_en' => 'Get Quote',
                'secondary_button_url' => '/quote',
                'badge_text_id' => 'Terpercaya Sejak 2011',
                'badge_text_en' => 'Trusted Since 2011',
                'is_active' => true,
                'order' => 1,
            ],
            [
                'title_id' => 'Produk Besi Baja Berkualitas Premium',
                'title_en' => 'Premium Quality Steel Products',
                'subtitle_id' => 'Produk bersertifikat SNI dengan quality control ketat. Dipercaya oleh 500+ perusahaan untuk kebutuhan konstruksi dan industri mereka.',
                'subtitle_en' => 'SNI certified products with strict quality control. Trusted by 500+ companies for their construction and industrial needs.',
                'image' => '/images/hero-2.jpg',
                'gradient_class' => 'from-secondary-900/95 via-secondary-800/90 to-secondary-700/85',
                'text_color' => 'white',
                'primary_button_text_id' => 'Lihat Katalog',
                'primary_button_text_en' => 'View Catalog',
                'primary_button_url' => '/products',
                'secondary_button_text_id' => 'Hubungi Kami',
                'secondary_button_text_en' => 'Contact Us',
                'secondary_button_url' => '/contact',
                'badge_text_id' => 'Bersertifikat SNI',
                'badge_text_en' => 'SNI Certified',
                'is_active' => true,
                'order' => 2,
            ],
            [
                'title_id' => 'Pengiriman Cepat & Terpercaya',
                'title_en' => 'Fast & Reliable Delivery',
                'subtitle_id' => 'Jangkauan armada sendiri ke 34 provinsi di Indonesia. Pengiriman tepat waktu dengan sistem tracking real-time.',
                'subtitle_en' => 'Own fleet coverage to 34 provinces in Indonesia. On-time delivery guaranteed with real-time tracking system.',
                'image' => '/images/hero-3.jpg',
                'gradient_class' => 'from-green-900/95 via-green-800/90 to-green-700/85',
                'text_color' => 'white',
                'primary_button_text_id' => 'Lihat Layanan',
                'primary_button_text_en' => 'View Services',
                'primary_button_url' => '/about',
                'secondary_button_text_id' => 'Cek Ongkir',
                'secondary_button_text_en' => 'Check Shipping',
                'secondary_button_url' => '/contact',
                'badge_text_id' => 'Pengiriman Se-Indonesia',
                'badge_text_en' => 'Nationwide Delivery',
                'is_active' => true,
                'order' => 3,
            ],
        ];

        foreach ($slides as $slide) {
            HeroSlide::create($slide);
        }
    }
}
