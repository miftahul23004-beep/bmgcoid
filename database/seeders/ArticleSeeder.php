<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;

class ArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Create tags
        $tags = [
            ['name' => 'Tips Konstruksi', 'slug' => 'tips-konstruksi'],
            ['name' => 'Info Produk', 'slug' => 'info-produk'],
            ['name' => 'Berita Industri', 'slug' => 'berita-industri'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
            ['name' => 'Promo', 'slug' => 'promo'],
        ];

        foreach ($tags as $tagData) {
            Tag::create($tagData);
        }

        $author = User::first();

        $articles = [
            [
                'title' => [
                    'id' => 'Panduan Memilih Besi Beton yang Tepat untuk Konstruksi Rumah',
                    'en' => 'Guide to Choosing the Right Rebar for Home Construction'
                ],
                'slug' => 'panduan-memilih-besi-beton-yang-tepat-untuk-konstruksi-rumah',
                'content' => [
                    'id' => '<p>Memilih besi beton yang tepat sangat penting untuk kekokohan struktur bangunan. Berikut adalah panduan lengkap untuk memilih besi beton yang sesuai dengan kebutuhan konstruksi rumah Anda.</p><h2>1. Kenali Jenis Besi Beton</h2><p>Ada dua jenis utama besi beton: besi polos dan besi ulir. Besi polos cocok untuk konstruksi ringan, sementara besi ulir memberikan cengkraman lebih baik dengan beton.</p><h2>2. Perhatikan Standar SNI</h2><p>Pastikan besi beton yang Anda beli memiliki sertifikasi SNI untuk menjamin kualitas dan keamanan.</p><h2>3. Sesuaikan dengan Kebutuhan</h2><p>Untuk rumah 1 lantai, umumnya cukup menggunakan besi diameter 10-12mm. Untuk rumah 2 lantai atau lebih, diperlukan besi dengan diameter lebih besar.</p>',
                    'en' => '<p>Choosing the right rebar is crucial for the structural integrity of buildings. Here is a complete guide to selecting the appropriate rebar for your home construction needs.</p><h2>1. Know the Types of Rebar</h2><p>There are two main types of rebar: plain round bar and deformed bar. Plain bars are suitable for light construction, while deformed bars provide better grip with concrete.</p><h2>2. Check SNI Standards</h2><p>Make sure the rebar you buy has SNI certification to guarantee quality and safety.</p><h2>3. Match Your Needs</h2><p>For 1-story houses, 10-12mm diameter rebar is usually sufficient. For 2-story or higher buildings, larger diameter rebar is required.</p>'
                ],
                'excerpt' => [
                    'id' => 'Panduan lengkap memilih besi beton yang tepat untuk konstruksi rumah Anda.',
                    'en' => 'Complete guide to choosing the right rebar for your home construction.'
                ],
                'status' => 'published',
                'published_at' => now()->subDays(5),
                'tags' => ['tips-konstruksi', 'info-produk'],
            ],
            [
                'title' => [
                    'id' => 'Perbedaan Besi Hollow Galvanis dan Besi Hollow Hitam',
                    'en' => 'Differences Between Galvanized and Black Hollow Sections'
                ],
                'slug' => 'perbedaan-besi-hollow-galvanis-dan-besi-hollow-hitam',
                'content' => [
                    'id' => '<p>Besi hollow adalah material yang sangat populer dalam konstruksi. Terdapat dua jenis utama: galvanis dan hitam. Berikut perbedaannya:</p><h2>Besi Hollow Galvanis</h2><p>Dilapisi zinc untuk perlindungan terhadap karat. Cocok untuk aplikasi outdoor dan area dengan kelembaban tinggi.</p><h2>Besi Hollow Hitam</h2><p>Tidak memiliki lapisan anti karat. Harga lebih ekonomis, cocok untuk aplikasi indoor atau yang akan dicat.</p>',
                    'en' => '<p>Hollow sections are very popular in construction. There are two main types: galvanized and black. Here are the differences:</p><h2>Galvanized Hollow Section</h2><p>Coated with zinc for rust protection. Suitable for outdoor applications and high humidity areas.</p><h2>Black Hollow Section</h2><p>Does not have anti-rust coating. More economical price, suitable for indoor applications or those that will be painted.</p>'
                ],
                'excerpt' => [
                    'id' => 'Memahami perbedaan besi hollow galvanis dan hitam untuk memilih yang tepat.',
                    'en' => 'Understanding the differences between galvanized and black hollow sections.'
                ],
                'status' => 'published',
                'published_at' => now()->subDays(10),
                'tags' => ['info-produk', 'tips-konstruksi'],
            ],
            [
                'title' => [
                    'id' => 'Tips Menyimpan Material Besi agar Tidak Mudah Berkarat',
                    'en' => 'Tips for Storing Steel Materials to Prevent Rust'
                ],
                'slug' => 'tips-menyimpan-material-besi-agar-tidak-mudah-berkarat',
                'content' => [
                    'id' => '<p>Penyimpanan yang tepat sangat penting untuk menjaga kualitas material besi. Berikut tips menyimpan besi agar awet:</p><h2>1. Hindari Kontak dengan Air</h2><p>Simpan di tempat yang kering dan terlindung dari hujan.</p><h2>2. Gunakan Alas</h2><p>Letakkan besi di atas pallet atau alas untuk menghindari kontak langsung dengan tanah.</p><h2>3. Berikan Lapisan Pelindung</h2><p>Untuk penyimpanan jangka panjang, olesi dengan oli atau lapisan anti karat.</p>',
                    'en' => '<p>Proper storage is crucial for maintaining the quality of steel materials. Here are tips for storing steel to keep it in good condition:</p><h2>1. Avoid Contact with Water</h2><p>Store in a dry place protected from rain.</p><h2>2. Use a Base</h2><p>Place steel on pallets or bases to avoid direct contact with the ground.</p><h2>3. Apply Protective Coating</h2><p>For long-term storage, apply oil or anti-rust coating.</p>'
                ],
                'excerpt' => [
                    'id' => 'Tips praktis menyimpan material besi agar tidak berkarat dan tetap berkualitas.',
                    'en' => 'Practical tips for storing steel materials to prevent rust and maintain quality.'
                ],
                'status' => 'published',
                'published_at' => now()->subDays(15),
                'tags' => ['tips-konstruksi', 'tutorial'],
            ],
        ];

        foreach ($articles as $articleData) {
            $tagSlugs = $articleData['tags'];
            unset($articleData['tags']);
            
            $articleData['author_id'] = $author?->id;
            $article = Article::create($articleData);
            
            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id');
            $article->tags()->attach($tagIds);
        }
    }
}
