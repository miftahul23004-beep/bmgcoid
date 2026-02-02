<?php

namespace App\Http\Middleware;

use App\Models\PerformanceLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PerformanceLogging
{
    /**
     * Routes/patterns to exclude from logging
     */
    protected array $excludePatterns = [
        'livewire/*',
        '_debugbar/*',
        'filament/*',
        'admin/*',
        'api/*',
        'sanctum/*',
        'storage/*',
        '*.js',
        '*.css',
        '*.ico',
        '*.png',
        '*.jpg',
        '*.gif',
        '*.svg',
        '*.woff*',
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip logging for excluded routes
        if ($this->shouldExclude($request)) {
            return $next($request);
        }

        // Start timing
        $startTime = microtime(true);
        $startMemory = memory_get_usage();

        // Process request
        $response = $next($request);

        // Calculate metrics
        $endTime = microtime(true);
        $endMemory = memory_get_usage();
        $peakMemory = memory_get_peak_usage();

        // Skip if response is not HTML
        $contentType = $response->headers->get('Content-Type', '');
        if (!str_contains($contentType, 'text/html')) {
            return $response;
        }

        // Log performance data
        $this->logPerformance($request, $response, [
            'response_time' => ($endTime - $startTime) * 1000, // Convert to ms
            'memory_usage' => $endMemory - $startMemory,
            'peak_memory' => $peakMemory,
            'status_code' => $response->getStatusCode(),
        ]);

        return $response;
    }

    /**
     * Check if the request should be excluded from logging
     */
    protected function shouldExclude(Request $request): bool
    {
        $path = $request->path();

        foreach ($this->excludePatterns as $pattern) {
            if (fnmatch($pattern, $path)) {
                return true;
            }
        }

        // Skip non-GET requests (we only monitor page loads)
        if (!$request->isMethod('GET')) {
            return true;
        }

        // Skip AJAX requests
        if ($request->ajax()) {
            return true;
        }

        return false;
    }

    /**
     * Log performance metrics to database
     */
    protected function logPerformance(Request $request, Response $response, array $metrics): void
    {
        try {
            // Calculate content size
            $content = $response->getContent();
            $contentSize = strlen($content);

            // Count database queries if available
            $queryCount = 0;
            $queryTime = 0;
            if (app()->bound('db')) {
                $queryLog = \DB::getQueryLog();
                $queryCount = count($queryLog);
                $queryTime = collect($queryLog)->sum('time');
            }

            // Determine page type from route
            $pageType = $this->determinePageType($request);

            // Calculate performance score based on response time
            $performanceScore = $this->calculatePerformanceScore($metrics['response_time'], $contentSize);

            PerformanceLog::create([
                'url' => $request->fullUrl(),
                'route_name' => $request->route()?->getName() ?? $pageType,
                'method' => $request->method(),
                'response_time' => round($metrics['response_time'] / 1000, 4), // Convert to seconds
                'memory_usage' => $metrics['memory_usage'],
                'query_count' => $queryCount,
                'query_time' => round($queryTime / 1000, 4), // Convert to seconds
                'status_code' => $metrics['status_code'],
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
                'extra_data' => [
                    'page_type' => $pageType,
                    'page_size' => $contentSize,
                    'peak_memory' => $metrics['peak_memory'],
                    'performance_score' => $performanceScore,
                    'server_timing' => [
                        'total_ms' => round($metrics['response_time'], 2),
                        'query_ms' => round($queryTime, 2),
                        'processing_ms' => round($metrics['response_time'] - $queryTime, 2),
                    ],
                ],
            ]);

            // Check for performance alerts
            $this->checkPerformanceAlerts($metrics['response_time'], $contentSize, $request->fullUrl());

        } catch (\Exception $e) {
            // Silently fail - don't break the response
            \Log::warning('Performance logging failed: ' . $e->getMessage());
        }
    }

    /**
     * Determine the page type from the request/route
     */
    protected function determinePageType(Request $request): string
    {
        $route = $request->route();
        
        if (!$route) {
            return 'unknown';
        }

        $routeName = $route->getName() ?? '';
        $path = $request->path();

        // Map route names to page types
        $routeMapping = [
            'home' => 'home',
            'products.index' => 'product_list',
            'products.show' => 'product_detail',
            'products.category' => 'product_list',
            'articles.index' => 'article_list',
            'articles.show' => 'article_detail',
            'articles.category' => 'article_list',
            'about' => 'about',
            'contact' => 'contact',
            'page' => 'page',
        ];

        if (isset($routeMapping[$routeName])) {
            return $routeMapping[$routeName];
        }

        // Fallback: determine from path
        if ($path === '' || $path === '/') {
            return 'home';
        }

        if (str_starts_with($path, 'products/')) {
            return 'product_detail';
        }

        if ($path === 'products') {
            return 'product_list';
        }

        if (str_starts_with($path, 'articles/')) {
            return 'article_detail';
        }

        if ($path === 'articles') {
            return 'article_list';
        }

        return 'page';
    }

    /**
     * Calculate a simple performance score
     */
    protected function calculatePerformanceScore(float $responseTime, int $pageSize): int
    {
        $score = 100;

        // Penalize slow response times
        if ($responseTime > 3000) {
            $score -= 50;
        } elseif ($responseTime > 2000) {
            $score -= 30;
        } elseif ($responseTime > 1000) {
            $score -= 15;
        } elseif ($responseTime > 500) {
            $score -= 5;
        }

        // Penalize large page sizes (over 1MB)
        if ($pageSize > 1024 * 1024) {
            $score -= 20;
        } elseif ($pageSize > 512 * 1024) {
            $score -= 10;
        } elseif ($pageSize > 256 * 1024) {
            $score -= 5;
        }

        return max(0, min(100, $score));
    }

    /**
     * Check and trigger performance alerts
     */
    protected function checkPerformanceAlerts(float $responseTime, int $pageSize, string $url): void
    {
        $thresholds = config('audit.targets', []);
        
        // Alert on slow responses
        $maxResponseTime = $thresholds['response_time'] ?? 2000;
        if ($responseTime > $maxResponseTime) {
            \Log::warning('Slow page response detected', [
                'url' => $url,
                'response_time' => $responseTime,
                'threshold' => $maxResponseTime,
            ]);
        }

        // Alert on large page sizes
        $maxPageSize = $thresholds['page_size'] ?? 500 * 1024;
        if ($pageSize > $maxPageSize) {
            \Log::warning('Large page size detected', [
                'url' => $url,
                'page_size' => $pageSize,
                'threshold' => $maxPageSize,
            ]);
        }
    }

    /**
     * Format bytes to human readable string
     */
    protected function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $unitIndex = 0;

        while ($bytes >= 1024 && $unitIndex < count($units) - 1) {
            $bytes /= 1024;
            $unitIndex++;
        }

        return round($bytes, 2) . ' ' . $units[$unitIndex];
    }
}
