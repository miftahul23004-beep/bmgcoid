<?php

namespace App\Observers;

use App\Models\Article;
use App\Services\CloudflarePurgeService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class ArticleObserver
{
    /**
     * Handle the Article "saved" event.
     * Clear cache and purge Cloudflare
     */
    public function saved(Article $article): void
    {
        // Clear article cache
        Cache::forget('articles_homepage');
        Cache::forget('articles_featured');
        Cache::forget('article_' . $article->slug);
        
        // Auto-purge Cloudflare
        try {
            $purgeService = app(CloudflarePurgeService::class);
            $urls = [
                config('app.url'),
                config('app.url') . '/artikel',
                config('app.url') . '/artikel/' . $article->slug,
            ];
            $purgeService->purgeUrls($urls);
            \Log::info('Cloudflare purged for article: ' . $article->slug);
        } catch (\Exception $e) {
            \Log::warning('Cloudflare purge failed: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Article "deleted" event.
     */
    public function deleted(Article $article): void
    {
        // Delete featured image when article is deleted
        if ($article->featured_image) {
            Storage::disk('public')->delete($article->featured_image);
        }
        
        // Clear cache
        Cache::forget('articles_homepage');
        Cache::forget('articles_featured');
        
        // Purge Cloudflare
        try {
            $purgeService = app(CloudflarePurgeService::class);
            $purgeService->purgeUrls([
                config('app.url'),
                config('app.url') . '/artikel',
            ]);
        } catch (\Exception $e) {
            \Log::warning('Cloudflare purge failed: ' . $e->getMessage());
        }
    }
}
