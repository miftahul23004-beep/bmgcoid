<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CacheResponse
{
    /**
     * Routes to cache and their TTL in seconds
     */
    protected array $cacheableRoutes = [
        'home' => 300, // 5 minutes
    ];

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Only cache GET requests
        if ($request->method() !== 'GET') {
            return $next($request);
        }

        // Skip cache for authenticated users
        if (auth()->check()) {
            return $next($request);
        }

        // Get route name
        $routeName = $request->route()?->getName();
        
        // Check if route is cacheable
        if (!$routeName || !isset($this->cacheableRoutes[$routeName])) {
            return $next($request);
        }

        $ttl = $this->cacheableRoutes[$routeName];
        $locale = app()->getLocale();
        $cacheKey = "page_cache:{$routeName}:{$locale}";

        // Try to get cached response
        $cachedContent = Cache::get($cacheKey);
        
        if ($cachedContent) {
            return response($cachedContent)
                ->header('X-Cache', 'HIT')
                ->header('Content-Type', 'text/html; charset=UTF-8');
        }

        // Process request and cache response
        $response = $next($request);

        // Only cache successful HTML responses
        if ($response->getStatusCode() === 200 && 
            str_contains($response->headers->get('Content-Type', ''), 'text/html')) {
            Cache::put($cacheKey, $response->getContent(), $ttl);
            $response->headers->set('X-Cache', 'MISS');
        }

        return $response;
    }
}
