<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SitemapController extends Controller
{
    /**
     * Generate XML Sitemap with file-based caching for performance
     */
    public function index(): Response
    {
        $cachePath = 'sitemap/sitemap.xml';
        $cacheMinutes = config('seo.sitemap.cache_duration', 1440); // 24 hours default
        
        // Check if file cache exists and is fresh
        if (Storage::disk('local')->exists($cachePath)) {
            $lastModified = Storage::disk('local')->lastModified($cachePath);
            $expiresAt = $lastModified + ($cacheMinutes * 60);
            
            if (time() < $expiresAt) {
                $xml = Storage::disk('local')->get($cachePath);
                return response($xml, 200, [
                    'Content-Type' => 'application/xml',
                    'X-Cache' => 'HIT',
                ]);
            }
        }
        
        // Generate fresh sitemap
        $xml = $this->generateSitemap();
        
        // Save to file cache
        Storage::disk('local')->put($cachePath, $xml);

        return response($xml, 200, [
            'Content-Type' => 'application/xml',
            'X-Cache' => 'MISS',
        ]);
    }

    /**
     * Generate the sitemap XML content
     */
    protected function generateSitemap(): string
    {
        $urls = [];
        $baseUrl = rtrim(config('app.url'), '/');

        // Static pages
        $staticPages = [
            ['loc' => '/', 'changefreq' => 'daily', 'priority' => '1.0'],
            ['loc' => '/about', 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => '/about/vision-mission', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/about/team', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/about/certificates', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/products', 'changefreq' => 'weekly', 'priority' => '0.9'],
            ['loc' => '/articles', 'changefreq' => 'weekly', 'priority' => '0.8'],
            ['loc' => '/contact', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/quote', 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => '/testimonials', 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => '/privacy', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/terms', 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => '/sitemap', 'changefreq' => 'weekly', 'priority' => '0.5'],
        ];

        foreach ($staticPages as $page) {
            $urls[] = [
                'loc' => $baseUrl . $page['loc'],
                'changefreq' => $page['changefreq'],
                'priority' => $page['priority'],
            ];
        }

        // Products
        if (config('seo.sitemap.include.products', true)) {
            $products = Product::where('is_active', true)
                ->select('slug', 'updated_at')
                ->get();

            foreach ($products as $product) {
                $urls[] = [
                    'loc' => $baseUrl . '/products/' . $product->slug,
                    'lastmod' => $product->updated_at->toW3cString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.8',
                ];
            }
        }

        // Categories - use clean URL path instead of query parameters
        if (config('seo.sitemap.include.categories', true)) {
            $categories = Category::where('is_active', true)
                ->select('slug', 'updated_at')
                ->get();

            foreach ($categories as $category) {
                $urls[] = [
                    'loc' => $baseUrl . '/products/category/' . $category->slug,
                    'lastmod' => $category->updated_at->toW3cString(),
                    'changefreq' => 'weekly',
                    'priority' => '0.7',
                ];
            }
        }

        // Articles
        if (config('seo.sitemap.include.articles', true)) {
            $articles = Article::where('status', 'published')
                ->where('published_at', '<=', now())
                ->select('slug', 'updated_at')
                ->get();

            foreach ($articles as $article) {
                $urls[] = [
                    'loc' => $baseUrl . '/articles/' . $article->slug,
                    'lastmod' => $article->updated_at->toW3cString(),
                    'changefreq' => 'monthly',
                    'priority' => '0.6',
                ];
            }
        }

        return $this->buildXml($urls);
    }

    /**
     * Build XML from URLs array
     */
    protected function buildXml(array $urls): string
    {
        $xml = '<?xml version="1.0" encoding="UTF-8"?>' . PHP_EOL;
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . PHP_EOL;

        foreach ($urls as $url) {
            $xml .= '  <url>' . PHP_EOL;
            $xml .= '    <loc>' . htmlspecialchars($url['loc']) . '</loc>' . PHP_EOL;
            
            if (isset($url['lastmod'])) {
                $xml .= '    <lastmod>' . $url['lastmod'] . '</lastmod>' . PHP_EOL;
            }
            
            if (isset($url['changefreq'])) {
                $xml .= '    <changefreq>' . $url['changefreq'] . '</changefreq>' . PHP_EOL;
            }
            
            if (isset($url['priority'])) {
                $xml .= '    <priority>' . $url['priority'] . '</priority>' . PHP_EOL;
            }
            
            $xml .= '  </url>' . PHP_EOL;
        }

        $xml .= '</urlset>';

        return $xml;
    }

    /**
     * Clear sitemap cache
     */
    public function clearCache(): void
    {
        Cache::forget('sitemap.xml');
        Storage::disk('local')->delete('sitemap/sitemap.xml');
    }
}
