<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SampleArticleSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure tags exist
        $this->createTags();

        $author = User::first();

        $articles = [
            // Article 1: Comprehensive Guide
            [
                'title' => [
                    'id' => 'Panduan Lengkap: 10 Jenis Besi Konstruksi dan Penggunaannya dalam Proyek Bangunan Modern',
                    'en' => 'Complete Guide: 10 Types of Construction Steel and Their Uses in Modern Building Projects'
                ],
                'slug' => 'panduan-lengkap-10-jenis-besi-konstruksi-dan-penggunaannya',
                'excerpt' => [
                    'id' => 'Temukan berbagai jenis besi konstruksi mulai dari besi beton, besi hollow, WF beam, hingga wiremesh. Panduan lengkap untuk memilih material yang tepat untuk proyek bangunan Anda.',
                    'en' => 'Discover various types of construction steel from rebar, hollow sections, WF beams, to wiremesh. A complete guide to choosing the right materials for your building project.'
                ],
                'content' => [
                    'id' => $this->getArticle1ContentID(),
                    'en' => $this->getArticle1ContentEN()
                ],
                'meta_title' => [
                    'id' => 'Panduan Lengkap Jenis Besi Konstruksi - BMG Steel',
                    'en' => 'Complete Guide to Construction Steel Types - BMG Steel'
                ],
                'meta_description' => [
                    'id' => 'Pelajari 10 jenis besi konstruksi beserta fungsi dan kegunaannya. Tips memilih besi yang tepat untuk proyek bangunan dari ahli material konstruksi.',
                    'en' => 'Learn about 10 types of construction steel along with their functions and uses. Tips for choosing the right steel for building projects from construction material experts.'
                ],
                'meta_keywords' => [
                    'id' => 'jenis besi konstruksi, besi beton, besi hollow, WF beam, wiremesh, material bangunan, konstruksi baja',
                    'en' => 'construction steel types, rebar, hollow section, WF beam, wiremesh, building materials, steel construction'
                ],
                'type' => 'article',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(3),
                'is_featured' => true,
                'allow_comments' => true,
                'view_count' => 1250,
                'share_count' => 45,
                'tags' => ['tips-konstruksi', 'info-produk', 'tutorial'],
            ],

            // Article 2: Industry News
            [
                'title' => [
                    'id' => 'Tren Harga Besi 2026: Analisis Pasar dan Prediksi untuk Pelaku Industri Konstruksi',
                    'en' => 'Steel Price Trends 2026: Market Analysis and Predictions for Construction Industry Players'
                ],
                'slug' => 'tren-harga-besi-2026-analisis-pasar-prediksi-industri-konstruksi',
                'excerpt' => [
                    'id' => 'Analisis mendalam tentang tren harga besi tahun 2026. Faktor-faktor yang mempengaruhi harga, prediksi pasar, dan strategi pembelian yang tepat untuk kontraktor dan developer.',
                    'en' => 'In-depth analysis of steel price trends in 2026. Factors affecting prices, market predictions, and the right purchasing strategies for contractors and developers.'
                ],
                'content' => [
                    'id' => $this->getArticle2ContentID(),
                    'en' => $this->getArticle2ContentEN()
                ],
                'meta_title' => [
                    'id' => 'Tren Harga Besi 2026 - Analisis & Prediksi Pasar | BMG Steel',
                    'en' => 'Steel Price Trends 2026 - Market Analysis & Predictions | BMG Steel'
                ],
                'meta_description' => [
                    'id' => 'Update terbaru tren harga besi 2026. Analisis pasar, faktor pengaruh harga, dan prediksi untuk industri konstruksi Indonesia.',
                    'en' => 'Latest update on 2026 steel price trends. Market analysis, price influencing factors, and predictions for Indonesia construction industry.'
                ],
                'meta_keywords' => [
                    'id' => 'harga besi 2026, tren harga baja, analisis pasar besi, prediksi harga besi, industri konstruksi',
                    'en' => 'steel prices 2026, steel price trends, steel market analysis, steel price predictions, construction industry'
                ],
                'type' => 'news',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(7),
                'is_featured' => true,
                'allow_comments' => true,
                'view_count' => 2340,
                'share_count' => 89,
                'tags' => ['berita-industri', 'info-produk'],
            ],

            // Article 3: Tutorial/Tips
            [
                'title' => [
                    'id' => 'Tutorial: Cara Menghitung Kebutuhan Besi untuk Pondasi Rumah 2 Lantai',
                    'en' => 'Tutorial: How to Calculate Steel Requirements for 2-Story House Foundation'
                ],
                'slug' => 'tutorial-cara-menghitung-kebutuhan-besi-pondasi-rumah-2-lantai',
                'excerpt' => [
                    'id' => 'Langkah demi langkah menghitung kebutuhan besi beton untuk pondasi rumah 2 lantai. Dilengkapi rumus perhitungan, tabel referensi, dan tips menghemat biaya material.',
                    'en' => 'Step by step guide to calculating rebar requirements for 2-story house foundations. Complete with calculation formulas, reference tables, and tips to save material costs.'
                ],
                'content' => [
                    'id' => $this->getArticle3ContentID(),
                    'en' => $this->getArticle3ContentEN()
                ],
                'meta_title' => [
                    'id' => 'Cara Menghitung Kebutuhan Besi Pondasi Rumah | Tutorial BMG Steel',
                    'en' => 'How to Calculate Foundation Steel Requirements | BMG Steel Tutorial'
                ],
                'meta_description' => [
                    'id' => 'Tutorial lengkap menghitung kebutuhan besi beton untuk pondasi rumah 2 lantai. Rumus perhitungan, contoh kasus, dan tips dari ahli konstruksi.',
                    'en' => 'Complete tutorial on calculating rebar requirements for 2-story house foundations. Calculation formulas, case examples, and tips from construction experts.'
                ],
                'meta_keywords' => [
                    'id' => 'menghitung kebutuhan besi, pondasi rumah, besi beton, tutorial konstruksi, RAB besi',
                    'en' => 'calculate steel requirements, house foundation, rebar, construction tutorial, steel BOQ'
                ],
                'type' => 'tutorial',
                'status' => 'published',
                'published_at' => Carbon::now()->subDays(14),
                'is_featured' => false,
                'allow_comments' => true,
                'view_count' => 3567,
                'share_count' => 156,
                'tags' => ['tutorial', 'tips-konstruksi'],
            ],
        ];

        foreach ($articles as $articleData) {
            $tagSlugs = $articleData['tags'];
            unset($articleData['tags']);
            
            $articleData['author_id'] = $author?->id;
            
            // Check if article already exists
            $existing = Article::where('slug', $articleData['slug'])->first();
            if ($existing) {
                $existing->update($articleData);
                $article = $existing;
            } else {
                $article = Article::create($articleData);
            }
            
            // Sync tags
            $tagIds = Tag::whereIn('slug', $tagSlugs)->pluck('id');
            $article->tags()->sync($tagIds);
        }

        $this->command->info('3 sample articles created successfully!');
    }

    private function createTags(): void
    {
        $tags = [
            ['name' => 'Tips Konstruksi', 'slug' => 'tips-konstruksi'],
            ['name' => 'Info Produk', 'slug' => 'info-produk'],
            ['name' => 'Berita Industri', 'slug' => 'berita-industri'],
            ['name' => 'Tutorial', 'slug' => 'tutorial'],
            ['name' => 'Promo', 'slug' => 'promo'],
        ];

        foreach ($tags as $tagData) {
            Tag::firstOrCreate(['slug' => $tagData['slug']], $tagData);
        }
    }

    private function getArticle1ContentID(): string
    {
        return <<<HTML
<p>Dalam dunia konstruksi, pemilihan material besi yang tepat sangat menentukan kualitas dan keamanan bangunan. Setiap jenis besi memiliki karakteristik dan fungsi yang berbeda-beda. Artikel ini akan membahas secara lengkap 10 jenis besi konstruksi yang paling umum digunakan beserta panduan penggunaannya.</p>

<h2>1. Besi Beton (Rebar)</h2>
<p>Besi beton adalah tulang punggung dari setiap konstruksi beton bertulang. Tersedia dalam dua jenis utama:</p>
<ul>
    <li><strong>Besi Beton Polos (BJTP)</strong> - Permukaan halus, cocok untuk konstruksi ringan dan pengikat</li>
    <li><strong>Besi Beton Ulir (BJTD)</strong> - Memiliki sirip untuk cengkraman optimal dengan beton</li>
</ul>

<blockquote>
    <p>"Untuk rumah 2 lantai, kami merekomendasikan penggunaan besi beton ulir diameter 12mm untuk kolom dan 10mm untuk balok. Ini memberikan keseimbangan optimal antara kekuatan dan biaya."</p>
</blockquote>

<h2>2. Besi Hollow (Hollow Section)</h2>
<p>Besi hollow berbentuk pipa kotak atau bulat, ideal untuk rangka atap, kanopi, dan furniture. Keunggulannya:</p>
<ol>
    <li>Ringan namun kuat</li>
    <li>Mudah dibentuk dan dilas</li>
    <li>Estetika modern</li>
    <li>Tersedia dalam berbagai ukuran</li>
</ol>

<h2>3. Besi WF (Wide Flange Beam)</h2>
<p>Besi WF berbentuk huruf H dan I, digunakan untuk struktur bangunan bertingkat tinggi, jembatan, dan konstruksi berat. Ukuran standar mulai dari WF 100 hingga WF 600.</p>

<h2>4. Besi Siku (Angle Bar)</h2>
<p>Berbentuk huruf L, besi siku digunakan untuk:</p>
<ul>
    <li>Rangka pintu dan jendela</li>
    <li>Bracing struktur</li>
    <li>Dudukan mesin</li>
    <li>Konstruksi tangga</li>
</ul>

<h2>5. Besi Plat (Steel Plate)</h2>
<p>Lembaran besi dengan berbagai ketebalan (1mm - 50mm), digunakan untuk lantai bordes, dinding baja, dan komponen mesin.</p>

<h2>6. Wiremesh</h2>
<p>Jaring besi yang dilas membentuk kotak-kotak seragam. Sangat efisien untuk pengecoran lantai dan dinding karena mempercepat pekerjaan.</p>

<table>
    <thead>
        <tr>
            <th>Tipe Wiremesh</th>
            <th>Diameter</th>
            <th>Ukuran Lubang</th>
            <th>Aplikasi</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>M4</td>
            <td>4mm</td>
            <td>15 x 15 cm</td>
            <td>Lantai ringan</td>
        </tr>
        <tr>
            <td>M6</td>
            <td>6mm</td>
            <td>15 x 15 cm</td>
            <td>Lantai standar</td>
        </tr>
        <tr>
            <td>M8</td>
            <td>8mm</td>
            <td>15 x 15 cm</td>
            <td>Lantai berat</td>
        </tr>
    </tbody>
</table>

<h2>7. Besi CNP (Channel)</h2>
<p>Berbentuk huruf C, banyak digunakan untuk rangka atap baja ringan dan gording. Populer karena harganya yang ekonomis.</p>

<h2>8. Pipa Besi (Steel Pipe)</h2>
<p>Tersedia dalam bentuk bulat, kotak, dan persegi panjang. Digunakan untuk pagar, railing, dan instalasi fluida.</p>

<h2>9. Besi Strip</h2>
<p>Besi berbentuk pita pipih, digunakan untuk ornamen, bracket, dan komponen kecil lainnya.</p>

<h2>10. Besi Cor (Cast Iron)</h2>
<p>Besi dengan kandungan karbon tinggi, cocok untuk komponen yang membutuhkan ketahanan aus seperti manhole cover dan katup.</p>

<h2>Tips Memilih Besi yang Tepat</h2>
<ol>
    <li><strong>Kenali kebutuhan struktur</strong> - Hitung beban yang akan ditanggung</li>
    <li><strong>Perhatikan standar SNI</strong> - Pastikan produk memiliki sertifikasi</li>
    <li><strong>Konsultasi dengan ahli</strong> - Libatkan insinyur sipil untuk proyek besar</li>
    <li><strong>Bandingkan harga</strong> - Cari supplier terpercaya dengan harga kompetitif</li>
    <li><strong>Pertimbangkan lokasi</strong> - Area pesisir butuh besi anti karat</li>
</ol>

<h2>Kesimpulan</h2>
<p>Memilih jenis besi yang tepat adalah investasi jangka panjang untuk keamanan dan kualitas bangunan Anda. Konsultasikan kebutuhan Anda dengan tim ahli kami untuk mendapatkan rekomendasi terbaik sesuai budget dan spesifikasi proyek.</p>
HTML;
    }

    private function getArticle1ContentEN(): string
    {
        return <<<HTML
<p>In the world of construction, choosing the right steel material is crucial for building quality and safety. Each type of steel has different characteristics and functions. This article will comprehensively discuss the 10 most commonly used types of construction steel along with usage guidelines.</p>

<h2>1. Rebar (Reinforcing Bar)</h2>
<p>Rebar is the backbone of every reinforced concrete construction. Available in two main types:</p>
<ul>
    <li><strong>Plain Round Bar (BJTP)</strong> - Smooth surface, suitable for light construction and ties</li>
    <li><strong>Deformed Bar (BJTD)</strong> - Has ribs for optimal grip with concrete</li>
</ul>

<blockquote>
    <p>"For 2-story houses, we recommend using 12mm diameter deformed rebar for columns and 10mm for beams. This provides the optimal balance between strength and cost."</p>
</blockquote>

<h2>2. Hollow Section</h2>
<p>Hollow sections come in square or round pipe shapes, ideal for roof frames, canopies, and furniture. Their advantages:</p>
<ol>
    <li>Light yet strong</li>
    <li>Easy to shape and weld</li>
    <li>Modern aesthetics</li>
    <li>Available in various sizes</li>
</ol>

<h2>3. WF Beam (Wide Flange Beam)</h2>
<p>WF beams are H and I-shaped, used for high-rise building structures, bridges, and heavy construction. Standard sizes range from WF 100 to WF 600.</p>

<h2>4. Angle Bar</h2>
<p>L-shaped, angle bars are used for:</p>
<ul>
    <li>Door and window frames</li>
    <li>Structural bracing</li>
    <li>Machine supports</li>
    <li>Stair construction</li>
</ul>

<h2>5. Steel Plate</h2>
<p>Steel sheets in various thicknesses (1mm - 50mm), used for checkered plates, steel walls, and machine components.</p>

<h2>6. Wiremesh</h2>
<p>Steel mesh welded into uniform squares. Very efficient for floor and wall casting as it speeds up work.</p>

<table>
    <thead>
        <tr>
            <th>Wiremesh Type</th>
            <th>Diameter</th>
            <th>Hole Size</th>
            <th>Application</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>M4</td>
            <td>4mm</td>
            <td>15 x 15 cm</td>
            <td>Light floor</td>
        </tr>
        <tr>
            <td>M6</td>
            <td>6mm</td>
            <td>15 x 15 cm</td>
            <td>Standard floor</td>
        </tr>
        <tr>
            <td>M8</td>
            <td>8mm</td>
            <td>15 x 15 cm</td>
            <td>Heavy floor</td>
        </tr>
    </tbody>
</table>

<h2>7. CNP Channel</h2>
<p>C-shaped, widely used for light steel roof frames and purlins. Popular due to its economical price.</p>

<h2>8. Steel Pipe</h2>
<p>Available in round, square, and rectangular shapes. Used for fences, railings, and fluid installations.</p>

<h2>9. Steel Strip</h2>
<p>Flat ribbon-shaped steel, used for ornaments, brackets, and other small components.</p>

<h2>10. Cast Iron</h2>
<p>Steel with high carbon content, suitable for components requiring wear resistance such as manhole covers and valves.</p>

<h2>Tips for Choosing the Right Steel</h2>
<ol>
    <li><strong>Know your structural needs</strong> - Calculate the load to be carried</li>
    <li><strong>Check SNI standards</strong> - Ensure products have certification</li>
    <li><strong>Consult experts</strong> - Involve civil engineers for large projects</li>
    <li><strong>Compare prices</strong> - Find trusted suppliers with competitive prices</li>
    <li><strong>Consider location</strong> - Coastal areas need rust-resistant steel</li>
</ol>

<h2>Conclusion</h2>
<p>Choosing the right type of steel is a long-term investment for the safety and quality of your building. Consult your needs with our expert team to get the best recommendations according to your budget and project specifications.</p>
HTML;
    }

    private function getArticle2ContentID(): string
    {
        return <<<HTML
<p>Industri konstruksi Indonesia memasuki tahun 2026 dengan berbagai tantangan dan peluang baru. Fluktuasi harga besi menjadi perhatian utama bagi para pelaku industri. Artikel ini menyajikan analisis mendalam tentang tren harga dan prediksi pasar untuk membantu Anda membuat keputusan bisnis yang tepat.</p>

<h2>Kondisi Pasar Besi Saat Ini</h2>
<p>Memasuki kuartal pertama 2026, harga besi menunjukkan tren yang relatif stabil dengan kenaikan moderat sebesar 3-5% dibandingkan akhir tahun 2025. Beberapa faktor utama yang mempengaruhi:</p>

<ul>
    <li>Pemulihan ekonomi global pasca pandemi</li>
    <li>Kebijakan impor baja nasional</li>
    <li>Mega proyek infrastruktur pemerintah</li>
    <li>Fluktuasi nilai tukar rupiah</li>
</ul>

<blockquote>
    <p>"Kami memperkirakan harga besi akan tetap stabil dengan kenaikan maksimal 8% hingga akhir 2026. Para kontraktor disarankan melakukan pembelian bertahap untuk mengantisipasi fluktuasi." - Asosiasi Besi Baja Indonesia</p>
</blockquote>

<h2>Tren Harga per Kategori Produk</h2>

<h3>Besi Beton</h3>
<p>Harga besi beton ulir SNI menunjukkan stabilitas dengan range harga:</p>
<table>
    <thead>
        <tr>
            <th>Diameter</th>
            <th>Harga/Batang (Jan 2026)</th>
            <th>Perubahan YoY</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>10mm</td>
            <td>Rp 65.000 - 75.000</td>
            <td>+4%</td>
        </tr>
        <tr>
            <td>12mm</td>
            <td>Rp 95.000 - 110.000</td>
            <td>+5%</td>
        </tr>
        <tr>
            <td>16mm</td>
            <td>Rp 165.000 - 185.000</td>
            <td>+3%</td>
        </tr>
    </tbody>
</table>

<h3>Besi Hollow</h3>
<p>Permintaan besi hollow meningkat signifikan seiring tren desain minimalis. Harga naik sekitar 6-8% dibandingkan tahun lalu.</p>

<h3>Besi WF dan H-Beam</h3>
<p>Proyek infrastruktur besar mendorong permintaan tinggi. Harga relatif stabil karena pasokan yang cukup.</p>

<h2>Faktor-faktor yang Mempengaruhi Harga</h2>

<ol>
    <li>
        <strong>Harga Bahan Baku Global</strong>
        <p>Iron ore dan scrap metal mengalami kenaikan 10-15% di pasar internasional, memberikan tekanan pada harga domestik.</p>
    </li>
    <li>
        <strong>Kebijakan Pemerintah</strong>
        <p>Peraturan anti-dumping dan bea masuk baja impor membantu melindungi produsen lokal namun berdampak pada harga.</p>
    </li>
    <li>
        <strong>Proyek Infrastruktur</strong>
        <p>IKN (Ibu Kota Nusantara), tol Trans Sumatera, dan proyek MRT mendorong permintaan tinggi.</p>
    </li>
    <li>
        <strong>Nilai Tukar</strong>
        <p>Rupiah yang stabil di kisaran Rp 15.500-16.000/USD membantu menjaga harga tetap terkendali.</p>
    </li>
</ol>

<h2>Prediksi Harga Q2-Q4 2026</h2>

<p>Berdasarkan analisis kami, berikut prediksi pergerakan harga:</p>

<ul>
    <li><strong>Q2 2026:</strong> Kenaikan 2-3% seiring musim konstruksi</li>
    <li><strong>Q3 2026:</strong> Stabil atau sedikit turun 1-2%</li>
    <li><strong>Q4 2026:</strong> Potensi kenaikan 3-4% menjelang akhir tahun</li>
</ul>

<h2>Strategi Pembelian yang Disarankan</h2>

<ol>
    <li><strong>Pembelian Bertahap</strong> - Hindari stock besar sekaligus, beli sesuai kebutuhan per fase proyek</li>
    <li><strong>Kontrak Jangka Panjang</strong> - Negosiasikan harga tetap dengan supplier untuk proyek besar</li>
    <li><strong>Pantau Pasar Rutin</strong> - Subscribe update harga dari distributor terpercaya</li>
    <li><strong>Diversifikasi Supplier</strong> - Jangan bergantung pada satu supplier saja</li>
    <li><strong>Manfaatkan Promo</strong> - Banyak distributor menawarkan diskon volume besar</li>
</ol>

<h2>Kesimpulan</h2>

<p>Pasar besi 2026 diprediksi relatif stabil dengan kenaikan terkendali. Pelaku industri konstruksi disarankan untuk merencanakan pembelian dengan cermat dan menjalin hubungan baik dengan supplier terpercaya. BMG Steel menyediakan update harga rutin dan konsultasi gratis untuk membantu Anda mengoptimalkan budget proyek.</p>
HTML;
    }

    private function getArticle2ContentEN(): string
    {
        return <<<HTML
<p>Indonesia's construction industry enters 2026 with various new challenges and opportunities. Steel price fluctuations are a major concern for industry players. This article presents an in-depth analysis of price trends and market predictions to help you make the right business decisions.</p>

<h2>Current Steel Market Conditions</h2>
<p>Entering Q1 2026, steel prices show a relatively stable trend with a moderate increase of 3-5% compared to the end of 2025. Key influencing factors include:</p>

<ul>
    <li>Post-pandemic global economic recovery</li>
    <li>National steel import policies</li>
    <li>Government infrastructure mega projects</li>
    <li>Rupiah exchange rate fluctuations</li>
</ul>

<blockquote>
    <p>"We expect steel prices to remain stable with a maximum increase of 8% until the end of 2026. Contractors are advised to make gradual purchases to anticipate fluctuations." - Indonesian Iron and Steel Association</p>
</blockquote>

<h2>Price Trends by Product Category</h2>

<h3>Rebar</h3>
<p>SNI deformed rebar prices show stability with price range:</p>
<table>
    <thead>
        <tr>
            <th>Diameter</th>
            <th>Price/Bar (Jan 2026)</th>
            <th>YoY Change</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>10mm</td>
            <td>IDR 65,000 - 75,000</td>
            <td>+4%</td>
        </tr>
        <tr>
            <td>12mm</td>
            <td>IDR 95,000 - 110,000</td>
            <td>+5%</td>
        </tr>
        <tr>
            <td>16mm</td>
            <td>IDR 165,000 - 185,000</td>
            <td>+3%</td>
        </tr>
    </tbody>
</table>

<h3>Hollow Section</h3>
<p>Hollow section demand has increased significantly along with minimalist design trends. Prices have risen about 6-8% compared to last year.</p>

<h3>WF and H-Beam</h3>
<p>Large infrastructure projects drive high demand. Prices are relatively stable due to adequate supply.</p>

<h2>Factors Affecting Prices</h2>

<ol>
    <li>
        <strong>Global Raw Material Prices</strong>
        <p>Iron ore and scrap metal have experienced 10-15% increases in international markets, putting pressure on domestic prices.</p>
    </li>
    <li>
        <strong>Government Policies</strong>
        <p>Anti-dumping regulations and import duties on steel help protect local producers but impact prices.</p>
    </li>
    <li>
        <strong>Infrastructure Projects</strong>
        <p>IKN (Nusantara Capital City), Trans Sumatera toll road, and MRT projects drive high demand.</p>
    </li>
    <li>
        <strong>Exchange Rate</strong>
        <p>Stable rupiah around IDR 15,500-16,000/USD helps keep prices under control.</p>
    </li>
</ol>

<h2>Q2-Q4 2026 Price Predictions</h2>

<p>Based on our analysis, here are the predicted price movements:</p>

<ul>
    <li><strong>Q2 2026:</strong> 2-3% increase along with construction season</li>
    <li><strong>Q3 2026:</strong> Stable or slight 1-2% decrease</li>
    <li><strong>Q4 2026:</strong> Potential 3-4% increase towards year-end</li>
</ul>

<h2>Recommended Purchasing Strategies</h2>

<ol>
    <li><strong>Gradual Purchasing</strong> - Avoid large stock purchases at once, buy according to project phase needs</li>
    <li><strong>Long-term Contracts</strong> - Negotiate fixed prices with suppliers for large projects</li>
    <li><strong>Regular Market Monitoring</strong> - Subscribe to price updates from trusted distributors</li>
    <li><strong>Supplier Diversification</strong> - Don't depend on just one supplier</li>
    <li><strong>Take Advantage of Promos</strong> - Many distributors offer large volume discounts</li>
</ol>

<h2>Conclusion</h2>

<p>The 2026 steel market is predicted to remain relatively stable with controlled increases. Construction industry players are advised to plan purchases carefully and maintain good relationships with trusted suppliers. BMG Steel provides regular price updates and free consultations to help you optimize your project budget.</p>
HTML;
    }

    private function getArticle3ContentID(): string
    {
        return <<<HTML
<p>Menghitung kebutuhan besi untuk pondasi adalah langkah krusial dalam merencanakan konstruksi rumah. Perhitungan yang akurat akan menghemat biaya dan memastikan struktur yang kokoh. Tutorial ini akan memandu Anda langkah demi langkah menghitung kebutuhan besi untuk pondasi rumah 2 lantai.</p>

<h2>Memahami Komponen Pondasi</h2>

<p>Sebelum menghitung, pahami dulu komponen utama pondasi rumah 2 lantai:</p>

<ol>
    <li><strong>Pondasi Footplat (Telapak)</strong> - Bagian yang kontak langsung dengan tanah</li>
    <li><strong>Sloof</strong> - Balok pengikat pondasi di atas footplat</li>
    <li><strong>Kolom Pondasi</strong> - Penyalur beban dari struktur atas ke footplat</li>
</ol>

<h2>Spesifikasi Standar untuk Rumah 2 Lantai</h2>

<p>Untuk rumah 2 lantai dengan struktur standar, gunakan spesifikasi berikut:</p>

<table>
    <thead>
        <tr>
            <th>Komponen</th>
            <th>Ukuran</th>
            <th>Besi Utama</th>
            <th>Besi Sengkang</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Footplat</td>
            <td>80 x 80 x 25 cm</td>
            <td>D12 - 8 batang/arah</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Sloof</td>
            <td>20 x 30 cm</td>
            <td>D12 - 4 batang</td>
            <td>Ø8 - jarak 15 cm</td>
        </tr>
        <tr>
            <td>Kolom</td>
            <td>25 x 25 cm</td>
            <td>D12 - 4 batang</td>
            <td>Ø8 - jarak 15 cm</td>
        </tr>
    </tbody>
</table>

<h2>Langkah Perhitungan</h2>

<h3>Langkah 1: Hitung Jumlah Titik Pondasi</h3>

<p>Untuk contoh rumah 8m x 10m dengan jarak kolom 4m:</p>
<ul>
    <li>Arah panjang: (10 ÷ 4) + 1 = 3.5, dibulatkan = 4 titik</li>
    <li>Arah lebar: (8 ÷ 4) + 1 = 3 titik</li>
    <li>Total titik: 4 x 3 = <strong>12 titik footplat</strong></li>
</ul>

<h3>Langkah 2: Hitung Kebutuhan Besi Footplat</h3>

<p>Untuk 1 footplat 80 x 80 cm dengan besi D12:</p>
<ul>
    <li>Besi arah X: 8 batang x 0.8m = 6.4 m</li>
    <li>Besi arah Y: 8 batang x 0.8m = 6.4 m</li>
    <li>Total per footplat: 12.8 m besi D12</li>
    <li>Total 12 footplat: 12 x 12.8 = <strong>153.6 m besi D12</strong></li>
</ul>

<blockquote>
    <p>Tips: Tambahkan 10% untuk waste dan sambungan. Jadi total = 153.6 x 1.1 = 169 m atau sekitar <strong>15 batang besi D12</strong> (1 batang = 12m)</p>
</blockquote>

<h3>Langkah 3: Hitung Kebutuhan Besi Sloof</h3>

<p>Keliling bangunan: (8 + 10) x 2 = 36 m, ditambah sloof tengah ± 16 m = <strong>52 m total</strong></p>

<p>Untuk sloof 20 x 30 cm dengan 4 D12:</p>
<ul>
    <li>Besi utama D12: 52 m x 4 = 208 m</li>
    <li>Tambah 10%: 208 x 1.1 = 229 m = <strong>20 batang D12</strong></li>
</ul>

<p>Besi sengkang Ø8 jarak 15 cm:</p>
<ul>
    <li>Jumlah sengkang: 52 ÷ 0.15 = 347 buah</li>
    <li>Keliling sengkang: (20 + 30) x 2 + 10 (kait) = 110 cm = 1.1 m</li>
    <li>Total: 347 x 1.1 = 382 m = <strong>32 batang Ø8</strong></li>
</ul>

<h3>Langkah 4: Hitung Kebutuhan Besi Kolom Pondasi</h3>

<p>Tinggi kolom pondasi: ± 40 cm (di atas sloof). Untuk 12 kolom dengan 4 D12:</p>
<ul>
    <li>Besi utama: 12 x 0.4 x 4 = 19.2 m, tambah tekukan = 25 m = <strong>3 batang D12</strong></li>
    <li>Sengkang Ø8: minimal 3 per kolom = 36 buah x 1 m = 36 m = <strong>3 batang Ø8</strong></li>
</ul>

<h2>Rekapitulasi Total Kebutuhan</h2>

<table>
    <thead>
        <tr>
            <th>Komponen</th>
            <th>Besi D12</th>
            <th>Besi Ø8</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Footplat</td>
            <td>15 batang</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Sloof</td>
            <td>20 batang</td>
            <td>32 batang</td>
        </tr>
        <tr>
            <td>Kolom Pondasi</td>
            <td>3 batang</td>
            <td>3 batang</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>38 batang</strong></td>
            <td><strong>35 batang</strong></td>
        </tr>
    </tbody>
</table>

<h2>Tips Menghemat Biaya</h2>

<ol>
    <li><strong>Hitung ulang</strong> - Lakukan perhitungan 2-3 kali untuk menghindari kesalahan</li>
    <li><strong>Optimalkan pemotongan</strong> - Rencanakan pemotongan untuk meminimalkan sisa</li>
    <li><strong>Beli dalam volume</strong> - Negosiasikan diskon untuk pembelian besar</li>
    <li><strong>Pilih supplier terpercaya</strong> - Pastikan berat aktual sesuai standar</li>
    <li><strong>Gunakan wiremesh</strong> - Untuk lantai, wiremesh lebih efisien</li>
</ol>

<h2>Kesimpulan</h2>

<p>Perhitungan kebutuhan besi membutuhkan ketelitian dan pemahaman struktur. Untuk proyek rumah Anda, sebaiknya konsultasikan dengan insinyur sipil untuk mendapatkan spesifikasi yang tepat sesuai kondisi tanah dan desain bangunan. Hubungi tim BMG Steel untuk konsultasi gratis dan penawaran harga terbaik.</p>
HTML;
    }

    private function getArticle3ContentEN(): string
    {
        return <<<HTML
<p>Calculating steel requirements for foundations is a crucial step in planning house construction. Accurate calculations will save costs and ensure a solid structure. This tutorial will guide you step by step in calculating steel requirements for a 2-story house foundation.</p>

<h2>Understanding Foundation Components</h2>

<p>Before calculating, first understand the main components of a 2-story house foundation:</p>

<ol>
    <li><strong>Footplate Foundation</strong> - The part that directly contacts the ground</li>
    <li><strong>Tie Beam (Sloof)</strong> - Foundation tie beam above the footplate</li>
    <li><strong>Foundation Column</strong> - Load distributor from upper structure to footplate</li>
</ol>

<h2>Standard Specifications for 2-Story Houses</h2>

<p>For a 2-story house with standard structure, use the following specifications:</p>

<table>
    <thead>
        <tr>
            <th>Component</th>
            <th>Size</th>
            <th>Main Rebar</th>
            <th>Stirrups</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Footplate</td>
            <td>80 x 80 x 25 cm</td>
            <td>D12 - 8 bars/direction</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Tie Beam</td>
            <td>20 x 30 cm</td>
            <td>D12 - 4 bars</td>
            <td>Ø8 - 15 cm spacing</td>
        </tr>
        <tr>
            <td>Column</td>
            <td>25 x 25 cm</td>
            <td>D12 - 4 bars</td>
            <td>Ø8 - 15 cm spacing</td>
        </tr>
    </tbody>
</table>

<h2>Calculation Steps</h2>

<h3>Step 1: Count Foundation Points</h3>

<p>For example house 8m x 10m with 4m column spacing:</p>
<ul>
    <li>Length direction: (10 ÷ 4) + 1 = 3.5, rounded = 4 points</li>
    <li>Width direction: (8 ÷ 4) + 1 = 3 points</li>
    <li>Total points: 4 x 3 = <strong>12 footplate points</strong></li>
</ul>

<h3>Step 2: Calculate Footplate Steel Requirements</h3>

<p>For 1 footplate 80 x 80 cm with D12 rebar:</p>
<ul>
    <li>X direction rebar: 8 bars x 0.8m = 6.4 m</li>
    <li>Y direction rebar: 8 bars x 0.8m = 6.4 m</li>
    <li>Total per footplate: 12.8 m D12 rebar</li>
    <li>Total 12 footplates: 12 x 12.8 = <strong>153.6 m D12 rebar</strong></li>
</ul>

<blockquote>
    <p>Tip: Add 10% for waste and splices. So total = 153.6 x 1.1 = 169 m or about <strong>15 bars of D12</strong> (1 bar = 12m)</p>
</blockquote>

<h3>Step 3: Calculate Tie Beam Steel Requirements</h3>

<p>Building perimeter: (8 + 10) x 2 = 36 m, plus middle tie beams ± 16 m = <strong>52 m total</strong></p>

<p>For tie beam 20 x 30 cm with 4 D12:</p>
<ul>
    <li>Main D12 rebar: 52 m x 4 = 208 m</li>
    <li>Add 10%: 208 x 1.1 = 229 m = <strong>20 bars D12</strong></li>
</ul>

<p>Ø8 stirrups at 15 cm spacing:</p>
<ul>
    <li>Number of stirrups: 52 ÷ 0.15 = 347 pieces</li>
    <li>Stirrup perimeter: (20 + 30) x 2 + 10 (hooks) = 110 cm = 1.1 m</li>
    <li>Total: 347 x 1.1 = 382 m = <strong>32 bars Ø8</strong></li>
</ul>

<h3>Step 4: Calculate Foundation Column Steel Requirements</h3>

<p>Foundation column height: ± 40 cm (above tie beam). For 12 columns with 4 D12:</p>
<ul>
    <li>Main rebar: 12 x 0.4 x 4 = 19.2 m, plus bends = 25 m = <strong>3 bars D12</strong></li>
    <li>Ø8 stirrups: minimum 3 per column = 36 pieces x 1 m = 36 m = <strong>3 bars Ø8</strong></li>
</ul>

<h2>Total Requirements Summary</h2>

<table>
    <thead>
        <tr>
            <th>Component</th>
            <th>D12 Rebar</th>
            <th>Ø8 Rebar</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Footplate</td>
            <td>15 bars</td>
            <td>-</td>
        </tr>
        <tr>
            <td>Tie Beam</td>
            <td>20 bars</td>
            <td>32 bars</td>
        </tr>
        <tr>
            <td>Foundation Column</td>
            <td>3 bars</td>
            <td>3 bars</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td><strong>38 bars</strong></td>
            <td><strong>35 bars</strong></td>
        </tr>
    </tbody>
</table>

<h2>Cost Saving Tips</h2>

<ol>
    <li><strong>Recalculate</strong> - Do calculations 2-3 times to avoid errors</li>
    <li><strong>Optimize cutting</strong> - Plan cuts to minimize waste</li>
    <li><strong>Buy in volume</strong> - Negotiate discounts for large purchases</li>
    <li><strong>Choose trusted suppliers</strong> - Ensure actual weight meets standards</li>
    <li><strong>Use wiremesh</strong> - For floors, wiremesh is more efficient</li>
</ol>

<h2>Conclusion</h2>

<p>Calculating steel requirements requires precision and structural understanding. For your house project, it's advisable to consult with a civil engineer to get the right specifications according to soil conditions and building design. Contact the BMG Steel team for free consultation and the best price quotes.</p>
HTML;
    }
}
