<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inquiry Baru</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background: #fff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: #1E40AF; color: #fff; padding: 20px 30px; }
        .header h1 { margin: 0; font-size: 20px; }
        .header p { margin: 5px 0 0; font-size: 13px; opacity: 0.9; }
        .body { padding: 30px; }
        .badge { display: inline-block; padding: 4px 12px; border-radius: 20px; font-size: 12px; font-weight: bold; margin-bottom: 15px; }
        .badge-product { background: #DBEAFE; color: #1E40AF; }
        .badge-contact { background: #FEE2E2; color: #DC2626; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        table td { padding: 10px 0; border-bottom: 1px solid #eee; vertical-align: top; }
        table td:first-child { font-weight: bold; color: #666; width: 130px; }
        .message-box { background: #f9fafb; border-left: 4px solid #1E40AF; padding: 15px; margin: 15px 0; border-radius: 0 8px 8px 0; }
        .footer { background: #f9fafb; padding: 15px 30px; text-align: center; font-size: 12px; color: #999; }
        .btn { display: inline-block; background: #1E40AF; color: #fff !important; padding: 10px 25px; border-radius: 6px; text-decoration: none; font-weight: bold; margin-top: 10px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📩 Inquiry Baru Masuk</h1>
            <p>{{ now()->format('d M Y, H:i') }} WIB</p>
        </div>
        <div class="body">
            @if($inquiry->product_id)
                <span class="badge badge-product">🛒 Penawaran Produk</span>
            @else
                <span class="badge badge-contact">📋 Kontak Umum</span>
            @endif

            <table>
                <tr>
                    <td>Nama</td>
                    <td>{{ $inquiry->name }}</td>
                </tr>
                <tr>
                    <td>Email</td>
                    <td><a href="mailto:{{ $inquiry->email }}">{{ $inquiry->email }}</a></td>
                </tr>
                <tr>
                    <td>Telepon</td>
                    <td><a href="tel:{{ $inquiry->phone }}">{{ $inquiry->phone }}</a></td>
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
                @endif
            </table>

            <p><strong>Pesan:</strong></p>
            <div class="message-box">
                {!! nl2br(e($inquiry->message)) !!}
            </div>

            <center>
                <a href="{{ url('/admin/inquiries') }}" class="btn">Lihat di Admin Panel →</a>
            </center>
        </div>
        <div class="footer">
            Email ini dikirim otomatis dari website {{ config('app.name') }}
        </div>
    </div>
</body>
</html>
