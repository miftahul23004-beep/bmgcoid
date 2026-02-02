<?php

namespace Database\Seeders;

use App\Models\PageContent;
use Illuminate\Database\Seeder;

class PageContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $contents = [
            // ========== ABOUT PAGE ==========
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'badge',
                'content' => ['id' => 'Terpercaya Sejak 2011', 'en' => 'Trusted Since 2011'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'title',
                'content' => ['id' => 'Mitra Terpercaya untuk Besi Baja Berkualitas', 'en' => 'Your Trusted Partner for Quality Steel'],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'hero',
                'key' => 'description',
                'content' => [
                    'id' => 'PT. Berkah Mandiri Globalindo merupakan distributor besi baja terkemuka yang melayani kebutuhan konstruksi dan industri di seluruh Indonesia dengan komitmen pada kualitas, pelayanan, dan harga kompetitif.',
                    'en' => 'PT. Berkah Mandiri Globalindo has been a leading steel distributor serving construction and industrial needs across Indonesia with commitment to quality, service, and competitive pricing.'
                ],
                'type' => 'text',
                'order' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'story',
                'key' => 'badge',
                'content' => ['id' => 'Cerita Kami', 'en' => 'Our Story'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'story',
                'key' => 'title',
                'content' => ['id' => 'Membangun Indonesia dengan Besi Baja Berkualitas', 'en' => 'Building Indonesia with Quality Steel'],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'story',
                'key' => 'paragraph_1',
                'content' => [
                    'id' => 'Sejak 2011, PT. Berkah Mandiri Globalindo telah menjadi garda terdepan industri distribusi besi baja Indonesia, melayani ribuan proyek konstruksi di seluruh nusantara.',
                    'en' => 'Since 2011, PT. Berkah Mandiri Globalindo has been at the forefront of Indonesia\'s steel distribution industry, serving thousands of construction projects across the archipelago.'
                ],
                'type' => 'text',
                'order' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'story',
                'key' => 'paragraph_2',
                'content' => [
                    'id' => 'Kami mengkhususkan diri dalam menyediakan produk besi baja berkualitas tinggi termasuk besi beton, hollow, pipa, dan berbagai profil, semuanya bersertifikat SNI dan memenuhi standar kualitas internasional.',
                    'en' => 'We specialize in providing high-quality steel products including rebar, hollow sections, pipes, and various profiles, all certified to meet SNI standards and international quality benchmarks.'
                ],
                'type' => 'text',
                'order' => 4,
            ],
            [
                'page' => 'about',
                'section' => 'stats',
                'key' => 'years_experience',
                'content' => ['id' => '14+', 'en' => '14+'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'stats',
                'key' => 'clients',
                'content' => ['id' => '500+', 'en' => '500+'],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'stats',
                'key' => 'projects',
                'content' => ['id' => '1000+', 'en' => '1000+'],
                'type' => 'text',
                'order' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'stats',
                'key' => 'products',
                'content' => ['id' => '300+', 'en' => '300+'],
                'type' => 'text',
                'order' => 4,
            ],

            // ========== VISION MISSION PAGE ==========
            [
                'page' => 'vision-mission',
                'section' => 'hero',
                'key' => 'title',
                'content' => ['id' => 'Visi & Misi', 'en' => 'Vision & Mission'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'hero',
                'key' => 'description',
                'content' => [
                    'id' => 'Panduan dan komitmen kami dalam melayani Indonesia',
                    'en' => 'Our guidance and commitment in serving Indonesia'
                ],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'vision',
                'key' => 'title',
                'content' => ['id' => 'Visi Kami', 'en' => 'Our Vision'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'vision',
                'key' => 'content',
                'content' => [
                    'id' => 'Menjadi distributor besi baja terdepan dan terpercaya di Indonesia yang memberikan solusi terbaik untuk kebutuhan konstruksi dan industri dengan kualitas produk unggulan, pelayanan prima, dan harga kompetitif.',
                    'en' => 'To become the leading and trusted steel distributor in Indonesia that provides the best solutions for construction and industrial needs with superior product quality, excellent service, and competitive pricing.'
                ],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'title',
                'content' => ['id' => 'Misi Kami', 'en' => 'Our Mission'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'item_1',
                'content' => [
                    'id' => 'Menyediakan produk besi baja berkualitas tinggi dengan sertifikasi SNI',
                    'en' => 'Provide high-quality steel products with SNI certification'
                ],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'item_2',
                'content' => [
                    'id' => 'Memberikan pelayanan cepat, ramah, dan profesional kepada setiap pelanggan',
                    'en' => 'Provide fast, friendly, and professional service to every customer'
                ],
                'type' => 'text',
                'order' => 3,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'item_3',
                'content' => [
                    'id' => 'Menawarkan harga kompetitif dengan sistem pembayaran fleksibel',
                    'en' => 'Offer competitive prices with flexible payment systems'
                ],
                'type' => 'text',
                'order' => 4,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'item_4',
                'content' => [
                    'id' => 'Membangun hubungan jangka panjang yang saling menguntungkan dengan mitra bisnis',
                    'en' => 'Build mutually beneficial long-term relationships with business partners'
                ],
                'type' => 'text',
                'order' => 5,
            ],
            [
                'page' => 'vision-mission',
                'section' => 'mission',
                'key' => 'item_5',
                'content' => [
                    'id' => 'Berkontribusi pada pembangunan infrastruktur Indonesia yang berkelanjutan',
                    'en' => 'Contribute to sustainable Indonesian infrastructure development'
                ],
                'type' => 'text',
                'order' => 6,
            ],

            // ========== CONTACT PAGE ==========
            [
                'page' => 'contact',
                'section' => 'hero',
                'key' => 'title',
                'content' => ['id' => 'Hubungi Kami', 'en' => 'Contact Us'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'contact',
                'section' => 'hero',
                'key' => 'description',
                'content' => [
                    'id' => 'Tim kami siap membantu kebutuhan besi baja Anda. Hubungi kami sekarang untuk konsultasi gratis dan penawaran terbaik.',
                    'en' => 'Our team is ready to help with your steel needs. Contact us now for free consultation and the best offers.'
                ],
                'type' => 'text',
                'order' => 2,
            ],

            // ========== VALUES/KEUNGGULAN ==========
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_1_title',
                'content' => ['id' => 'Kualitas Terjamin', 'en' => 'Guaranteed Quality'],
                'type' => 'text',
                'order' => 1,
            ],
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_1_desc',
                'content' => [
                    'id' => 'Semua produk bersertifikat SNI dan memenuhi standar kualitas internasional',
                    'en' => 'All products are SNI certified and meet international quality standards'
                ],
                'type' => 'text',
                'order' => 2,
            ],
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_2_title',
                'content' => ['id' => 'Harga Kompetitif', 'en' => 'Competitive Pricing'],
                'type' => 'text',
                'order' => 3,
            ],
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_2_desc',
                'content' => [
                    'id' => 'Penawaran harga terbaik untuk partai besar maupun eceran',
                    'en' => 'Best price offers for both wholesale and retail'
                ],
                'type' => 'text',
                'order' => 4,
            ],
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_3_title',
                'content' => ['id' => 'Pengiriman Cepat', 'en' => 'Fast Delivery'],
                'type' => 'text',
                'order' => 5,
            ],
            [
                'page' => 'about',
                'section' => 'values',
                'key' => 'value_3_desc',
                'content' => [
                    'id' => 'Armada pengiriman sendiri menjamin ketepatan waktu',
                    'en' => 'Own delivery fleet ensures on-time delivery'
                ],
                'type' => 'text',
                'order' => 6,
            ],
        ];

        foreach ($contents as $content) {
            PageContent::updateOrCreate(
                [
                    'page' => $content['page'],
                    'section' => $content['section'],
                    'key' => $content['key'],
                ],
                [
                    'content' => $content['content'],
                    'type' => $content['type'],
                    'order' => $content['order'],
                    'is_active' => true,
                ]
            );
        }
    }
}
