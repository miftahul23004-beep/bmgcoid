<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Konfirmasi Penerimaan Pesan</title>
    @php
        $companyName = $companyInfo['company_name'] ?? 'PT Berkah Mandiri Global';
        $phone = $companyInfo['phone'] ?? '';
        $whatsapp = $companyInfo['whatsapp'] ?? '';
        $email = $companyInfo['email'] ?? 'info@berkahmandiri.co.id';
        $address = $companyInfo['address'] ?? '';
    @endphp
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1E40AF; color: #fff; padding: 25px 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 8px 0 0; font-size: 13px; opacity: 0.9; }
        .body { padding: 30px; }
        .greeting { font-size: 16px; margin-bottom: 20px; }
        .info-box { background: #f0f9ff; border: 1px solid #bae6fd; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .info-box h3 { margin: 0 0 12px; color: #1E40AF; font-size: 15px; }
        table { width: 100%; border-collapse: collapse; }
        table td { padding: 8px 0; border-bottom: 1px solid #e0f2fe; vertical-align: top; font-size: 14px; }
        table td:first-child { font-weight: bold; color: #666; width: 120px; }
        table tr:last-child td { border-bottom: none; }
        .message-box { background: #f9fafb; border-left: 4px solid #1E40AF; padding: 15px; margin: 15px 0; border-radius: 0 8px 8px 0; font-size: 14px; }
        .note { background: #fffbeb; border: 1px solid #fde68a; border-radius: 8px; padding: 15px; margin: 20px 0; font-size: 13px; color: #92400e; }
        .note strong { color: #78350f; }
        .contact-info { margin: 20px 0; padding: 15px; background: #f9fafb; border-radius: 8px; font-size: 13px; }
        .contact-info p { margin: 5px 0; }
        .footer { background: #f9fafb; padding: 20px 30px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #eee; }
        .footer a { color: #1E40AF; text-decoration: none; }
        .divider { border: none; border-top: 1px solid #eee; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>✅ Pesan Anda Telah Kami Terima</h1>
            <p>{{ $companyName }}</p>
        </div>
        <div class="body">
            <p class="greeting">Yth. <strong>{{ $inquiry->name }}</strong>,</p>

            <p>Terima kasih telah menghubungi <strong>{{ $companyName }}</strong>. Kami ingin mengonfirmasi bahwa pesan Anda telah berhasil kami terima dan sedang dalam proses peninjauan.</p>

            <div class="info-box">
                <h3>📋 Ringkasan Pesan Anda</h3>
                <table>
                    <tr>
                        <td>Nama</td>
                        <td>{{ $inquiry->name }}</td>
                    </tr>
                    <tr>
                        <td>Email</td>
                        <td>{{ $inquiry->email }}</td>
                    </tr>
                    @if($inquiry->company)
                    <tr>
                        <td>Perusahaan</td>
                        <td>{{ $inquiry->company }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td>Subjek</td>
                        <td>{{ $inquiry->subject }}</td>
                    </tr>
                    @if($inquiry->product)
                    <tr>
                        <td>Produk</td>
                        <td>{{ $inquiry->product->name }}</td>
                    </tr>
                    @if($inquiry->quantity)
                    <tr>
                        <td>Jumlah</td>
                        <td>{{ $inquiry->quantity }} {{ $inquiry->unit }}</td>
                    </tr>
                    @endif
                    @endif
                    <tr>
                        <td>Tanggal</td>
                        <td>{{ $inquiry->created_at->format('d M Y, H:i') }} WIB</td>
                    </tr>
                </table>
            </div>

            <p><strong>Pesan Anda:</strong></p>
            <div class="message-box">
                {!! nl2br(e($inquiry->message)) !!}
            </div>

            <div class="note">
                <strong>⏰ Estimasi Waktu Respon:</strong> Tim kami akan meninjau pesan Anda dan merespons dalam waktu <strong>1×24 jam kerja</strong>. Jika pertanyaan Anda bersifat mendesak, silakan hubungi kami langsung melalui telepon.
            </div>

            <hr class="divider">

            <div class="contact-info">
                @if($phone)
                <p><strong>📞 Telepon:</strong> {{ $phone }}</p>
                @endif
                @if($whatsapp)
                <p><strong>💬 WhatsApp:</strong> <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $whatsapp) }}" style="color: #1E40AF;">{{ $whatsapp }}</a></p>
                @endif
                <p><strong>📧 Email:</strong> {{ $email }}</p>
                <p><strong>🌐 Website:</strong> <a href="{{ config('app.url') }}" style="color: #1E40AF;">berkahmandiri.co.id</a></p>
                @if($address)
                <p><strong>📍 Alamat:</strong> {{ $address }}</p>
                @endif
            </div>

            <p style="font-size: 14px; color: #666;">Salam hangat,<br><strong>Tim {{ $companyName }}</strong></p>
        </div>
        <div class="footer">
            <p>Email ini dikirim secara otomatis sebagai konfirmasi penerimaan pesan Anda.<br>Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} <a href="{{ config('app.url') }}">{{ $companyName }}</a>. Semua hak dilindungi.</p>
        </div>
    </div>
</body>
</html>
