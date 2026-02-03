<?php

use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// XML Sitemap (for SEO/search engines)
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap.xml');

// Language Switcher
Route::get('language/{locale}', [LanguageController::class, 'switch'])
    ->name('language.switch')
    ->whereIn('locale', ['id', 'en']);

// Homepage
Route::get('/', [HomeController::class, 'index'])->name('home');

// About Pages
Route::prefix('about')->name('about.')->group(function () {
    Route::get('/', [HomeController::class, 'about'])->name('company');
    Route::get('/vision-mission', [HomeController::class, 'visionMission'])->name('vision-mission');
    Route::get('/team', [HomeController::class, 'team'])->name('team');
    Route::get('/certificates', [HomeController::class, 'certificates'])->name('certificates');
});

// Products
Route::prefix('products')->name('products.')->group(function () {
    Route::get('/', [ProductController::class, 'index'])->name('index');
    Route::get('/category/{slug}', [ProductController::class, 'category'])->name('category');
    Route::get('/{slug}', [ProductController::class, 'show'])->name('show');
});

// Articles
Route::prefix('articles')->name('articles.')->group(function () {
    Route::get('/', [ArticleController::class, 'index'])->name('index');
    Route::get('/tag/{slug}', [ArticleController::class, 'tag'])->name('tag');
    Route::get('/{slug}', [ArticleController::class, 'show'])->name('show');
});

// Testimonials
Route::get('/testimonials', [HomeController::class, 'testimonials'])->name('testimonials');

// Contact & Quote
Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'submit'])->name('contact.submit')->middleware('throttle:5,1');
Route::get('/quote', [ContactController::class, 'quote'])->name('quote');
Route::post('/quote', [ContactController::class, 'submitQuote'])->name('quote.submit')->middleware('throttle:5,1');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');

// Static Pages
Route::get('/privacy', [HomeController::class, 'privacy'])->name('privacy');
Route::get('/privacy/pdf', [HomeController::class, 'privacyPdf'])->name('privacy.pdf');
Route::get('/terms', [HomeController::class, 'terms'])->name('terms');
Route::get('/terms/pdf', [HomeController::class, 'termsPdf'])->name('terms.pdf');
Route::get('/sitemap', [HomeController::class, 'sitemap'])->name('sitemap');

// Marketplace Link Redirect (for tracking) - Rate limited
Route::get('/go/{platform}/{productId}', [ProductController::class, 'marketplaceRedirect'])
    ->name('marketplace.redirect')
    ->middleware('throttle:30,1'); // 30 requests per minute

// Clear hero slides cache (temporary utility route)
Route::get('/clear-hero-cache/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    \Illuminate\Support\Facades\Cache::forget('hero_slides.displayable');
    return response()->json(['status' => 'ok', 'message' => 'Hero slides cache cleared']);
});

// Debug hero slides (temporary)
Route::get('/debug-hero/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    $slides = \App\Models\HeroSlide::select('id', 'title_id', 'image', 'mobile_image', 'is_active')->get();
    $files = \Illuminate\Support\Facades\Storage::disk('public')->files('hero-slides');
    return response()->json([
        'database' => $slides,
        'files_in_folder' => $files
    ]);
});

// Debug language (temporary)
Route::get('/debug-lang/{secret}', function ($secret, \Illuminate\Http\Request $request) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    return response()->json([
        'app_locale' => app()->getLocale(),
        'session_locale' => session('locale'),
        'cookie_locale' => $request->cookie('locale'),
        'config_locale' => config('app.locale'),
        'all_cookies' => $request->cookies->all(),
    ]);
});

// Check file content (temporary)
Route::get('/check-code/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $filePath = app_path('Filament/Resources/HeroSlides/Schemas/HeroSlideForm.php');
    $content = file_get_contents($filePath);
    
    // Find processUpload calls
    preg_match_all('/processUpload\([^)]+,\s*(\d+)\)/', $content, $matches);
    
    return response()->json([
        'file_exists' => file_exists($filePath),
        'file_size' => filesize($filePath),
        'max_sizes_found' => $matches[1] ?? [],
        'has_saveUploadedFileUsing' => str_contains($content, 'saveUploadedFileUsing'),
        'snippet' => substr($content, strpos($content, 'saveUploadedFileUsing') ?: 0, 500),
    ]);
});

// Test Cloudflare purge (temporary)
Route::get('/test-purge/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $zoneId = config('services.cloudflare.zone_id');
    $apiToken = config('services.cloudflare.api_token');
    $baseUrl = config('app.url');
    
    $urls = [
        $baseUrl,
        $baseUrl . '/',
    ];
    
    try {
        $response = \Illuminate\Support\Facades\Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiToken,
            'Content-Type' => 'application/json',
        ])->post("https://api.cloudflare.com/client/v4/zones/{$zoneId}/purge_cache", [
            'files' => $urls,
        ]);
        
        return response()->json([
            'success' => $response->successful(),
            'status' => $response->status(),
            'response' => $response->json(),
            'urls_purged' => $urls,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
        ]);
    }
});

// Re-optimize hero images (temporary)
Route::get('/reoptimize-hero/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $service = app(\App\Services\ImageOptimizationService::class);
    $results = [];
    
    $slides = \App\Models\HeroSlide::all();
    
    foreach ($slides as $slide) {
        $slideResult = ['id' => $slide->id, 'title' => $slide->title_id];
        
        // Re-optimize desktop image
        if ($slide->image && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->image)) {
            $oldPath = $slide->image;
            $oldSize = \Illuminate\Support\Facades\Storage::disk('public')->size($oldPath) / 1024;
            
            $newPath = $service->convertToWebp($oldPath, 100, 1920);
            $newSize = \Illuminate\Support\Facades\Storage::disk('public')->size($newPath) / 1024;
            
            $slideResult['desktop'] = [
                'old_size' => round($oldSize, 2) . 'KB',
                'new_size' => round($newSize, 2) . 'KB',
                'path' => $newPath,
            ];
            
            if ($newPath !== $oldPath) {
                \App\Models\HeroSlide::withoutEvents(function () use ($slide, $newPath) {
                    $slide->update(['image' => $newPath]);
                });
            }
        }
        
        // Re-optimize mobile image
        if ($slide->mobile_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($slide->mobile_image)) {
            $oldPath = $slide->mobile_image;
            $oldSize = \Illuminate\Support\Facades\Storage::disk('public')->size($oldPath) / 1024;
            
            $newPath = $service->convertToWebp($oldPath, 80, 750);
            $newSize = \Illuminate\Support\Facades\Storage::disk('public')->size($newPath) / 1024;
            
            $slideResult['mobile'] = [
                'old_size' => round($oldSize, 2) . 'KB',
                'new_size' => round($newSize, 2) . 'KB',
                'path' => $newPath,
            ];
            
            if ($newPath !== $oldPath) {
                \App\Models\HeroSlide::withoutEvents(function () use ($slide, $newPath) {
                    $slide->update(['mobile_image' => $newPath]);
                });
            }
        }
        
        $results[] = $slideResult;
    }
    
    // Clear cache
    \Illuminate\Support\Facades\Cache::forget('hero_slides.displayable');
    
    // Purge Cloudflare
    app(\App\Services\CloudflarePurgeService::class)->purgeHomepage();
    
    return response()->json([
        'success' => true,
        'results' => $results,
    ]);
});

// Debug GD WebP support (temporary)
Route::get('/debug-gd/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $gdLoaded = extension_loaded('gd');
    $gdInfo = $gdLoaded ? gd_info() : null;
    $webpSupport = $gdInfo['WebP Support'] ?? false;
    
    // Test actual WebP creation
    $testResult = 'not tested';
    if ($webpSupport) {
        try {
            $img = imagecreatetruecolor(100, 100);
            $testPath = storage_path('app/public/test-webp-' . time() . '.webp');
            $result = imagewebp($img, $testPath);
            if ($result && file_exists($testPath)) {
                $testResult = 'success - file created: ' . filesize($testPath) . ' bytes';
                unlink($testPath);
            } else {
                $testResult = 'failed - file not created';
            }
            imagedestroy($img);
        } catch (\Exception $e) {
            $testResult = 'error: ' . $e->getMessage();
        }
    }
    
    // Check articles folder
    $articleFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('articles');
    
    return response()->json([
        'gd_loaded' => $gdLoaded,
        'gd_info' => $gdInfo,
        'webp_support' => $webpSupport,
        'webp_test' => $testResult,
        'articles_files' => $articleFiles,
        'storage_path' => storage_path('app/public'),
        'storage_writable' => is_writable(storage_path('app/public')),
    ]);
});

// Debug recent logs (temporary)
Route::get('/debug-log/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $logPath = storage_path('logs/laravel.log');
    if (!file_exists($logPath)) {
        return response()->json(['error' => 'Log file not found']);
    }
    
    // Get last 100 lines
    $lines = [];
    $file = new \SplFileObject($logPath, 'r');
    $file->seek(PHP_INT_MAX);
    $totalLines = $file->key();
    $startLine = max(0, $totalLines - 100);
    
    $file->seek($startLine);
    while (!$file->eof()) {
        $line = $file->fgets();
        if (str_contains($line, 'processUpload') || str_contains($line, 'Image converted') || str_contains($line, 'upload') || str_contains($line, 'Error')) {
            $lines[] = trim($line);
        }
    }
    
    return response()->json([
        'log_lines' => array_slice($lines, -30),
        'total_lines' => $totalLines,
    ]);
});

// Debug articles (temporary)
Route::get('/debug-articles/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $articles = \App\Models\Article::select('id', 'slug', 'featured_image', 'created_at')
        ->orderByDesc('created_at')
        ->limit(5)
        ->get();
    
    $articleFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('articles');
    
    $result = [];
    foreach ($articles as $article) {
        $imageExists = $article->featured_image 
            ? \Illuminate\Support\Facades\Storage::disk('public')->exists($article->featured_image)
            : null;
        $result[] = [
            'id' => $article->id,
            'slug' => $article->slug,
            'featured_image_db' => $article->featured_image,
            'image_exists' => $imageExists,
            'storage_url' => $article->featured_image ? \Illuminate\Support\Facades\Storage::disk('public')->url($article->featured_image) : null,
        ];
    }
    
    return response()->json([
        'articles' => $result,
        'files_in_folder' => $articleFiles,
    ]);
});

// Debug categories (temporary)
Route::get('/debug-categories/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    $categories = \App\Models\Category::select('id', 'name', 'slug', 'icon', 'image')
        ->where('is_active', true)
        ->get();
    
    $categoryFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('categories/images');
    $iconFiles = \Illuminate\Support\Facades\Storage::disk('public')->files('categories/icons');
    
    $result = [];
    foreach ($categories as $category) {
        $imageExists = $category->image 
            ? \Illuminate\Support\Facades\Storage::disk('public')->exists($category->image)
            : null;
        $iconExists = $category->icon 
            ? \Illuminate\Support\Facades\Storage::disk('public')->exists($category->icon)
            : null;
        $result[] = [
            'id' => $category->id,
            'name' => $category->name,
            'image_db' => $category->image,
            'image_exists' => $imageExists,
            'icon_db' => $category->icon,
            'icon_exists' => $iconExists,
        ];
    }
    
    return response()->json([
        'categories' => $result,
        'image_files' => $categoryFiles,
        'icon_files' => $iconFiles,
    ]);
});

// Debug migrate (temporary)
Route::get('/debug-migrate/{secret}', function ($secret) {
    if ($secret !== 'bmg2026secure') {
        abort(404);
    }
    
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate', ['--force' => true]);
        $output = \Illuminate\Support\Facades\Artisan::output();
        return response()->json([
            'status' => 'success',
            'output' => $output,
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage(),
        ], 500);
    }
});
