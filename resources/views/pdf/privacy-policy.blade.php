<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>{{ app()->getLocale() === 'en' ? 'Privacy Policy' : 'Kebijakan Privasi' }} - {{ $companyName }}</title>
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
        <h1>{{ app()->getLocale() === 'en' ? 'Privacy Policy' : 'Kebijakan Privasi' }}</h1>
        <p class="company">{{ $companyName }}</p>
        <p class="date">{{ app()->getLocale() === 'en' ? 'Last Updated' : 'Terakhir Diperbarui' }}: {{ now()->format('d F Y') }}</p>
    </div>

    @if(app()->getLocale() === 'en')
        {{-- English Content --}}
        <h2><span class="number">1</span> Introduction</h2>
        <p>PT. Berkah Mandiri Globalindo ("BMG", "we", "us") values the privacy of our website visitors. This privacy policy explains how we collect, use, and protect your personal information.</p>

        <h2><span class="number">2</span> Information We Collect</h2>
        <p>We may collect the following information:</p>
        <ul>
            <li>Name and contact information (email, phone number, address)</li>
            <li>Company name and business information</li>
            <li>Product inquiry details and preferences</li>
            <li>Website usage information and cookies</li>
        </ul>

        <h2><span class="number">3</span> How We Use Information</h2>
        <p>We use the collected information for:</p>
        <ul>
            <li>Responding to product inquiries and quote requests</li>
            <li>Providing customer service and support</li>
            <li>Sending relevant product information and updates</li>
            <li>Improving our website and services</li>
            <li>Complying with legal obligations</li>
        </ul>

        <h2><span class="number">4</span> Data Security</h2>
        <p>We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction. This includes:</p>
        <ul>
            <li>SSL encryption for data transmission</li>
            <li>Secure server infrastructure</li>
            <li>Limited employee access to personal data</li>
            <li>Regular security assessments</li>
        </ul>

        <h2><span class="number">5</span> Cookies</h2>
        <p>Our website uses cookies to enhance your browsing experience. Cookies are small files stored on your device that help us:</p>
        <ul>
            <li>Remember your preferences</li>
            <li>Analyze website traffic and usage patterns</li>
            <li>Improve website functionality</li>
        </ul>
        <p>You can control cookie settings through your browser preferences.</p>

        <h2><span class="number">6</span> Third Party Sharing</h2>
        <p>We do not sell, trade, or rent your personal information to third parties. We may share information with:</p>
        <ul>
            <li>Service providers who assist our business operations</li>
            <li>Legal authorities when required by law</li>
            <li>Business partners with your consent</li>
        </ul>

        <h2><span class="number">7</span> Your Rights</h2>
        <p>You have the right to:</p>
        <ul>
            <li>Access your personal data we hold</li>
            <li>Request correction of inaccurate data</li>
            <li>Request deletion of your data</li>
            <li>Opt-out of marketing communications</li>
            <li>Withdraw consent at any time</li>
        </ul>

        <h2><span class="number">8</span> Policy Changes</h2>
        <p>We may update this privacy policy from time to time. Any changes will be posted on this page with an updated revision date. We encourage you to review this policy periodically.</p>

        <h2><span class="number">9</span> Contact Us</h2>
        <p>If you have questions about this privacy policy, please contact us:</p>
        <div class="contact-box">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Phone:</strong> {{ $phone }}</p>
            <p><strong>Address:</strong> {{ $address }}</p>
        </div>

    @else
        {{-- Indonesian Content --}}
        <h2><span class="number">1</span> Pendahuluan</h2>
        <p>PT. Berkah Mandiri Globalindo ("BMG", "kami", "kita") menghargai privasi pengunjung website kami. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>

        <h2><span class="number">2</span> Informasi yang Kami Kumpulkan</h2>
        <p>Kami dapat mengumpulkan informasi berikut:</p>
        <ul>
            <li>Nama dan informasi kontak (email, nomor telepon, alamat)</li>
            <li>Nama perusahaan dan informasi bisnis</li>
            <li>Detail pertanyaan produk dan preferensi</li>
            <li>Informasi penggunaan website dan cookies</li>
        </ul>

        <h2><span class="number">3</span> Penggunaan Informasi</h2>
        <p>Kami menggunakan informasi yang dikumpulkan untuk:</p>
        <ul>
            <li>Menanggapi pertanyaan produk dan permintaan penawaran</li>
            <li>Menyediakan layanan dan dukungan pelanggan</li>
            <li>Mengirimkan informasi produk dan pembaruan yang relevan</li>
            <li>Meningkatkan website dan layanan kami</li>
            <li>Memenuhi kewajiban hukum</li>
        </ul>

        <h2><span class="number">4</span> Keamanan Data</h2>
        <p>Kami menerapkan langkah-langkah keamanan yang tepat untuk melindungi informasi pribadi Anda dari akses, perubahan, pengungkapan, atau penghancuran yang tidak sah. Ini termasuk:</p>
        <ul>
            <li>Enkripsi SSL untuk transmisi data</li>
            <li>Infrastruktur server yang aman</li>
            <li>Pembatasan akses karyawan ke data pribadi</li>
            <li>Penilaian keamanan berkala</li>
        </ul>

        <h2><span class="number">5</span> Cookies</h2>
        <p>Website kami menggunakan cookies untuk meningkatkan pengalaman browsing Anda. Cookies adalah file kecil yang disimpan di perangkat Anda yang membantu kami:</p>
        <ul>
            <li>Mengingat preferensi Anda</li>
            <li>Menganalisis lalu lintas dan pola penggunaan website</li>
            <li>Meningkatkan fungsionalitas website</li>
        </ul>
        <p>Anda dapat mengontrol pengaturan cookie melalui preferensi browser Anda.</p>

        <h2><span class="number">6</span> Berbagi dengan Pihak Ketiga</h2>
        <p>Kami tidak menjual, memperdagangkan, atau menyewakan informasi pribadi Anda kepada pihak ketiga. Kami dapat membagikan informasi dengan:</p>
        <ul>
            <li>Penyedia layanan yang membantu operasi bisnis kami</li>
            <li>Otoritas hukum bila diwajibkan oleh hukum</li>
            <li>Mitra bisnis dengan persetujuan Anda</li>
        </ul>

        <h2><span class="number">7</span> Hak Anda</h2>
        <p>Anda berhak untuk:</p>
        <ul>
            <li>Mengakses data pribadi yang kami simpan</li>
            <li>Meminta koreksi data yang tidak akurat</li>
            <li>Meminta penghapusan data Anda</li>
            <li>Menolak komunikasi pemasaran</li>
            <li>Menarik persetujuan kapan saja</li>
        </ul>

        <h2><span class="number">8</span> Perubahan Kebijakan</h2>
        <p>Kami dapat memperbarui kebijakan privasi ini dari waktu ke waktu. Setiap perubahan akan diposting di halaman ini dengan tanggal revisi yang diperbarui. Kami menyarankan Anda untuk meninjau kebijakan ini secara berkala.</p>

        <h2><span class="number">9</span> Hubungi Kami</h2>
        <p>Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami:</p>
        <div class="contact-box">
            <p><strong>Email:</strong> {{ $email }}</p>
            <p><strong>Telepon:</strong> {{ $phone }}</p>
            <p><strong>Alamat:</strong> {{ $address }}</p>
        </div>
    @endif

    <div class="footer">
        {{ $companyName }} | {{ app()->getLocale() === 'en' ? 'Privacy Policy' : 'Kebijakan Privasi' }} | {{ now()->format('Y') }}
    </div>
</body>
</html>
