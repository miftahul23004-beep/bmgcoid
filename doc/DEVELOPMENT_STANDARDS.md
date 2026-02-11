# 📋 Development Standards & Architecture Guide
## PT. Berkah Mandiri Globalindo — berkahmandiri.co.id

> **Terakhir diperbarui:** 12 Februari 2026
> **Tujuan dokumen:** Panduan lengkap arsitektur, standar koding, deployment, dan SEO untuk proyek website korporat PT. Berkah Mandiri Globalindo.

---

## Daftar Isi

1. [Ikhtisar Proyek](#1-ikhtisar-proyek)
2. [Tech Stack](#2-tech-stack)
3. [Arsitektur & Pola Desain](#3-arsitektur--pola-desain)
4. [Struktur Direktori](#4-struktur-direktori)
5. [Database & Model](#5-database--model)
6. [Routing & Controller](#6-routing--controller)
7. [Service Layer](#7-service-layer)
8. [Filament Admin Panel](#8-filament-admin-panel)
9. [Frontend & Asset Pipeline](#9-frontend--asset-pipeline)
10. [SEO & Indexability](#10-seo--indexability)
11. [Keamanan (Security)](#11-keamanan-security)
12. [Caching Strategy](#12-caching-strategy)
13. [Internationalization (i18n)](#13-internationalization-i18n)
14. [Media & Image Optimization](#14-media--image-optimization)
15. [Chat & AI Integration](#15-chat--ai-integration)
16. [Email & Notification](#16-email--notification)
17. [Deployment](#17-deployment)
18. [Performance Monitoring](#18-performance-monitoring)
19. [Backup & Recovery](#19-backup--recovery)
20. [Konvensi Penamaan & Coding Style](#20-konvensi-penamaan--coding-style)
21. [Environment Variables](#21-environment-variables)
22. [Troubleshooting](#22-troubleshooting)

---

## 1. Ikhtisar Proyek

| Item | Detail |
|------|--------|
| **Nama** | PT. Berkah Mandiri Globalindo |
| **Domain** | berkahmandiri.co.id |
| **Domain Alias** | berkahmandiriglobalindo.com (redirect) |
| **Bisnis** | Supplier & distributor besi baja — Surabaya, Jawa Timur |
| **Tipe Website** | Company profile + katalog produk + blog + live chat |
| **Bahasa** | Indonesia (primary) + English |
| **Server** | 109.106.253.35 (shared hosting) |
| **CDN/Proxy** | Cloudflare |
| **Admin Email** | info@berkahmandiri.co.id |

---

## 2. Tech Stack

### Backend
| Komponen | Versi | Fungsi |
|----------|-------|--------|
| PHP | ^8.2 | Runtime |
| Laravel | ^12.0 | Framework |
| Filament | ^5.1 | Admin panel |
| Livewire | ^3.0 | Komponen interaktif (chat, form) |
| MySQL/MariaDB | — | Database |

### Frontend
| Komponen | Versi | Fungsi |
|----------|-------|--------|
| Tailwind CSS | ^3.4 | Utility-first CSS |
| Alpine.js | ^3.15 | Reactive JS framework |
| Vite | ^6.0 | Asset bundler |
| Terser | ^5.46 | JS minification |

### Paket Utama (Composer)
| Paket | Fungsi |
|-------|--------|
| `spatie/laravel-translatable` | Multi-bahasa pada model (id/en) |
| `spatie/laravel-sluggable` | Auto-generate slug |
| `spatie/laravel-medialibrary` | Manajemen media file |
| `spatie/laravel-activitylog` | Audit log aktivitas |
| `spatie/laravel-permission` | Role & permission (RBAC) |
| `spatie/laravel-backup` | Database & file backup |
| `spatie/laravel-sitemap` | XML sitemap generation |
| `barryvdh/laravel-dompdf` | PDF generation |
| `barryvdh/laravel-debugbar` | Debug toolbar (dev only) |

### Paket Frontend (NPM)
| Paket | Fungsi |
|-------|--------|
| `alpinejs` | Reactive UI |
| `@alpinejs/collapse` | Collapse/accordion animation |
| `@alpinejs/intersect` | Intersection observer |
| `vite-plugin-compression` | Gzip + Brotli compression |
| `axios` | HTTP client |

---

## 3. Arsitektur & Pola Desain

### Service Layer Pattern
Semua business logic ditempatkan di `app/Services/`, **bukan** di controller. Controller hanya bertugas:
1. Validasi input
2. Memanggil service
3. Return view/response

```
Request → Controller (validate) → Service (business logic) → Model/Cache → View
```

### Observer Pattern
Setiap perubahan pada model penting akan trigger:
- **Cache clearing** — Membersihkan cache terkait
- **Cloudflare purge** — Purge URL yang berubah via CloudflarePurgeService
- **Image optimization** — Auto-convert ke WebP (pada Category, HeroSlide)

Observer terdaftar di: `AppServiceProvider::boot()`

### Singleton Services
Service berikut di-register sebagai singleton di `AppServiceProvider::register()`:
- `SettingService`
- `ProductService`
- `CategoryService`
- `ArticleService`

### View Composer
`LayoutComposer` menyediakan data terkelola-cache ke layout partials (`navbar`, `footer`, `topbar`).

---

## 4. Struktur Direktori

```
├── app/
│   ├── Console/Commands/          # Artisan commands (backup, audit, optimize)
│   ├── Filament/                  # Admin panel (resources, pages, widgets)
│   │   ├── Pages/                 # Custom admin pages (8)
│   │   ├── Resources/             # CRUD resources (15)
│   │   └── Widgets/               # Dashboard widgets (8)
│   ├── Helpers/
│   │   └── HtmlSanitizer.php      # HTML sanitization untuk CMS content
│   ├── Http/
│   │   ├── Controllers/           # 7 controllers
│   │   └── Middleware/            # 4 middleware (Cache, Performance, Security, Locale)
│   ├── Livewire/                  # 3 komponen (Chat, ContactForm, InquiryForm)
│   ├── Mail/                      # 2 mailable (InquiryConfirmation, InquiryNotification)
│   ├── Models/                    # 22 Eloquent models
│   ├── Observers/                 # 4 observers (Article, Category, HeroSlide, Product)
│   ├── Providers/                 # AppServiceProvider + AdminPanelProvider
│   ├── Services/                  # 11 service classes
│   └── View/Composers/           # LayoutComposer
├── config/                        # 20+ config files
├── database/
│   ├── migrations/                # 46 migration files
│   └── seeders/                   # 12 seeder classes
├── doc/                           # Dokumentasi proyek
├── lang/                          # i18n: en.json, id.json
├── public/                        # Web root
│   ├── build/                     # Vite compiled assets
│   ├── images/                    # Static images (logo, og-image, dll)
│   └── robots.txt                 # Crawler directives
├── resources/
│   ├── css/app.css                # Tailwind entry point
│   ├── js/app.js                  # Alpine.js entry point
│   └── views/                     # Blade templates
│       ├── layouts/               # Main layout + partials
│       ├── pages/                 # Page views (home, about, products, articles, dll)
│       ├── components/            # Reusable components + icons
│       ├── livewire/              # Livewire component views
│       ├── emails/                # Email templates
│       └── pdf/                   # PDF templates
├── routes/web.php                 # All web routes
├── deploy.php                     # FTP deployment script
└── vite.config.js                 # Vite build config
```

---

## 5. Database & Model

### Daftar Model (22)

| Model | Trait Utama | Translatable Fields |
|-------|-------------|-------------------|
| **User** | HasRoles, Notifiable | — |
| **Product** | HasSlug, HasTranslations, InteractsWithMedia, LogsActivity, SoftDeletes | name, short_description, description, meta_* |
| **Category** | HasSlug, HasTranslations, InteractsWithMedia, LogsActivity, SoftDeletes | name, description, meta_* |
| **Article** | HasSlug, HasTranslations, InteractsWithMedia, LogsActivity, SoftDeletes | title, excerpt, content, meta_* |
| **Tag** | HasSlug, HasTranslations | name |
| **Variant** | HasTranslations | name |
| **ProductMedia** | HasTranslations | alt_text, caption |
| **ProductDocument** | HasTranslations | title |
| **ProductFaq** | HasTranslations | question, answer |
| **ProductMarketplaceLink** | — | — |
| **HeroSlide** | — (separate _id/_en columns) | — |
| **HomepageSection** | — | — |
| **Client** | — | — |
| **Testimonial** | HasTranslations | content, author_position, project_name |
| **Inquiry** | — | — |
| **ChatSession** | — | — |
| **ChatMessage** | — | — |
| **PageContent** | HasTranslations | content |
| **Setting** | — | — |
| **AuditResult** | — | — |
| **AuditIssue** | — | — |
| **PerformanceLog** | — | — |

### Relasi Utama

```
Category (1) ──── (N) Product
Product  (1) ──── (N) Variant
Product  (1) ──── (N) ProductMedia
Product  (1) ──── (N) ProductDocument
Product  (1) ──── (N) ProductFaq
Product  (1) ──── (N) ProductMarketplaceLink
Product  (1) ──── (N) Inquiry

Article  (N) ──── (N) Tag  (pivot: article_tag)
Article  (N) ──── (1) User (author)

Client   (1) ──── (N) Testimonial

ChatSession (1) ── (N) ChatMessage
```

### Convention
- **Soft deletes**: Product, Category, Article
- **Activity logging**: Product, Category, Article
- **Slug auto-generation**: Product, Category, Article, Tag (via Spatie Sluggable)
- **Translatable**: Semua content model menggunakan JSON columns (id/en)

---

## 6. Routing & Controller

### Route Map

| Method | URI | Controller | Name | SEO |
|--------|-----|------------|------|-----|
| GET | `/` | HomeController@index | `home` | index |
| GET | `/about` | HomeController@about | `about.company` | index |
| GET | `/about/vision-mission` | HomeController@visionMission | `about.vision-mission` | index |
| GET | `/about/team` | HomeController@team | `about.team` | index |
| GET | `/about/certificates` | HomeController@certificates | `about.certificates` | index |
| GET | `/products` | ProductController@index | `products.index` | index* |
| GET | `/products/category/{slug}` | ProductController@category | `products.category` | index* |
| GET | `/products/{slug}` | ProductController@show | `products.show` | index |
| GET | `/articles` | ArticleController@index | `articles.index` | index* |
| GET | `/articles/tag/{slug}` | ArticleController@tag | `articles.tag` | index |
| GET | `/articles/{slug}` | ArticleController@show | `articles.show` | index |
| GET | `/testimonials` | HomeController@testimonials | `testimonials` | index |
| GET | `/contact` | ContactController@index | `contact` | index |
| POST | `/contact` | ContactController@submit | `contact.submit` | — |
| GET | `/quote` | ContactController@quote | `quote` | index |
| POST | `/quote` | ContactController@submitQuote | `quote.submit` | — |
| GET | `/search` | SearchController@index | `search` | noindex |
| GET | `/privacy` | HomeController@privacy | `privacy` | index |
| GET | `/terms` | HomeController@terms | `terms` | index |
| GET | `/sitemap` | HomeController@sitemap | `sitemap` | index |
| GET | `/sitemap.xml` | SitemapController@index | `sitemap.xml` | — |
| GET | `/language/{locale}` | LanguageController@switch | `language.switch` | noindex |
| GET | `/go/{platform}/{productId}` | ProductController@marketplaceRedirect | `marketplace.redirect` | noindex |
| GET | `/privacy/pdf` | HomeController@privacyPdf | `privacy.pdf` | — |
| GET | `/terms/pdf` | HomeController@termsPdf | `terms.pdf` | — |

> **index***: Menjadi `noindex, follow` ketika ada query params (search, sort, page>1). Canonical tetap menunjuk ke URL bersih (tanpa query params).

### Controller Conventions
- Validasi input di controller menggunakan `$request->validate()`
- Slug divalidasi format-nya: `/^[a-z0-9\-]+$/`
- Rate limiting pada POST routes: `throttle:5,1`
- Redirect `?category=slug` → `/products/category/slug` (301) untuk SEO

---

## 7. Service Layer

| Service | Fungsi Utama |
|---------|-------------|
| **SettingService** | CRUD settings per group, cache per-group (1h), helpers: companyInfo, socialLinks, marketplace, seo |
| **ProductService** | Product listing/filter/sort, featured/popular/related, caching (30min), search sanitization |
| **CategoryService** | Navigation tree, breadcrumbs, product counts, caching (1h) |
| **ArticleService** | Article CRUD, tag filtering, related articles, popular tags, caching (30min) |
| **SchemaService** | JSON-LD markup: Organization, LocalBusiness, Product, Article, FAQ, BreadcrumbList |
| **ImageOptimizationService** | WebP conversion, quality tiering, resize, ICO conversion untuk favicon |
| **AiChatService** | AI chatbot (OpenAI/Gemini), knowledge base dari produk/kategori, handover detection |
| **CloudflarePurgeService** | Purge cache Cloudflare by URL atau purge-all |
| **Audit/PerformanceAuditService** | PageSpeed API, internal performance measurement |
| **Audit/SeoAuditService** | Full SEO analysis: meta, headings, images, links, structured data |
| **Audit/SecurityAuditService** | Security headers, SSL, exposure analysis |

### Aturan Service
1. Service **tidak boleh** langsung return view/response
2. Service meng-handle caching internal (caller tidak perlu cache manual)
3. Cache key harus **locale-aware**: `"key_{$locale}"`
4. Service di-inject via constructor DI atau singleton resolution

---

## 8. Filament Admin Panel

### Akses
- **URL**: `/admin`
- **Auth**: Harus implement `FilamentUser` interface
- **Panel Provider**: `AdminPanelProvider`

### Resources (15 CRUD)
| Resource | RelationManagers | Catatan |
|----------|-----------------|---------|
| Articles | — | Status: draft/published |
| AuditResults | — | View-only |
| Categories | — | Hierarchical (parent/child) |
| ChatSessions | — | Chat management |
| Clients | — | Logo + company info |
| HeroSlides | — | Homepage slider |
| HomepageSections | — | Section ordering |
| Inquiries | — | Contact/quote management |
| PageContents | — | CMS blocks |
| **Products** | **Variants, Media, FAQs, Documents, MarketplaceLinks** | Full product CRUD |
| Roles | — | Spatie Permission |
| Settings | — | Key-value pairs |
| Tags | — | Article tags |
| Testimonials | — | Client testimonials |
| Users | — | User management |

### Custom Pages (8)
| Page | Fungsi |
|------|--------|
| ActivityLog | Riwayat aktivitas sistem |
| BackupManager | Backup database |
| CompanySettings | Info perusahaan (general + contact) |
| LiveChat | Operator chat interface |
| MarketplaceSettings | Konfigurasi marketplace |
| SeoSettings | Google Analytics, GTM, meta defaults |
| SocialMediaSettings | Link sosial media |
| StaticPageSettings | Gambar halaman statis |

### Dashboard Widgets (8)
- StatsOverview, PopularProducts, LatestInquiries, LatestChats
- PerformanceStats, PerformanceChart, AuditScores, AuditIssues

---

## 9. Frontend & Asset Pipeline

### Vite Configuration
```javascript
// Entry points
input: ['resources/css/app.css', 'resources/js/app.js']

// Build optimizations
minify: 'terser'          // Drop console.log & debugger
compression: gzip + brotli // Dual compression
cssCodeSplit: true         // Separate CSS chunks
target: 'es2020'           // Modern browsers
sourcemap: false           // Disabled in production

// Code splitting
manualChunks: {
    alpine: ['alpinejs', '@alpinejs/intersect', '@alpinejs/collapse']
}
```

### Tailwind Config
- **Fonts**: Inter (body), Plus Jakarta Sans (heading) — **self-hosted** di `/fonts/`, format `.woff2`
- **Colors**: primary (blue), secondary (red), accent (amber)
- **Content scan**: `resources/**/*.blade.php`, `resources/**/*.js`, `app/**/*.php`

### Font Optimization
- Font files self-hosted di `public/fonts/` (Inter + Plus Jakarta Sans)
- Format: `.woff2` only (optimal compression)
- `font-display: swap` pada semua `@font-face` di `app.css`
- Preload font latin di `<head>` layout:
  ```html
  <link rel="preload" href="/fonts/inter/inter-latin.woff2" as="font" type="font/woff2" crossorigin>
  <link rel="preload" href="/fonts/plus-jakarta-sans/plus-jakarta-sans-latin.woff2" as="font" type="font/woff2" crossorigin>
  ```
- **Tidak menggunakan Google Fonts API** — menghilangkan render-blocking external request

### Image Best Practices
Semua `<img>` tag di blade templates harus menyertakan:
- `width` dan `height` eksplisit (mencegah CLS)
- `loading="lazy"` (kecuali LCP image → `loading="eager"` + `fetchpriority="high"`)
- `decoding="async"`
- `alt` deskriptif (nama produk/artikel, bukan generik seperti "Thumbnail")

### Build Commands
```bash
npm run dev      # Development server dengan HMR
npm run build    # Production build
npm run preview  # Preview production build
```

### Full Dev Environment
```bash
composer dev     # Runs: php artisan serve + queue:listen + pail + vite dev
```

---

## 10. SEO & Indexability

### Meta Tags
Dikelola di `layouts/app.blade.php`:
- `<title>` — dari `@section('title')` atau `$metaTitle` atau config default
- `<meta name="description">` — dari `@section('meta_description')` atau `$metaDescription`
- `<meta name="robots">` — dari `$metaRobots` (default: `index, follow`)
- `<link rel="canonical">` — Selalu ada di semua halaman
- `<link rel="alternate" hreflang="...">` — Versi id, en, dan x-default di semua halaman

### Canonical Strategy
| Kondisi | Canonical |
|---------|-----------|
| Halaman normal | `strtok(url()->current(), '?')` (self-referencing, strip query params) |
| Halaman noindex (search/filter/paginated) | URL induk yang indexable (misal `/products`, `/articles`) |
| Controller menyetel custom | `$canonicalUrl` variable |

### Noindex Pages
Halaman berikut di-set `noindex, follow` dengan canonical ke halaman induk:
- `/search?q=xxx` → canonical: `/search`
- `/products?search=xxx` atau `?page=2` → canonical: `/products`
- `/products/category/xxx?page=2` → canonical: `/products/category/xxx`
- `/articles?search=xxx` atau `?page=2` → canonical: `/articles`
- `/language/{locale}` — via `X-Robots-Tag` header
- `/go/{platform}/{id}` — via `X-Robots-Tag` header

### Structured Data (JSON-LD)
Dihasilkan oleh `SchemaService`:
- **Organization** — di semua halaman
- **LocalBusiness (WholesaleStore)** — di homepage
- **Product** — di halaman detail produk
- **Article** — di halaman detail artikel
- **BreadcrumbList** — di halaman produk & artikel
- **FAQPage** — di halaman produk yang punya FAQ

### XML Sitemap
- URL: `/sitemap.xml`
- Cached 24 jam (file-based)
- Meliputi: halaman statis, produk, kategori, artikel
- Prioritas: homepage 1.0, produk/artikel 0.8, kategori 0.6

### robots.txt
```
User-agent: *
Allow: /
Disallow: /admin/, /livewire/, /api/, /storage/logs/, /_debugbar/, /vendor/, /language/, /go/
Sitemap: https://berkahmandiri.co.id/sitemap.xml
```
> **Catatan:** Cloudflare menambahkan blok `Content-Signal` dan AI bot blocking di depan robots.txt. Ini normal dan tidak mempengaruhi SEO.

### Open Graph
Semua halaman menyertakan OG tags:
- `og:title`, `og:description`, `og:image` (1200x630), `og:url`, `og:type`, `og:site_name`, `og:locale`
- Twitter Card: `summary_large_image`

### Hreflang (Multilingual SEO)
Semua halaman menyertakan hreflang tags di `<head>`:
```html
<link rel="alternate" hreflang="id" href="https://berkahmandiri.co.id/products?lang=id">
<link rel="alternate" hreflang="en" href="https://berkahmandiri.co.id/products?lang=en">
<link rel="alternate" hreflang="x-default" href="https://berkahmandiri.co.id/products">
```
- `id` dan `en` menunjuk ke URL dengan `?lang=` parameter
- `x-default` menunjuk ke URL tanpa parameter (mengikuti preferensi user/session)
- Dihasilkan otomatis dari `strtok(url()->current(), '?')` di layout

### SEO Form Standards (Admin Panel)
Semua resource CMS harus menyertakan SEO fields bilingual:

| Field | Max Length | Wajib | Catatan |
|-------|-----------|-------|--------|
| `meta_title` (id/en) | 60 karakter | Tidak | Fallback ke judul/nama |
| `meta_description` (id/en) | 160 karakter | Tidak | Fallback ke excerpt/deskripsi |
| `meta_keywords` (id/en) | 255 karakter | Tidak | Pisahkan dengan koma |
| `alt_text` (id) pada gambar | 255 karakter | **Ya** | Wajib untuk SEO & aksesibilitas |
| `alt_text` (en) pada gambar | 255 karakter | Tidak | Fallback ke versi ID |

---

## 11. Keamanan (Security)

### Security Headers (SecurityHeaders Middleware)
Diterapkan ke semua response HTML:

| Header | Nilai |
|--------|-------|
| X-Content-Type-Options | `nosniff` |
| X-Frame-Options | `SAMEORIGIN` |
| X-XSS-Protection | `1; mode=block` |
| Referrer-Policy | `strict-origin-when-cross-origin` |
| Permissions-Policy | `camera=(), microphone=(), geolocation=()` |
| HSTS | `max-age=31536000; includeSubDomains` (production) |
| Content-Security-Policy | Detailed per-directive policy |
| Removed | `X-Powered-By`, `Server` |

### CSP Directives
```
default-src: 'self'
script-src: 'self' 'unsafe-inline' 'unsafe-eval' + googletagmanager, google-analytics, recaptcha, youtube
style-src: 'self' 'unsafe-inline' + googleapis.com
img-src: 'self' data: blob: + googletagmanager, google-analytics, i.ytimg.com, *.cloudinary.com
connect-src: 'self' + google-analytics, doubleclick.net, cloudflareinsights
font-src: 'self' data: + gstatic.com
frame-src: youtube.com/embed, recaptcha.google.com
```

### Input Sanitization
- **HTML Content**: `HtmlSanitizer` — whitelist tags/attributes, strip scripts/styles/event handlers
- **Search Queries**: Max 100 chars, validated per controller
- **Slugs**: Regex validated `/^[a-z0-9\-]+$/`
- **Rate Limiting**: POST routes `throttle:5,1`, marketplace redirect `throttle:30,1`

### Session
- **Driver**: Database
- **Encryption**: Enabled
- **Lifetime**: 120 menit

---

## 12. Caching Strategy

### Multi-Level Cache

| Level | Mekanisme | TTL | Digunakan Untuk |
|-------|-----------|-----|-----------------|
| **HTTP Cache** | CacheResponse middleware | 5 menit | Homepage (unauthenticated) |
| **Application Cache** | File cache | 30-60 menit | Products, categories, articles, settings |
| **In-Memory** | Static property | Per-request | SettingService values |
| **CDN Cache** | Cloudflare | Varies | Static assets + HTML |

### Cache Key Convention
```php
// Format: {entity}_{identifier}_{locale}
"products_featured_{$locale}"
"categories_navigation_{$locale}"
"articles_recent_{$locale}"
"settings_group_{$group}"
"homepage_data_{$locale}"
```

### Cache Invalidation
Observer-based: Ketika model disimpan/dihapus, observer akan:
1. `Cache::forget()` key terkait
2. `CloudflarePurgeService::purgeUrls()` URL terkait

---

## 13. Internationalization (i18n)

### Setup
- **Primary locale**: `id` (Indonesia)
- **Fallback**: `en` (English)
- **Method**: Spatie Laravel Translatable (JSON columns)
- **Translation files**: `lang/en.json`, `lang/id.json`

### Locale Detection (SetLocale Middleware)
Prioritas:
1. Query parameter `?lang=id`
2. Session value
3. Cookie value
4. Config default

### Language Switcher
- Menggunakan `<button>` (bukan `<a>`) untuk menghindari crawling
- Set locale di session + cookie (1 tahun)
- Response header: `X-Robots-Tag: noindex, nofollow`

### Hreflang Tags
Semua halaman otomatis menyertakan hreflang di `layouts/app.blade.php`:
- `hreflang="id"` → URL + `?lang=id`
- `hreflang="en"` → URL + `?lang=en`
- `hreflang="x-default"` → URL tanpa parameter

Ini membantu Google memahami relasi antar versi bahasa dan mencegah duplikat konten.

### Menambahkan Terjemahan Baru
1. Tambahkan key di `lang/id.json` dan `lang/en.json`
2. Gunakan `{{ __('key') }}` di blade atau `trans('key')` di PHP

---

## 14. Media & Image Optimization

### Config (config/media.php)
| Tipe | Disk | Max Size | Format Output |
|------|------|----------|---------------|
| Images | public | 5 MB | WebP (auto-convert) |
| Videos | public | 100 MB | Original + YouTube |
| Documents | public | 20 MB | PDF/DOC/XLS/PPT |

### Image Conversions (Otomatis)
| Nama | Dimensi | Kualitas |
|------|---------|---------|
| thumbnail | 150x150 | 80% |
| small | 320w | 80% |
| medium | 640w | 80% |
| large | 1024w | 80% |
| hero | 1920x1080 | 85% |

### ImageOptimizationService
- Progressive quality reduction: mulai dari 85%, turunkan sampai file < target size
- Auto-generate mobile variant (640w) untuk HeroSlide
- Favicon: konversi ke ICO via GD
- Upload processing: validasi tipe + resize + convert WebP

### Product Limits
- Max 10 gambar, 5 video, 10 dokumen per produk
- Primary image wajib ada

---

## 15. Chat & AI Integration

### ChatWidget (Livewire Component)
- **Posisi**: Bottom-right floating widget
- **Session**: Cookie-based (7 hari)
- **Flow**: Visitor isi nama/email/phone → Chat dimulai → AI response → Handover ke operator jika diperlukan

### AI Configuration
| Setting | Default |
|---------|---------|
| Provider | OpenAI |
| Model | gpt-4o-mini |
| Max Tokens | 1000 |
| Temperature | 0.7 |
| Bahasa | Indonesia |

### Knowledge Base
AI dibangun dari:
- Daftar produk & kategori
- Informasi perusahaan
- Keyword handover (misal: "bicara dengan manusia", "operator", "komplain")

### Working Hours
- Senin-Jumat: 08:00-17:00
- Sabtu: 08:00-12:00
- Minggu: Tutup
- Timezone: Asia/Jakarta

---

## 16. Email & Notification

### Mailable Classes
| Class | Dikirim Ke | Trigger |
|-------|-----------|---------|
| `InquiryConfirmation` | Customer | Setelah submit inquiry/contact |
| `InquiryNotification` | Admin (info@berkahmandiri.co.id) | Setelah submit inquiry/contact |

### Email Config
- **Mailer**: SMTP (failover: smtp → log)
- **Encryption**: TLS
- **verify_peer**: false (shared hosting)

---

## 17. Deployment

### Method
FTP-based deployment via `deploy.php`

### Commands
```bash
# Deploy file yang berubah sejak commit terakhir (default)
php deploy.php

# Deploy file tertentu
php deploy.php --file=app/Http/Controllers/HomeController.php,resources/views/layouts/app.blade.php --force

# Preview tanpa upload
php deploy.php --dry-run

# Full sync semua file
php deploy.php --all --force
```

### Cara Kerja
1. **Git-based change detection**: Membandingkan commit saat ini dengan commit terakhir deploy (disimpan di `.last_deploy`)
2. **FTP upload**: File yang berubah di-upload ke production server
3. **Excluded**: `.env`, `.git`, `node_modules`, `vendor`, `storage/logs|cache|sessions`, `tests`, `deploy.php`

### Deployment Checklist
1. ✅ Pastikan kode sudah di-test lokal
2. ✅ `npm run build` jika ada perubahan frontend
3. ✅ Deploy file PHP: `php deploy.php --file=... --force`
4. ✅ Deploy build assets: `php deploy.php --file=public/build/manifest.json,[css],[js] --force`
5. ✅ Purge Cloudflare cache jika diperlukan
6. ✅ Test di production

### FTP Configuration (.env)
```
FTP_HOST=109.106.253.35
FTP_USERNAME=web@berkahmandiri.co.id
FTP_PASSWORD=***
FTP_PORT=21
FTP_PASSIVE=true
```

---

## 18. Performance Monitoring

### PerformanceLogging Middleware
- Mengukur: response time, memory, DB query count/time
- Hanya untuk GET request HTML (exclude: livewire, debugbar, admin, API, AJAX)
- Logging ke tabel `performance_logs`
- Sample rate: 10%

### Performance Targets (config/audit.php)
| Metrik | Target |
|--------|--------|
| Overall Score | 95 |
| FCP (First Contentful Paint) | < 1.8s |
| LCP (Largest Contentful Paint) | < 2.5s |
| CLS (Cumulative Layout Shift) | < 0.1 |
| TTI (Time to Interactive) | < 3.8s |
| TBT (Total Blocking Time) | < 200ms |

### Artisan Commands
```bash
php artisan audit:run           # Jalankan audit manual
php artisan audit:scheduled     # Jalankan audit terjadwal
php artisan logs:prune          # Hapus log lama (90 hari retention)
php artisan optimize:production # Optimasi production
```

---

## 19. Backup & Recovery

### Spatie Backup
```bash
php artisan backup:run          # Full backup (DB + files)
php artisan backup:run --only-db # Database only
php artisan backup:list         # Lihat daftar backup
```

### Backup Scope
- **Database**: MySQL dump
- **Files**: `storage/app/public`, `.env`
- **Storage**: `storage/app/backups`

### Custom Commands
```bash
php artisan db:backup           # Custom backup command
php artisan db:backup-list      # Custom list command
```

---

## 20. Konvensi Penamaan & Coding Style

### PHP / Laravel
| Elemen | Convention | Contoh |
|--------|-----------|--------|
| Controller | PascalCase + `Controller` | `ProductController` |
| Model | PascalCase singular | `Product`, `Category` |
| Service | PascalCase + `Service` | `ProductService` |
| Observer | PascalCase + `Observer` | `ProductObserver` |
| Migration | snake_case deskriptif | `create_products_table` |
| Route name | dot.notation | `products.show` |
| Config key | snake_case | `seo.defaults.title` |
| Cache key | snake_case + locale | `products_featured_id` |
| View path | kebab-case | `pages.about.vision-mission` |

### Blade Templates
| Elemen | Convention |
|--------|-----------|
| Layout | `layouts/app.blade.php` |
| Page view | `pages/{section}/{action}.blade.php` |
| Component | `components/{name}.blade.php` |
| Partial | `layouts/partials/{name}.blade.php` |
| Icon | `components/icons/{name}.blade.php` |

### Database
| Elemen | Convention |
|--------|-----------|
| Table | plural snake_case (`products`, `hero_slides`) |
| Column | snake_case (`is_active`, `created_at`) |
| Foreign key | `{model}_id` (`category_id`) |
| Boolean | prefix `is_` atau `has_` |
| JSON translatable | Column name saja (`name`, `description`) |
| Pivot table | alphabetical singular (`article_tag`) |

### CSS / Tailwind
- Utility-first, hindari custom CSS kecuali untuk CMS content (`.article-content`, `.product-description`)
- Color menggunakan design token: `primary-*`, `secondary-*`, `accent-*`

---

## 21. Environment Variables

### Wajib (Required)
```env
APP_NAME=
APP_KEY=
APP_URL=

DB_HOST=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

MAIL_MAILER=smtp
MAIL_HOST=
MAIL_PORT=
MAIL_USERNAME=
MAIL_PASSWORD=

FTP_HOST=
FTP_USERNAME=
FTP_PASSWORD=
```

### Opsional (SEO & Analytics)
```env
GOOGLE_ANALYTICS_ID=G-XXXXXXXXXX
GTM_ID=GTM-XXXXXXX
GOOGLE_SITE_VERIFICATION=
BING_SITE_VERIFICATION=
```

### Opsional (Services)
```env
CHAT_ENABLED=true
CHAT_AI_PROVIDER=openai
CHAT_AI_MODEL=gpt-4o-mini
OPENAI_API_KEY=

CLOUDFLARE_ZONE_ID=
CLOUDFLARE_API_TOKEN=
```

---

## 22. Troubleshooting

### Cache Issues
```bash
php artisan cache:clear         # Clear application cache
php artisan config:clear        # Clear config cache
php artisan view:clear          # Clear compiled views
php artisan route:clear         # Clear route cache
```

### Common Issues

| Masalah | Solusi |
|---------|--------|
| Perubahan tidak terlihat di production | Purge Cloudflare cache |
| CSS/JS tidak update | `npm run build` lalu deploy `public/build/` |
| Terjemahan tidak muncul | Cek key di `lang/en.json` dan `lang/id.json` |
| Setting tidak berubah | `Cache::forget("settings_{$group}")` |
| Gambar tidak tampil | Cek `php artisan storage:link` |
| Email gagal terkirim | Cek konfigurasi SMTP di `.env`, pastikan `verify_peer=false` |
| SiteChecker "Content-Signal" warning | Normal — diinjeksi Cloudflare, abaikan |

### Useful Debug
```bash
php artisan tinker              # Interactive shell
php artisan route:list          # Daftar semua routes
php artisan migrate:status      # Status migration
```

---

## Changelog

| Tanggal | Perubahan |
|---------|-----------|| 2026-02-12 | Hreflang tags (id/en/x-default) di semua halaman, alt text deskriptif pada thumbnail produk, meta_keywords ditambahkan di CategoryForm, alt_text.id wajib di ProductMedia, meta_title maxLength disamakan ke 60, font optimization & image best practices didokumentasikan || 2026-02-11 | Dokumen dibuat — arsitektur lengkap, SEO standards, deployment guide |
