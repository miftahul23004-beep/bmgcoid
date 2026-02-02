<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'key' => 'hero',
                'name' => 'Hero Slider',
                'name_en' => 'Hero Slider',
                'description' => 'Banner slider utama di bagian atas homepage',
                'is_active' => true,
                'sort_order' => 1,
                'bg_color' => 'white',
            ],
            [
                'key' => 'stats',
                'name' => 'Statistik',
                'name_en' => 'Statistics',
                'description' => 'Angka-angka pencapaian perusahaan',
                'is_active' => true,
                'sort_order' => 2,
                'bg_color' => 'white',
            ],
            [
                'key' => 'categories',
                'name' => 'Kategori Produk',
                'name_en' => 'Product Categories',
                'description' => 'Grid kategori produk',
                'is_active' => true,
                'sort_order' => 3,
                'bg_color' => 'gray-50',
            ],
            [
                'key' => 'featured',
                'name' => 'Produk Unggulan',
                'name_en' => 'Featured Products',
                'description' => 'Produk-produk unggulan',
                'is_active' => true,
                'sort_order' => 4,
                'bg_color' => 'white',
            ],
            [
                'key' => 'new_products',
                'name' => 'Produk Terbaru',
                'name_en' => 'New Products',
                'description' => 'Produk-produk terbaru',
                'is_active' => false, // Hidden by default as per user request
                'sort_order' => 5,
                'bg_color' => 'gradient-dark',
            ],
            [
                'key' => 'clients',
                'name' => 'Klien & Partner',
                'name_en' => 'Clients & Partners',
                'description' => 'Logo klien dan partner',
                'is_active' => true,
                'sort_order' => 6,
                'bg_color' => 'gray-50',
            ],
            [
                'key' => 'marketplace',
                'name' => 'Marketplace',
                'name_en' => 'Marketplace',
                'description' => 'Link ke marketplace (Tokopedia, Shopee, dll)',
                'is_active' => true,
                'sort_order' => 7,
                'bg_color' => 'white',
            ],
            [
                'key' => 'why_us',
                'name' => 'Mengapa Kami',
                'name_en' => 'Why Choose Us',
                'description' => 'Keunggulan perusahaan',
                'is_active' => true,
                'sort_order' => 8,
                'bg_color' => 'gray-50',
            ],
            [
                'key' => 'testimonials',
                'name' => 'Testimoni',
                'name_en' => 'Testimonials',
                'description' => 'Testimoni pelanggan',
                'is_active' => true,
                'sort_order' => 9,
                'bg_color' => 'white',
            ],
            [
                'key' => 'articles',
                'name' => 'Artikel',
                'name_en' => 'Articles',
                'description' => 'Artikel terbaru',
                'is_active' => true,
                'sort_order' => 10,
                'bg_color' => 'gray-50',
            ],
            [
                'key' => 'service_areas',
                'name' => 'Area Layanan',
                'name_en' => 'Service Areas',
                'description' => 'Jangkauan layanan nasional',
                'is_active' => true,
                'sort_order' => 11,
                'bg_color' => 'gray-50',
            ],
            [
                'key' => 'cta',
                'name' => 'Call to Action',
                'name_en' => 'Call to Action',
                'description' => 'Ajakan untuk menghubungi',
                'is_active' => true,
                'sort_order' => 12,
                'bg_color' => 'gradient-secondary',
            ],
        ];

        foreach ($sections as $section) {
            HomepageSection::updateOrCreate(
                ['key' => $section['key']],
                $section
            );
        }
    }
}
