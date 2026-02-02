<?php

namespace App\Services\Audit;

use App\Models\AuditResult;
use App\Models\AuditIssue;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PerformanceAuditService
{
    protected array $config;
    protected array $issues = [];
    protected array $internalMetrics = [];

    public function __construct()
    {
        $this->config = config('audit.performance', []);
    }

    /**
     * Run complete performance audit for a URL
     */
    public function audit(string $url, string $pageType = 'page'): AuditResult
    {
        $this->issues = [];
        $startTime = microtime(true);

        // Internal performance checks
        $internalMetrics = $this->measureInternalPerformance($url);
        $this->internalMetrics = $internalMetrics; // Store for fallback estimation
        
        // PageSpeed API check (if enabled), pass internal metrics for fallback estimation
        $pageSpeedMetrics = $this->getPageSpeedMetrics($url, $internalMetrics);
        
        // Merge metrics
        $metrics = array_merge($internalMetrics, $pageSpeedMetrics);
        
        // Analyze and generate issues
        $this->analyzeMetrics($metrics);
        
        $executionTime = round((microtime(true) - $startTime) * 1000);

        // Save result
        $auditResult = AuditResult::create([
            'url' => $url,
            'page_type' => $pageType,
            'audit_type' => 'performance',
            'performance_score' => $metrics['performance_score'] ?? null,
            'seo_score' => $pageSpeedMetrics['seo_score'] ?? null,
            'accessibility_score' => $pageSpeedMetrics['accessibility_score'] ?? null,
            'best_practices_score' => $pageSpeedMetrics['best_practices_score'] ?? null,
            'fcp' => $metrics['fcp'] ?? null,
            'lcp' => $metrics['lcp'] ?? null,
            'cls' => $metrics['cls'] ?? null,
            'tti' => $metrics['tti'] ?? null,
            'tbt' => $metrics['tbt'] ?? null,
            'speed_index' => $metrics['speed_index'] ?? null,
            'page_size' => $metrics['page_size'] ?? null,
            'request_count' => $metrics['request_count'] ?? null,
            'load_time' => $metrics['load_time'] ?? null,
            'raw_data' => $metrics,
            'source' => 'manual',
            'notes' => "Audit completed in {$executionTime}ms",
        ]);

        // Save issues
        foreach ($this->issues as $issue) {
            $issue['audit_result_id'] = $auditResult->id;
            AuditIssue::create($issue);
        }

        return $auditResult;
    }

    /**
     * Measure internal performance metrics
     */
    protected function measureInternalPerformance(string $url): array
    {
        $startTime = microtime(true);
        
        try {
            // Make request to measure response
            // Disable SSL verification for local development
            $http = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'BMG-Performance-Audit/1.0',
                    'Accept' => 'text/html',
                ]);
            
            // Disable SSL verification for local URLs
            if (str_contains($url, 'localhost') || str_contains($url, '.test') || str_contains($url, '.local')) {
                $http = $http->withoutVerifying();
            }
            
            $response = $http->get($url);

            $loadTime = round((microtime(true) - $startTime) * 1000); // ms
            $contentLength = strlen($response->body());
            
            // Parse HTML for additional metrics
            $htmlMetrics = $this->analyzeHtml($response->body());

            return [
                'load_time' => $loadTime,
                'page_size' => $contentLength,
                'status_code' => $response->status(),
                'headers' => $response->headers(),
                'request_count' => $htmlMetrics['external_resources'] ?? 0,
                'image_count' => $htmlMetrics['images'] ?? 0,
                'script_count' => $htmlMetrics['scripts'] ?? 0,
                'style_count' => $htmlMetrics['styles'] ?? 0,
                'inline_styles' => $htmlMetrics['inline_styles'] ?? 0,
                'inline_scripts' => $htmlMetrics['inline_scripts'] ?? 0,
            ];
        } catch (\Exception $e) {
            Log::error('Performance audit failed', [
                'url' => $url,
                'error' => $e->getMessage(),
            ]);

            return [
                'error' => $e->getMessage(),
                'load_time' => null,
            ];
        }
    }

    /**
     * Get metrics from Google PageSpeed API
     */
    protected function getPageSpeedMetrics(string $url, array $internalMetrics = []): array
    {
        $apiKey = config('audit.performance.lighthouse.api_key');
        
        if (!$apiKey || !config('audit.performance.lighthouse.enabled', false)) {
            return $this->estimatePerformanceScore($internalMetrics);
        }

        try {
            $response = Http::timeout(60)->get('https://www.googleapis.com/pagespeedonline/v5/runPagespeed', [
                'url' => $url,
                'key' => $apiKey,
                'category' => ['performance', 'seo', 'accessibility', 'best-practices'],
                'strategy' => 'mobile',
            ]);

            if (!$response->successful()) {
                Log::warning('PageSpeed API error', ['status' => $response->status()]);
                return $this->estimatePerformanceScore($this->internalMetrics);
            }

            $data = $response->json();
            $lighthouse = $data['lighthouseResult'] ?? [];
            $categories = $lighthouse['categories'] ?? [];
            $audits = $lighthouse['audits'] ?? [];

            return [
                'performance_score' => isset($categories['performance']['score']) 
                    ? round($categories['performance']['score'] * 100) 
                    : null,
                'seo_score' => isset($categories['seo']['score']) 
                    ? round($categories['seo']['score'] * 100) 
                    : null,
                'accessibility_score' => isset($categories['accessibility']['score']) 
                    ? round($categories['accessibility']['score'] * 100) 
                    : null,
                'best_practices_score' => isset($categories['best-practices']['score']) 
                    ? round($categories['best-practices']['score'] * 100) 
                    : null,
                'fcp' => isset($audits['first-contentful-paint']['numericValue']) 
                    ? round($audits['first-contentful-paint']['numericValue']) 
                    : null,
                'lcp' => isset($audits['largest-contentful-paint']['numericValue']) 
                    ? round($audits['largest-contentful-paint']['numericValue']) 
                    : null,
                'cls' => $audits['cumulative-layout-shift']['numericValue'] ?? null,
                'tti' => isset($audits['interactive']['numericValue']) 
                    ? round($audits['interactive']['numericValue']) 
                    : null,
                'tbt' => isset($audits['total-blocking-time']['numericValue']) 
                    ? round($audits['total-blocking-time']['numericValue']) 
                    : null,
                'speed_index' => isset($audits['speed-index']['numericValue']) 
                    ? round($audits['speed-index']['numericValue']) 
                    : null,
                'lighthouse_audits' => $audits,
            ];
        } catch (\Exception $e) {
            Log::error('PageSpeed API failed', ['error' => $e->getMessage()]);
            return $this->estimatePerformanceScore($this->internalMetrics);
        }
    }

    /**
     * Estimate performance score based on internal metrics
     */
    protected function estimatePerformanceScore(array $internalMetrics = []): array
    {
        $score = 100;
        
        // Penalize based on load time
        $loadTime = $internalMetrics['load_time'] ?? 0;
        if ($loadTime > 5000) {
            $score -= 40;
        } elseif ($loadTime > 3000) {
            $score -= 25;
        } elseif ($loadTime > 2000) {
            $score -= 15;
        } elseif ($loadTime > 1000) {
            $score -= 5;
        }
        
        // Penalize based on page size (in bytes)
        $pageSize = $internalMetrics['page_size'] ?? 0;
        if ($pageSize > 2 * 1024 * 1024) { // > 2MB
            $score -= 25;
        } elseif ($pageSize > 1024 * 1024) { // > 1MB
            $score -= 15;
        } elseif ($pageSize > 500 * 1024) { // > 500KB
            $score -= 8;
        } elseif ($pageSize > 200 * 1024) { // > 200KB
            $score -= 3;
        }
        
        // Penalize based on request count
        $requestCount = $internalMetrics['request_count'] ?? 0;
        if ($requestCount > 100) {
            $score -= 15;
        } elseif ($requestCount > 50) {
            $score -= 8;
        } elseif ($requestCount > 30) {
            $score -= 3;
        }
        
        // Penalize inline scripts (render blocking)
        $inlineScripts = $internalMetrics['inline_scripts'] ?? 0;
        if ($inlineScripts > 5) {
            $score -= 5;
        }
        
        return [
            'performance_score' => max(0, min(100, $score)),
            'note' => 'Estimated score based on internal metrics. Add PAGESPEED_API_KEY to .env for accurate Core Web Vitals.',
        ];
    }

    /**
     * Analyze HTML content for performance metrics
     */
    protected function analyzeHtml(string $html): array
    {
        $doc = new \DOMDocument();
        @$doc->loadHTML($html, LIBXML_NOERROR);
        
        $images = $doc->getElementsByTagName('img');
        $scripts = $doc->getElementsByTagName('script');
        $links = $doc->getElementsByTagName('link');
        $styles = $doc->getElementsByTagName('style');

        $externalResources = 0;
        $inlineScripts = 0;
        $stylesheets = 0;

        // Count external scripts
        foreach ($scripts as $script) {
            if ($script->hasAttribute('src')) {
                $externalResources++;
            } else {
                $inlineScripts++;
            }
        }

        // Count stylesheets
        foreach ($links as $link) {
            if ($link->getAttribute('rel') === 'stylesheet') {
                $stylesheets++;
                $externalResources++;
            }
        }

        // Count images
        foreach ($images as $img) {
            if ($img->hasAttribute('src')) {
                $externalResources++;
            }
        }

        return [
            'images' => $images->length,
            'scripts' => $scripts->length,
            'styles' => $stylesheets,
            'inline_styles' => $styles->length,
            'inline_scripts' => $inlineScripts,
            'external_resources' => $externalResources,
        ];
    }

    /**
     * Analyze metrics and create issues
     */
    protected function analyzeMetrics(array $metrics): void
    {
        $targets = $this->config['targets'] ?? [];

        // Check load time
        if (isset($metrics['load_time']) && $metrics['load_time'] > 3000) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['load_time'] > 5000 ? 'critical' : 'warning',
                'category' => 'server_response',
                'title' => 'Slow page load time',
                'description' => "Page took {$metrics['load_time']}ms to load. Target is under 3000ms.",
                'suggestion' => 'Optimize server response time, enable caching, reduce resource size.',
                'impact_score' => min(1, $metrics['load_time'] / 5000),
            ]);
        }

        // Check page size
        if (isset($metrics['page_size']) && $metrics['page_size'] > 500000) { // 500KB
            $sizeKb = round($metrics['page_size'] / 1024);
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['page_size'] > 1000000 ? 'critical' : 'warning',
                'category' => 'page_size',
                'title' => 'Large page size',
                'description' => "Page size is {$sizeKb}KB. Recommended under 500KB.",
                'suggestion' => 'Compress images, minify CSS/JS, enable Gzip compression.',
                'impact_score' => min(1, $metrics['page_size'] / 1000000),
            ]);
        }

        // Check FCP
        if (isset($metrics['fcp']) && $metrics['fcp'] > ($targets['first_contentful_paint'] ?? 1800)) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['fcp'] > 3000 ? 'critical' : 'warning',
                'category' => 'fcp',
                'title' => 'Slow First Contentful Paint',
                'description' => "FCP is {$metrics['fcp']}ms. Target is under {$targets['first_contentful_paint']}ms.",
                'suggestion' => 'Eliminate render-blocking resources, inline critical CSS, preload key requests.',
                'impact_score' => min(1, $metrics['fcp'] / 4000),
            ]);
        }

        // Check LCP
        if (isset($metrics['lcp']) && $metrics['lcp'] > ($targets['largest_contentful_paint'] ?? 2500)) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['lcp'] > 4000 ? 'critical' : 'warning',
                'category' => 'lcp',
                'title' => 'Slow Largest Contentful Paint',
                'description' => "LCP is {$metrics['lcp']}ms. Target is under {$targets['largest_contentful_paint']}ms.",
                'suggestion' => 'Optimize and compress images, preload hero image, use CDN.',
                'impact_score' => min(1, $metrics['lcp'] / 5000),
            ]);
        }

        // Check CLS
        if (isset($metrics['cls']) && $metrics['cls'] > ($targets['cumulative_layout_shift'] ?? 0.1)) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['cls'] > 0.25 ? 'critical' : 'warning',
                'category' => 'cls',
                'title' => 'High Cumulative Layout Shift',
                'description' => "CLS is {$metrics['cls']}. Target is under {$targets['cumulative_layout_shift']}.",
                'suggestion' => 'Set explicit dimensions on images/videos, avoid inserting content above existing content.',
                'impact_score' => min(1, $metrics['cls'] / 0.5),
            ]);
        }

        // Check TBT
        if (isset($metrics['tbt']) && $metrics['tbt'] > ($targets['total_blocking_time'] ?? 200)) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => $metrics['tbt'] > 600 ? 'critical' : 'warning',
                'category' => 'tbt',
                'title' => 'High Total Blocking Time',
                'description' => "TBT is {$metrics['tbt']}ms. Target is under {$targets['total_blocking_time']}ms.",
                'suggestion' => 'Break up long JavaScript tasks, defer non-critical JS, remove unused code.',
                'impact_score' => min(1, $metrics['tbt'] / 1000),
            ]);
        }

        // Check inline styles
        if (isset($metrics['inline_styles']) && $metrics['inline_styles'] > 3) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => 'info',
                'category' => 'code_quality',
                'title' => 'Multiple inline style blocks',
                'description' => "Found {$metrics['inline_styles']} inline style blocks.",
                'suggestion' => 'Consolidate styles into external stylesheets for better caching.',
                'impact_score' => 0.3,
            ]);
        }

        // Check inline scripts
        if (isset($metrics['inline_scripts']) && $metrics['inline_scripts'] > 5) {
            $this->addIssue([
                'type' => 'performance',
                'severity' => 'info',
                'category' => 'code_quality',
                'title' => 'Multiple inline script blocks',
                'description' => "Found {$metrics['inline_scripts']} inline script blocks.",
                'suggestion' => 'Consolidate scripts into external files for better caching.',
                'impact_score' => 0.3,
            ]);
        }
    }

    /**
     * Add issue to the list
     */
    protected function addIssue(array $issue): void
    {
        $issue['status'] = 'open';
        $this->issues[] = $issue;
    }

    /**
     * Get database performance metrics
     */
    public function getDatabaseMetrics(): array
    {
        $startTime = microtime(true);
        
        // Simple query to test connection
        DB::select('SELECT 1');
        
        $queryTime = round((microtime(true) - $startTime) * 1000, 2);

        // Get slow query log if available
        $slowQueries = Cache::get('slow_queries', []);

        return [
            'connection_time' => $queryTime,
            'slow_queries_count' => count($slowQueries),
            'slow_queries' => array_slice($slowQueries, 0, 10),
        ];
    }

    /**
     * Get cache performance metrics
     */
    public function getCacheMetrics(): array
    {
        $cacheDriver = config('cache.default');
        
        // Test cache operations
        $startTime = microtime(true);
        Cache::put('_audit_test', 'test', 60);
        $writeTime = round((microtime(true) - $startTime) * 1000, 2);

        $startTime = microtime(true);
        Cache::get('_audit_test');
        $readTime = round((microtime(true) - $startTime) * 1000, 2);

        Cache::forget('_audit_test');

        return [
            'driver' => $cacheDriver,
            'write_time' => $writeTime,
            'read_time' => $readTime,
            'is_healthy' => $readTime < 10 && $writeTime < 10,
        ];
    }

    /**
     * Get all issues from last audit
     */
    public function getIssues(): array
    {
        return $this->issues;
    }
}
