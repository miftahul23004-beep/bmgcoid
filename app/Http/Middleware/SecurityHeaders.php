<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Security headers to add to all responses
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Only add headers to HTML responses
        if (!$this->isHtmlResponse($response)) {
            return $response;
        }

        // X-Content-Type-Options - Prevent MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // X-Frame-Options - Prevent clickjacking
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');

        // X-XSS-Protection - Enable XSS filter
        $response->headers->set('X-XSS-Protection', '1; mode=block');

        // Referrer-Policy - Control referrer information
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');

        // Permissions-Policy - Disable unnecessary features
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        // Content-Security-Policy - Basic CSP
        $cspDirectives = [
            "default-src 'self'",
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://www.youtube.com https://www.google.com https://www.googletagmanager.com https://www.google-analytics.com https://static.cloudflareinsights.com https://challenges.cloudflare.com",
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",
            "img-src 'self' data: https: http: blob:",
            "font-src 'self' https://fonts.gstatic.com",
            "frame-src 'self' https://www.youtube.com https://www.google.com https://challenges.cloudflare.com",
            "connect-src 'self' https://www.google-analytics.com https://cloudflareinsights.com wss: ws:",
            "media-src 'self' https: http:",
            "object-src 'none'",
            "base-uri 'self'",
            "form-action 'self'",
        ];
        
        // Only add upgrade-insecure-requests when using HTTPS
        if (request()->secure()) {
            $cspDirectives[] = "upgrade-insecure-requests";
        }
        
        $response->headers->set('Content-Security-Policy', implode('; ', $cspDirectives));

        // Strict-Transport-Security (HSTS) - Add when using HTTPS
        if (request()->secure()) {
            // Use shorter max-age for local development
            $maxAge = app()->isProduction() ? 31536000 : 86400; // 1 year in prod, 1 day in dev
            $response->headers->set('Strict-Transport-Security', "max-age={$maxAge}; includeSubDomains");
        }

        // Remove server version disclosure
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');

        return $response;
    }

    /**
     * Check if response is HTML
     */
    protected function isHtmlResponse(Response $response): bool
    {
        $contentType = $response->headers->get('Content-Type', '');
        return str_contains($contentType, 'text/html') || empty($contentType);
    }
}
