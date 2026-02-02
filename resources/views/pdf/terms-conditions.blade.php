<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ app()->getLocale() === 'en' ? 'Terms & Conditions' : 'Syarat & Ketentuan' }} - {{ $companyName }}</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
        }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11pt;
            line-height: 1.6;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #1e40af;
        }
        .header h1 {
            color: #1e40af;
            font-size: 24pt;
            margin: 0 0 10px 0;
        }
        .header .company {
            font-size: 14pt;
            color: #666;
            margin: 0;
        }
        .header .date {
            font-size: 10pt;
            color: #888;
            margin-top: 10px;
        }
        h2 {
            color: #1e40af;
            font-size: 14pt;
            margin-top: 25px;
            margin-bottom: 15px;
            padding-bottom: 5px;
            border-bottom: 1px solid #e5e7eb;
        }
        h2 .number {
            display: inline-block;
            width: 28px;
            height: 28px;
            background: #1e40af;
            color: white;
            border-radius: 50%;
            text-align: center;
            line-height: 28px;
            font-size: 12pt;
            margin-right: 10px;
        }
        p {
            margin-bottom: 12px;
            text-align: justify;
        }
        ul {
            margin: 10px 0 15px 0;
            padding-left: 25px;
        }
        li {
            margin-bottom: 8px;
        }
        .contact-box {
            background: #f0f9ff;
            border: 1px solid #bfdbfe;
            border-radius: 8px;
            padding: 20px;
            margin-top: 15px;
        }
        .contact-box p {
            margin: 5px 0;
        }
        .contact-box strong {
            color: #1e40af;
        }
        .highlight-box {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
        .footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 9pt;
            color: #888;
            border-top: 1px solid #e5e7eb;
            padding-top: 10px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ app()->getLocale() === 'en' ? 'Terms & Conditions' : 'Syarat & Ketentuan' }}</h1>
        <p class="company">{{ $companyName }}</p>
        <p class="date">{{ app()->getLocale() === 'en' ? 'Last Updated' : 'Terakhir Diperbarui' }}: {{ now()->format('d F Y') }}</p>
    </div>

    @if(app()->getLocale() === 'en')
        {{-- English Content --}}
        <h2><span class="number">1</span> Acceptance of Terms</h2>
        <p>By accessing and using the PT. Berkah Mandiri Globalindo ("BMG") website, you agree to be bound by these terms and conditions. If you do not agree with any part of these terms, please do not use our website.</p>

        <h2><span class="number">2</span> Product Information</h2>
        <p>We strive to display product information as accurately as possible. However:</p>
        <ul>
            <li>Product images may differ slightly from actual products</li>
            <li>Specifications may change without prior notice</li>
            <li>Prices are subject to change without prior notice</li>
            <li>Product availability is subject to stock conditions</li>
        </ul>

        <h2><span class="number">3</span> Orders and Payments</h2>
        <p>Regarding orders and payments:</p>
        <ul>
            <li>All orders are subject to confirmation and availability</li>
            <li>Prices quoted are valid for the specified period only</li>
            <li>Payment terms will be agreed upon in the sales contract</li>
            <li>We reserve the right to refuse any order</li>
        </ul>

        <h2><span class="number">4</span> Delivery</h2>
        <p>Delivery terms and conditions:</p>
        <ul>
            <li>Delivery times are estimates and not guaranteed</li>
            <li>Delivery costs will be calculated based on location and weight</li>
            <li>Risk transfers upon delivery to the carrier</li>
            <li>Delivery delays due to force majeure are not our responsibility</li>
        </ul>

        <h2><span class="number">5</span> Returns and Warranty</h2>
        <p>Our return and warranty policy:</p>
        <ul>
            <li>Products must be inspected upon delivery</li>
            <li>Claims must be made within 24 hours of delivery</li>
            <li>Returns are subject to product condition and our approval</li>
            <li>Warranty terms vary by product and manufacturer</li>
        </ul>

        <h2><span class="number">6</span> Limitation of Liability</h2>
        <p>To the maximum extent permitted by law:</p>
        <ul>
            <li>We are not liable for indirect or consequential damages</li>
            <li>Our liability is limited to the product purchase price</li>
            <li>We are not responsible for third-party actions</li>
            <li>Force majeure events release us from liability</li>
        </ul>

        <h2><span class="number">7</span> Intellectual Property</h2>
        <p>All content on this website, including but not limited to text, graphics, logos, images, and software, is the property of PT. Berkah Mandiri Globalindo and is protected by Indonesian and international copyright laws.</p>

        <h2><span class="number">8</span> Governing Law</h2>
        <p>These terms and conditions are governed by Indonesian law. Any disputes shall be resolved in the courts of Surabaya, Indonesia.</p>

        <h2><span class="number">9</span> Changes to Terms</h2>
        <p>We reserve the right to modify these terms at any time. Changes will be effective immediately upon posting on this website.</p>

        <h2><span class="number">10</span> Contact</h2>
        <p>For questions about these terms, please contact us:</p>
        <div class="contact-box">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Phone:</strong> {{ $phone }}</p>
            <p><strong>Address:</strong> {{ $address }}</p>
        </div>

    @else
        {{-- Indonesian Content --}}
        <h2><span class="number">1</span> Penerimaan Syarat</h2>
        <p>Dengan mengakses dan menggunakan website PT. Berkah Mandiri Globalindo ("BMG"), Anda setuju untuk terikat dengan syarat dan ketentuan ini. Jika Anda tidak setuju dengan bagian mana pun dari syarat ini, mohon untuk tidak menggunakan website kami.</p>

        <h2><span class="number">2</span> Informasi Produk</h2>
        <p>Kami berusaha menampilkan informasi produk seakurat mungkin. Namun:</p>
        <ul>
            <li>Gambar produk mungkin sedikit berbeda dengan produk aktual</li>
            <li>Spesifikasi dapat berubah tanpa pemberitahuan sebelumnya</li>
            <li>Harga dapat berubah tanpa pemberitahuan sebelumnya</li>
            <li>Ketersediaan produk tergantung kondisi stok</li>
        </ul>

        <h2><span class="number">3</span> Pesanan dan Pembayaran</h2>
        <p>Mengenai pesanan dan pembayaran:</p>
        <ul>
            <li>Semua pesanan tunduk pada konfirmasi dan ketersediaan</li>
            <li>Harga yang dikutip hanya berlaku untuk periode yang ditentukan</li>
            <li>Syarat pembayaran akan disepakati dalam kontrak penjualan</li>
            <li>Kami berhak menolak pesanan apa pun</li>
        </ul>

        <h2><span class="number">4</span> Pengiriman</h2>
        <p>Syarat dan ketentuan pengiriman:</p>
        <ul>
            <li>Waktu pengiriman adalah perkiraan dan tidak dijamin</li>
            <li>Biaya pengiriman akan dihitung berdasarkan lokasi dan berat</li>
            <li>Risiko berpindah saat pengiriman ke kurir</li>
            <li>Keterlambatan pengiriman karena force majeure bukan tanggung jawab kami</li>
        </ul>

        <h2><span class="number">5</span> Pengembalian dan Garansi</h2>
        <p>Kebijakan pengembalian dan garansi kami:</p>
        <ul>
            <li>Produk harus diperiksa saat pengiriman</li>
            <li>Klaim harus dilakukan dalam 24 jam setelah pengiriman</li>
            <li>Pengembalian tunduk pada kondisi produk dan persetujuan kami</li>
            <li>Syarat garansi bervariasi berdasarkan produk dan pabrikan</li>
        </ul>

        <h2><span class="number">6</span> Batasan Tanggung Jawab</h2>
        <p>Sejauh diizinkan oleh hukum:</p>
        <ul>
            <li>Kami tidak bertanggung jawab atas kerugian tidak langsung atau konsekuensial</li>
            <li>Tanggung jawab kami terbatas pada harga pembelian produk</li>
            <li>Kami tidak bertanggung jawab atas tindakan pihak ketiga</li>
            <li>Peristiwa force majeure membebaskan kami dari tanggung jawab</li>
        </ul>

        <h2><span class="number">7</span> Hak Kekayaan Intelektual</h2>
        <p>Semua konten di website ini, termasuk namun tidak terbatas pada teks, grafik, logo, gambar, dan perangkat lunak, adalah milik PT. Berkah Mandiri Globalindo dan dilindungi oleh hukum hak cipta Indonesia dan internasional.</p>

        <h2><span class="number">8</span> Hukum yang Berlaku</h2>
        <p>Syarat dan ketentuan ini diatur oleh hukum Indonesia. Setiap perselisihan akan diselesaikan di pengadilan Surabaya, Indonesia.</p>

        <h2><span class="number">9</span> Perubahan Syarat</h2>
        <p>Kami berhak mengubah syarat ini kapan saja. Perubahan akan berlaku segera setelah diposting di website ini.</p>

        <h2><span class="number">10</span> Kontak</h2>
        <p>Untuk pertanyaan tentang syarat ini, silakan hubungi kami:</p>
        <div class="contact-box">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Telepon:</strong> {{ $phone }}</p>
            <p><strong>Alamat:</strong> {{ $address }}</p>
        </div>
    @endif

    <div class="footer">
        {{ $companyName }} | {{ app()->getLocale() === 'en' ? 'Terms & Conditions' : 'Syarat & Ketentuan' }} | {{ now()->format('Y') }}
    </div>
</body>
</html>
