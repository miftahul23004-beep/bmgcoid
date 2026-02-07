# Standar Development Web — SEO, Performance & Keamanan

> Panduan teknis lengkap untuk membangun website Laravel yang optimal dari sisi SEO, performa, dan keamanan. Dokumen ini disusun berdasarkan pengalaman audit dan perbaikan nyata pada project production.

---

## Daftar Isi

1. [Arsitektur Template](#1-arsitektur-template)
2. [Pengelolaan Meta Tag SEO](#2-pengelolaan-meta-tag-seo)
3. [Penanganan URL](#3-penanganan-url)
4. [Pengelolaan Konten](#4-pengelolaan-konten)
5. [Optimasi Gambar](#5-optimasi-gambar)
6. [Pengelolaan Link](#6-pengelolaan-link)
7. [Internasionalisasi (i18n)](#7-internasionalisasi-i18n)
8. [Sitemap & Indexing](#8-sitemap--indexing)
9. [Performance — Frontend](#9-performance--frontend)
10. [Performance — Backend](#10-performance--backend)
11. [Performance — Server & Caching](#11-performance--server--caching)
12. [Keamanan — HTTP Headers](#12-keamanan--http-headers)
13. [Keamanan — Aplikasi](#13-keamanan--aplikasi)
14. [Keamanan — Database](#14-keamanan--database)
15. [Keamanan — File & Server](#15-keamanan--file--server)
16. [Keamanan — Dependency](#16-keamanan--dependency)
17. [Deployment & Monitoring](#17-deployment--monitoring)
18. [Checklist Rilis Production](#18-checklist-rilis-production)
19. [Quick Reference Card](#19-quick-reference-card)

---

## 1. Arsitektur Template

### Prinsip Utama

Layout (`app.blade.php`) bertanggung jawab mengelola semua tag SEO secara terpusat. Child view hanya menyuplai data melalui `@section` dan `@push`.

### Struktur Layout

```php
{{-- resources/views/layouts/app.blade.php --}}

@php
    // Ambil title dan description dari child view, fallback ke default
    $pageTitle = html_entity_decode(
        trim($__env->yieldContent('title')) ?: ($metaTitle ?? config('seo.defaults.title')),
        ENT_QUOTES, 'UTF-8'
    );
    $pageDescription = html_entity_decode(
        trim($__env->yieldContent('meta_description')) ?: ($metaDescription ?? config('seo.defaults.description')),
        ENT_QUOTES, 'UTF-8'
    );
@endphp

{{-- Tag SEO utama — dikelola HANYA di sini --}}
<title>{{ $pageTitle }}</title>
<meta name="description" content="{{ $pageDescription }}">
<link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

{{-- Open Graph — otomatis dari variabel yang sama --}}
<meta property="og:title" content="{{ $pageTitle }}">
<meta property="og:description" content="{{ $pageDescription }}">
<meta property="og:url" content="{{ $canonicalUrl ?? url()->current() }}">

{{-- Twitter Card — otomatis dari variabel yang sama --}}
<meta name="twitter:title" content="{{ $pageTitle }}">
<meta name="twitter:description" content="{{ $pageDescription }}">

{{-- Stack untuk data tambahan dari child view --}}
@stack('meta')
```

### Struktur Child View

```php
{{-- resources/views/pages/products/show.blade.php --}}
@extends('layouts.app')

{{-- 1. Title unik --}}
@section('title', $product->name . ' - ' . config('app.name'))

{{-- 2. Description unik --}}
@section('meta_description', Str::limit(strip_tags($product->description), 155))

{{-- 3. Canonical URL (jika berbeda dari url()->current()) --}}
@php
    $canonicalUrl = route('products.show', $product->slug);
@endphp

{{-- 4. Data tambahan yang layout tidak handle --}}
@push('meta')
    <meta property="og:type" content="product">
    <meta property="og:image" content="{{ asset('storage/' . $product->image) }}">
@endpush

@section('content')
    {{-- Konten halaman --}}
@endsection
```

### Aturan

| Boleh | Tidak Boleh |
|-------|-------------|
| `@section('title', ...)` | `@push('meta')` berisi `<title>` |
| `@section('meta_description', ...)` | `@push('meta')` berisi `<meta name="description">` |
| `@php $canonicalUrl = ... @endphp` | `@push('meta')` berisi `<link rel="canonical">` |
| `@push('meta')` untuk og:image, og:type | `@push('meta')` berisi `og:title`, `og:description` |
| `@push('meta')` | `@section('meta')` (tidak terbaca oleh `@stack`) |

**Alasan:** Layout sudah output tag-tag utama. Jika child view juga output tag yang sama via `@push('meta')`, hasilnya duplikat di HTML.

---

## 2. Pengelolaan Meta Tag SEO

### Title Tag

```
Format  : {Nama Halaman} - {Brand}
Panjang : 50-60 karakter
Contoh  : Besi WF - PT. Berkah Mandiri Globalindo
```

Setiap halaman **wajib** punya title unik. Title diambil dari data database (nama produk, artikel, kategori), sehingga otomatis berbeda untuk setiap konten baru.

```php
// Halaman statis — hardcode terjemahan
@section('title', __('About Us') . ' - ' . config('app.name'))

// Halaman dinamis — dari database
@section('title', $product->name . ' - ' . config('app.name'))

// Halaman dengan pagination
@section('title', __('Articles') . ($page > 1 ? ' - Hal ' . $page : '') . ' - ' . config('app.name'))
```

### Meta Description

```
Panjang : 120-155 karakter
Isi     : Deskripsi ringkas yang mengandung keyword utama
```

```php
// Statis
@section('meta_description', __('Pelajari tentang BMG - mitra terpercaya...'))

// Dinamis — potong dari konten
@section('meta_description', Str::limit(strip_tags($article->content), 155))
```

### Canonical URL

**Tepat 1 per halaman.** Dikelola di layout, child view cukup set variabel `$canonicalUrl`.

```php
// Child view
@php
    $canonicalUrl = route('products.show', $product->slug);
@endphp

// Jika tidak diset, layout otomatis pakai url()->current()
```

### Open Graph & Twitter Card

Layout menangani `og:title`, `og:description`, `og:url`, `twitter:title`, `twitter:description` secara otomatis dari `$pageTitle` dan `$pageDescription`.

Child view hanya push **data yang unik per tipe konten**:

```php
// Produk
@push('meta')
    <meta property="og:type" content="product">
    <meta property="og:image" content="{{ asset('storage/' . $product->image) }}">
@endpush

// Artikel
@push('meta')
    <meta property="og:type" content="article">
    <meta property="article:published_time" content="{{ $article->published_at->toISOString() }}">
    <meta property="og:image" content="{{ asset('storage/' . $article->image) }}">
@endpush

// Halaman umum — tidak perlu push apa-apa
```

---

## 3. Penanganan URL

### URL Bersih (Clean URL)

```
✅ /products/category/baja-struktural
❌ /products?category=baja-struktural

✅ /articles/judul-artikel
❌ /articles?id=123
```

**Aturan:** Konten permanen menggunakan path segment, bukan query parameter. Query parameter hanya untuk filter sementara (search, sort, pagination).

### Redirect

```
HTTP  → HTTPS    : 301 (di .htaccess)
WWW   → non-WWW  : 301 (di .htaccess)
Slash ganda → fix : 301 (di .htaccess, // menjadi /)
```

```apache
# .htaccess
# HTTPS
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]

# non-WWW
RewriteCond %{HTTP_HOST} ^www\.example\.com$ [NC]
RewriteRule ^(.*)$ https://example.com/$1 [L,R=301]

# Double slash
RewriteCond %{THE_REQUEST} \s([^\s]*?)//+([^\s]*)\s [NC]
RewriteRule .* %1/%2 [R=301,L,NE]
```

### Hreflang

Gunakan **hanya jika** URL berbeda per bahasa:

```
✅ Gunakan hreflang jika:
   /en/about  ←→  /id/about  (URL berbeda)

❌ Jangan gunakan jika:
   /about (bahasa berubah via session/cookie, URL sama)
```

---

## 4. Pengelolaan Konten

### HTML Escaping — Satu Kali Saja

Blade `{{ }}` sudah melakukan HTML escaping otomatis. Jangan escape dua kali.

```php
// ✅ BENAR
@php
    $name = $product->name;  // Raw dari database
@endphp
{{ $name }}
// Output HTML: Baja &amp; Profil
// Browser render: Baja & Profil

// ❌ SALAH — Double escape
@php
    $name = e($product->name);  // e() escape pertama
@endphp
{{ $name }}                     // {{ }} escape kedua
// Output HTML: Baja &amp;amp; Profil
// Browser render: Baja &amp; Profil ← RUSAK
```

**Aturan:**
- `{{ $var }}` → sudah aman, jangan tambah `e()`
- `{!! $var !!}` → raw output, hanya untuk HTML yang sudah di-sanitize
- `e()` → hanya dipakai di controller, JSON response, atau string concatenation di PHP
- Data di database harus **raw text** (karakter `&`, bukan `&amp;`)

### Heading H1

```
✅ Setiap halaman punya TEPAT 1 tag <h1>
✅ H1 unik per halaman (dari nama produk/artikel/kategori)
✅ H1 mengandung keyword utama halaman
❌ Tidak ada halaman tanpa H1
❌ Tidak ada halaman dengan 2+ H1
```

### Structured Data (JSON-LD)

```php
@push('schema')
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush
```

Tipe yang umum: `Product`, `Article`, `BreadcrumbList`, `Organization`, `LocalBusiness`.

---

## 5. Optimasi Gambar

### Atribut Wajib

```html
<!-- ✅ BENAR: Lengkap -->
<img src="{{ asset('storage/' . $product->image) }}"
     alt="{{ $product->name }}"
     width="400"
     height="300"
     loading="lazy"
     class="w-full h-auto">

<!-- ❌ SALAH: Tanpa alt, tanpa dimensi -->
<img src="{{ asset('storage/' . $product->image) }}" class="w-full">
```

### Aturan Alt Text

| Konteks | alt |
|---------|-----|
| Gambar produk | `alt="{{ $product->name }}"` |
| Icon kategori dalam `<a>` | `alt="{{ $category->name }}"` |
| Logo dalam `<a>` | `alt="{{ config('app.name') }} Logo"` |
| Gambar dekoratif (garis, ornamen) | `alt=""` (kosong, bukan tanpa atribut) |
| Avatar/foto tim | `alt="{{ $member->name }}"` |

### Loading Strategy

| Posisi | Atribut | Teknik |
|--------|---------|--------|
| Hero/LCP (above fold) | `loading="eager"` | `@push('preload')` + `<link rel="preload" as="image">` |
| Di bawah fold | `loading="lazy"` | Browser native lazy loading |
| Background CSS | — | Gunakan `<img>` jika penting untuk SEO |

### Format & Kompresi

```
Prioritas format: WebP > JPEG > PNG
Quality WebP    : 75-85%
Ukuran maksimal : 200KB untuk thumbnail, 500KB untuk hero
Dimensi          : Sediakan 2x untuk retina (srcset)
```

---

## 6. Pengelolaan Link

### Internal Link — Langsung ke URL Final

```php
// ✅ BENAR: Langsung ke endpoint (status 200)
<a href="{{ route('products.category', $category->slug) }}">
    {{ $category->name }}
</a>

// ❌ SALAH: Ke URL yang redirect 301
<a href="{{ route('products.index', ['category' => $category->slug]) }}">
    {{ $category->name }}
</a>
// /products?category=slug → 301 → /products/category/slug
```

**Test:** Klik link, jika address bar berubah (URL berbeda dari href) = link salah.

### Atribut href

```php
// ✅ BENAR
<a href="{{ route('contact') }}">Hubungi</a>
<button type="button" @click="handleClick">Action</button>

// ⚠️ Jika harus <a> tanpa navigasi
<a href="javascript:void(0)" @click="handleClick">Action</a>

// ❌ SALAH
<a href="#">Klik</a>       // Scroll ke atas + SEO warning
<a href="">Klik</a>        // Reload halaman + SEO warning
```

**Aturan:** Untuk aksi tanpa navigasi, gunakan `<button>` bukan `<a>`. Tidak boleh ada `href="#"` atau `href=""`.

### External Link

```html
<a href="https://external.com" target="_blank" rel="noopener noreferrer">
    External Site
</a>
```

Selalu tambah `rel="noopener noreferrer"` pada link external dengan `target="_blank"`.

### Jumlah Internal Link

Setiap halaman sebaiknya punya **minimal 3 internal link unik** di area konten (di luar navbar/footer).

---

## 7. Internasionalisasi (i18n)

### Translation Key

```php
// ✅ BENAR: Kalimat bahasa Inggris sebagai key
__('Our Team')          // id.json: "Our Team": "Tim Kami"
__('Contact Us')        // id.json: "Contact Us": "Hubungi Kami"
__('Product Catalog')   // id.json: "Product Catalog": "Katalog Produk"

// ❌ SALAH: Dot notation yang tidak terdaftar
__('nav.team')          // Output literal: "nav.team"
__('about.certificates_meta_description')  // Output literal jika key tidak ada
```

**Aturan:**
- Gunakan kalimat bahasa Inggris utuh sebagai key
- Semua key **wajib** ada padanannya di `lang/id.json`
- Test: ganti locale ke `id`, pastikan semua teks berubah
- Jangan gunakan dot notation kecuali dengan file PHP lang (bukan JSON)

### Locale Session-Based

Jika bahasa diubah via session (URL tetap sama):
- **Jangan** gunakan hreflang tag
- Route `/language/{locale}` harus return `noindex` header
- Masukkan `/language/` ke `robots.txt` → `Disallow`

---

## 8. Sitemap & Indexing

### Sitemap XML

```php
// ✅ BENAR: Clean URL, status 200
<url>
    <loc>https://example.com/products/category/baja-struktural</loc>
    <lastmod>2026-02-07T05:42:04+07:00</lastmod>
    <changefreq>weekly</changefreq>
    <priority>0.7</priority>
</url>

// ❌ SALAH: Query parameter (bisa redirect)
<url>
    <loc>https://example.com/products?category=baja-struktural</loc>
</url>
```

**Aturan sitemap:**
- Hanya berisi URL dengan status **200**
- Tidak ada URL yang redirect (301/302)
- Tidak ada URL yang di-`noindex`
- Gunakan `rtrim(config('app.url'), '/') . '/path'` untuk construct URL
- Cache sitemap ke file (hemat resource, jangan generate tiap request)
- Update otomatis saat konten baru ditambah

### Robots.txt

```
User-agent: *
Allow: /

# Halaman utilitas
Disallow: /language/
Disallow: /go/

# Halaman filter/search
Disallow: /products?search=
Disallow: /products?sort=

Sitemap: https://example.com/sitemap.xml
```

### Noindex Strategy

| Halaman | Robots |
|---------|--------|
| Konten utama (produk, artikel) | `index, follow` (default) |
| Pagination halaman 2+ | `noindex, follow` |
| Search results | `noindex, follow` |
| Filter/sort results | `noindex, follow` |
| Language redirect | `noindex, nofollow` + X-Robots-Tag header |
| Marketplace redirect | `noindex, nofollow` + X-Robots-Tag header |

```php
// Controller untuk redirect utilitas
return redirect()->back()
    ->header('X-Robots-Tag', 'noindex, nofollow');
```

---

## 9. Performance — Frontend

### Asset Loading

```
CSS & JS    : Minified + hashed filename (via Vite build)
Compression : Gzip + Brotli (vite-plugin-compression)
Bundle size : < 200KB total (CSS + JS, sebelum gzip)
```

```bash
# Build command
npm run build
# Menghasilkan file seperti:
# public/build/assets/app-B7x2K9mL.css (hashed, immutable cache)
# public/build/assets/app-D4f8H2nQ.js
```

### Font Loading — Non-Blocking

```html
<!-- Preload stylesheet (bukan file font langsung) -->
<link rel="preload"
      href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
      as="style">

<!-- Load async -->
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
      rel="stylesheet"
      media="print"
      onload="this.media='all'">

<!-- Fallback tanpa JS -->
<noscript>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap"
          rel="stylesheet">
</noscript>
```

**Jangan** langsung preload file `.woff2` — URL-nya berubah setiap update Google Fonts.

### DNS Prefetch & Preconnect

```html
<!-- Untuk domain yang PASTI diakses -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<!-- Untuk domain yang MUNGKIN diakses -->
<link rel="dns-prefetch" href="https://www.googletagmanager.com">
```

### Third-Party Scripts

```html
<!-- Analytics — load async -->
<script async src="https://www.googletagmanager.com/gtag/js?id=GA_ID"></script>

<!-- Chat widget — delay load setelah interaksi user -->
<script>
    document.addEventListener('scroll', function loadChat() {
        // Load Tawk.to / chat widget di sini
        document.removeEventListener('scroll', loadChat);
    }, { once: true });
</script>
```

**Aturan:** Setiap script pihak ketiga menambah 50-200ms. Jika tidak kritis, delay loading-nya.

### Core Web Vitals Target

| Metrik | Target | Artinya |
|--------|--------|---------|
| **LCP** | < 2.5 detik | Elemen terbesar (hero image/teks) cepat tampil |
| **INP** | < 200ms | Halaman responsif saat interaksi |
| **CLS** | < 0.1 | Tidak ada elemen yang bergeser saat loading |
| **FCP** | < 1.8 detik | Konten pertama cepat muncul |
| **TTFB** | < 800ms | Server merespons cepat |

### Pencegahan CLS (Layout Shift)

```html
<!-- Selalu beri dimensi pada gambar -->
<img width="400" height="300" ...>

<!-- Reservasi ruang untuk konten dinamis -->
<div style="min-height: 300px">
    {{-- Konten yang dimuat via AJAX/Livewire --}}
</div>

<!-- Font: gunakan font-display: swap -->
<!-- Sudah otomatis dari Google Fonts &display=swap -->
```

---

## 10. Performance — Backend

### Eloquent — Eager Loading

```php
// ✅ BENAR: Eager loading (1 query untuk relasi)
$products = Product::with(['category', 'productMedia'])->paginate(12);

// ❌ SALAH: N+1 query problem
$products = Product::paginate(12);
// Lalu di blade: $product->category->name → 1 query per produk!
```

### Query Optimization

```php
// ✅ Select hanya kolom yang diperlukan
Product::select('id', 'name', 'slug', 'featured_image', 'category_id')
    ->with('category:id,name,slug')
    ->paginate(12);

// ❌ Select semua kolom
Product::with('category')->paginate(12);
// Termasuk kolom description yang besar dan tidak ditampilkan di list
```

### Caching

```php
// Cache data yang jarang berubah
$categories = Cache::remember('nav_categories', 3600, function () {
    return Category::where('is_active', true)
        ->withCount('products')
        ->get();
});

// Cache sitemap ke file
$sitemapPath = storage_path('app/sitemap/sitemap.xml');
if (file_exists($sitemapPath) && filemtime($sitemapPath) > time() - 86400) {
    return response()->file($sitemapPath, ['Content-Type' => 'application/xml']);
}
// Generate baru jika cache expired...
```

---

## 11. Performance — Server & Caching

### Laravel Cache Commands (Production)

```bash
php artisan config:cache    # Cache konfigurasi (WAJIB)
php artisan route:cache     # Cache routing (WAJIB)
php artisan view:cache      # Cache compiled Blade templates (WAJIB)
php artisan event:cache     # Cache event listeners
```

**Penting:** Jalankan setelah setiap deploy. Jika ada error setelah deploy, clear dulu:
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Browser Caching (.htaccess)

```apache
<IfModule mod_expires.c>
    ExpiresActive On

    # Gambar — cache 1 tahun (jarang berubah)
    ExpiresByType image/webp "access plus 1 year"
    ExpiresByType image/jpeg "access plus 1 year"
    ExpiresByType image/png "access plus 1 year"
    ExpiresByType image/svg+xml "access plus 1 year"

    # CSS & JS — cache 1 tahun (filename di-hash oleh Vite)
    ExpiresByType text/css "access plus 1 year"
    ExpiresByType application/javascript "access plus 1 year"

    # Font — cache 1 tahun
    ExpiresByType font/woff2 "access plus 1 year"

    # HTML — cache pendek
    ExpiresByType text/html "access plus 1 hour"
</IfModule>
```

### Gzip/Brotli Compression

```apache
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/html text/plain text/css
    AddOutputFilterByType DEFLATE application/javascript application/json
    AddOutputFilterByType DEFLATE application/xml text/xml
    AddOutputFilterByType DEFLATE image/svg+xml
    AddOutputFilterByType DEFLATE font/woff font/woff2
</IfModule>
```

### PHP OPcache

```ini
; php.ini (production)
opcache.enable=1
opcache.memory_consumption=128
opcache.max_accelerated_files=10000
opcache.validate_timestamps=0    ; Jangan cek perubahan file (production)
opcache.revalidate_freq=0
```

---

## 12. Keamanan — HTTP Headers

### Security Headers Middleware

```php
// app/Http/Middleware/SecurityHeaders.php
public function handle($request, Closure $next)
{
    $response = $next($request);

    // Hanya untuk response HTML
    if (!str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
        return $response;
    }

    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

    // HSTS — hanya di HTTPS
    if ($request->isSecure()) {
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    // Content Security Policy
    $csp = implode('; ', [
        "default-src 'self'",
        "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.googletagmanager.com https://www.google-analytics.com",
        "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
        "img-src 'self' data: https:",
        "font-src 'self' https://fonts.gstatic.com",
        "frame-src 'self' https://www.youtube.com https://www.google.com",
        "connect-src 'self' https://www.google-analytics.com wss:",
        "object-src 'none'",
        "base-uri 'self'",
        "form-action 'self'",
    ]);
    $response->headers->set('Content-Security-Policy', $csp);

    return $response;
}
```

### Registrasi Middleware (Laravel 11)

```php
// bootstrap/app.php
->withMiddleware(function (Middleware $middleware) {
    $middleware->web(append: [
        \App\Http\Middleware\SecurityHeaders::class,
    ]);
})
```

### Target Skor

| Tool | Target |
|------|--------|
| securityheaders.com | **A+** |
| Mozilla Observatory | **A** |
| SSL Labs | **A+** |

---

## 13. Keamanan — Aplikasi

### CSRF Protection

```php
{{-- Selalu ada di setiap form --}}
<form method="POST" action="{{ route('contact.store') }}">
    @csrf
    ...
</form>
```

### Input Validation

```php
// Selalu validasi di server, jangan hanya client-side
public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string|max:5000',
        'file' => 'nullable|file|mimes:pdf,jpg,png|max:5120', // 5MB
    ]);

    // Gunakan $validated, bukan $request->all()
}
```

### Rate Limiting

```php
// routes/web.php
Route::post('/contact', [ContactController::class, 'store'])
    ->middleware('throttle:5,1');  // 5 request per menit

Route::post('/login', [AuthController::class, 'login'])
    ->middleware('throttle:5,1');
```

### Output Sanitization

```php
// User-generated HTML — sanitize sebelum simpan atau tampilkan
$cleanHtml = HtmlSanitizer::sanitize($userInput);

// Tampilkan dengan raw output (sudah disanitize)
{!! $cleanHtml !!}

// Teks biasa — biarkan Blade escape
{{ $user->name }}
```

### Session Security

```php
// config/session.php
'secure' => true,           // Cookie hanya via HTTPS
'http_only' => true,        // Tidak bisa diakses via JavaScript
'same_site' => 'lax',       // Cegah CSRF cross-site
'lifetime' => 120,          // 2 jam
```

### Error Handling

```env
# .env (PRODUCTION)
APP_ENV=production
APP_DEBUG=false              # WAJIB false! Jangan tampilkan stack trace
APP_KEY=base64:...           # Generate: php artisan key:generate

LOG_CHANNEL=stack
LOG_LEVEL=error              # Hanya log error ke atas
```

---

## 14. Keamanan — Database

### Eloquent ORM (Anti SQL Injection)

```php
// ✅ BENAR: Parameterized query via Eloquent
$product = Product::where('slug', $slug)->first();
$products = Product::whereIn('category_id', $categoryIds)->get();

// ✅ BENAR: Raw query dengan binding
DB::select('SELECT * FROM products WHERE slug = ?', [$slug]);

// ❌ SALAH: String concatenation (SQL injection risk!)
DB::select("SELECT * FROM products WHERE slug = '$slug'");
```

### Kredensial

```env
# .env — JANGAN hardcode di code
DB_HOST=127.0.0.1
DB_DATABASE=nama_db
DB_USERNAME=user_db
DB_PASSWORD=password_db
```

```
✅ .env di .gitignore (tidak masuk repository)
✅ .env tidak bisa diakses via web (.htaccess block)
✅ Setiap environment punya .env sendiri
✅ Database user punya minimal privilege
```

---

## 15. Keamanan — File & Server

### .htaccess — Blokir Akses File Sensitif

```apache
# Blokir akses ke file sensitif
<FilesMatch "\.(env|log|json|lock|md|yml|yaml|xml|bak|sql|sh)$">
    Require all denied
</FilesMatch>

# Blokir akses ke hidden files
<FilesMatch "^\.">
    Require all denied
</FilesMatch>

# Jangan tampilkan daftar direktori
Options -Indexes
```

### File Upload Security

```php
$request->validate([
    'file' => [
        'required',
        'file',
        'mimes:jpg,jpeg,png,webp,pdf',    // Whitelist ekstensi
        'max:5120',                         // Maksimal 5MB
    ],
]);

// Simpan dengan nama random (bukan nama asli user)
$path = $request->file('file')->store('uploads', 'public');
```

### Utility Scripts di Production

```php
// ✅ BENAR: Self-deleting script
<?php
// ... lakukan operasi ...
echo "Selesai\n";
@unlink(__FILE__);  // Hapus file ini setelah selesai
```

**Aturan ketat:**
- Setiap utility/debug script di `public/` **WAJIB self-delete**
- Jangan commit ke Git
- Jangan biarkan lebih dari 5 menit di production
- Idealnya: gunakan route terproteksi, bukan file PHP langsung

### Shared Hosting — Workaround

```
Masalah     : symlink() diblokir oleh hosting
Solusi      : Copy file langsung dari storage/app/public/ ke public/storage/
Alternatif  : Buat storage-serve.php yang membaca file manual dengan MIME detection

Masalah     : Artisan tidak bisa dijalankan via SSH
Solusi      : Buat endpoint PHP sementara yang memanggil Artisan::call()
              Wajib self-delete setelah dipakai
```

---

## 16. Keamanan — Dependency

### Audit Rutin

```bash
# PHP dependencies
composer audit

# JavaScript dependencies
npm audit

# Update
composer update --with-dependencies
npm update
```

### Versi Framework

```json
// composer.json — gunakan versi stabil
"require": {
    "laravel/framework": "^11.0",
    "livewire/livewire": "^3.0",
    "filament/filament": "^3.0"
}
```

Jalankan `composer audit` dan `npm audit` minimal **setiap bulan** atau sebelum release besar.

---

## 17. Deployment & Monitoring

### Alur Deployment

```
1. Develop & test di lokal
2. npm run build (minified + hashed assets)
3. php deploy.php --file=file1,file2 --force (upload via FTP)
4. Clear cache di production (self-deleting script)
5. Verifikasi:
   - Title unik per halaman
   - Canonical 1 per halaman
   - Tidak ada link 301
   - Security headers aktif
   - Website berfungsi normal
```

### Monitoring Tools

| Tool | Fungsi | Frekuensi |
|------|--------|-----------|
| **Google Search Console** | Index status, Core Web Vitals, error | Mingguan |
| **Google PageSpeed Insights** | Skor performance per halaman | Setelah deploy |
| **SecurityHeaders.com** | Skor keamanan HTTP headers | Setelah deploy |
| **UptimeRobot / Hetrix** | Monitoring uptime & SSL | 24/7 otomatis |
| **Laravel Log** | Error monitoring | Harian |
| **composer audit / npm audit** | Vulnerability check | Bulanan |

### Backup

```
Database     : Harian (otomatis via cron/hosting panel)
File website : Sebelum deploy besar
.env         : Simpan terpisah di tempat aman (bukan di repo)
Recovery     : Dokumentasi langkah restore
```

---

## 18. Checklist Rilis Production

### Sebelum Rilis

```
□ APP_DEBUG=false
□ APP_ENV=production
□ Error page custom (404, 500)
□ npm run build berhasil
□ composer audit bersih
□ npm audit bersih
□ Semua halaman punya title unik
□ Semua halaman punya meta description
□ Semua halaman punya tepat 1 canonical
□ Semua gambar punya alt text
□ Tidak ada href="#" atau href=""
□ Tidak ada link internal yang redirect
□ Tidak ada utility script di public/
□ Security headers middleware aktif
□ HTTPS enforced
□ .env tidak bisa diakses via web
□ robots.txt benar
□ sitemap.xml valid dan berisi URL 200 saja
□ SSL certificate valid
```

### Setelah Rilis

```
□ php artisan config:cache
□ php artisan route:cache
□ php artisan view:cache
□ Clear browser cache dan test
□ Cek Google PageSpeed Insights
□ Cek SecurityHeaders.com
□ Cek sitemap bisa diakses
□ Cek robots.txt bisa diakses
□ Submit sitemap ke Google Search Console
□ Monitor error log 24 jam pertama
```

### Saat Menambah Halaman Baru

```
□ @section('title', ...) dengan nama unik dari data
□ @section('meta_description', ...) deskriptif
□ $canonicalUrl diset jika perlu
□ H1 unik dan mengandung keyword
□ Semua gambar punya alt text deskriptif
□ Internal link ke URL final (status 200)
□ Halaman otomatis masuk sitemap jika indexable
□ Noindex jika halaman utilitas/filter/pagination
□ Test di mobile
```

---

## 19. Quick Reference Card

```
┌──────────────────────────────────────────────────────────────┐
│                    DEVELOPMENT STANDARDS                      │
├──────────────────────────────────────────────────────────────┤
│                                                              │
│  SEO                                                         │
│  ─────────────────────────────────────────────────────────── │
│  Title       → @section('title') + layout yieldContent       │
│  Description → @section('meta_description') + layout yield   │
│  Canonical   → 1x di layout, child set $canonicalUrl         │
│  OG/Twitter  → Layout handle, child push extra saja          │
│  Escape      → {{ }} saja, JANGAN e() sebelum {{ }}          │
│  Links       → Langsung ke URL 200, bukan ke URL 301         │
│  Images      → alt="deskriptif" + width + height             │
│  href        → Tidak boleh "#" atau ""                        │
│  i18n        → __('English Key') + id.json                   │
│  Sitemap     → Clean URL, status 200 only, cache ke file     │
│  H1          → Tepat 1 per halaman, unik                     │
│  Hreflang    → Hanya jika URL berbeda per bahasa              │
│  Redirect    → X-Robots-Tag noindex + robots.txt Disallow    │
│                                                              │
│  PERFORMANCE                                                 │
│  ─────────────────────────────────────────────────────────── │
│  Build       → npm run build (minified + hashed)             │
│  Fonts       → Preload stylesheet, async load                │
│  Images      → WebP, lazy load, dimensi eksplisit            │
│  LCP         → Preload hero image                            │
│  Cache       → config:cache + route:cache + view:cache       │
│  Query       → Eager loading with(), select() minimal        │
│  Compress    → Gzip/Brotli di .htaccess                      │
│  Browser     → Cache 1 tahun untuk static assets             │
│  Third-party → Async/defer, delay jika non-kritis            │
│  Target      → LCP<2.5s, INP<200ms, CLS<0.1                 │
│                                                              │
│  KEAMANAN                                                    │
│  ─────────────────────────────────────────────────────────── │
│  Headers     → CSP + HSTS + X-Frame + X-Content-Type         │
│  HTTPS       → Enforced via .htaccess 301 redirect           │
│  CSRF        → @csrf di setiap form                          │
│  Input       → validate() di controller, bukan client-only   │
│  Output      → {{ }} untuk teks, {!! !!} hanya jika sanitize │
│  Debug       → APP_DEBUG=false di production                  │
│  .env        → Di .gitignore + blocked via .htaccess          │
│  SQL         → Eloquent ORM, jangan concatenate query         │
│  Upload      → Whitelist mime, max size, random filename      │
│  Scripts     → Utility di public/ wajib self-delete           │
│  Deps        → composer audit + npm audit bulanan             │
│  Session     → secure, http_only, same_site=lax              │
│                                                              │
│  DEPLOY                                                      │
│  ─────────────────────────────────────────────────────────── │
│  1. npm run build                                            │
│  2. php deploy.php --file=... --force                        │
│  3. Clear cache (self-deleting script)                       │
│  4. Verify: title, canonical, links, headers                 │
│  5. Monitor error log 24 jam                                 │
│                                                              │
└──────────────────────────────────────────────────────────────┘
```

---

> **Dokumen ini adalah living document.** Update setiap kali menemukan pola masalah baru dari audit SEO, performance test, atau security scan. Terakhir diupdate: 7 Februari 2026.
