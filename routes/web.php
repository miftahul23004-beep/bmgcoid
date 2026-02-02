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
