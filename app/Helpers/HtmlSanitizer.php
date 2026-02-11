<?php

namespace App\Helpers;

class HtmlSanitizer
{
    /**
     * Allowed HTML tags for article content
     */
    protected static array $allowedTags = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'strike',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6',
        'ul', 'ol', 'li',
        'a', 'img',
        'blockquote', 'pre', 'code',
        'table', 'caption', 'thead', 'tbody', 'tr', 'th', 'td',
        'hr', 'figure', 'figcaption',
        'div', 'span',
    ];

    /**
     * Allowed attributes per tag
     */
    protected static array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height', 'loading', 'class'],
        'div' => ['class', 'id'],
        'span' => ['class', 'id'],
        'p' => ['class'],
        'h1' => ['class', 'id'],
        'h2' => ['class', 'id'],
        'h3' => ['class', 'id'],
        'h4' => ['class', 'id'],
        'h5' => ['class', 'id'],
        'h6' => ['class', 'id'],
        'table' => ['class'],
        'th' => ['class', 'colspan', 'rowspan'],
        'td' => ['class', 'colspan', 'rowspan'],
        'blockquote' => ['class', 'cite'],
        'pre' => ['class'],
        'code' => ['class'],
        'ul' => ['class'],
        'ol' => ['class', 'start', 'type'],
        'li' => ['class'],
        'figure' => ['class'],
        'figcaption' => ['class'],
    ];

    /**
     * Sanitize HTML content
     */
    public static function sanitize(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // Remove script tags and their contents
        $html = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $html);
        
        // Remove style tags and their contents
        $html = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $html);
        
        // Remove event handlers (onclick, onload, onerror, etc.)
        $html = preg_replace('/\s+on\w+\s*=\s*["\'][^"\']*["\']/i', '', $html);
        $html = preg_replace('/\s+on\w+\s*=\s*[^\s>]*/i', '', $html);
        
        // Remove javascript: urls
        $html = preg_replace('/href\s*=\s*["\']?\s*javascript:[^"\'>\s]*/i', 'href="#"', $html);
        $html = preg_replace('/src\s*=\s*["\']?\s*javascript:[^"\'>\s]*/i', 'src=""', $html);
        
        // Remove data: urls in src (except data:image for base64 images)
        $html = preg_replace('/src\s*=\s*["\']?\s*data:(?!image\/)[^"\'>\s]*/i', 'src=""', $html);
        
        // Remove vbscript: urls
        $html = preg_replace('/href\s*=\s*["\']?\s*vbscript:[^"\'>\s]*/i', 'href="#"', $html);
        
        // Add rel="noopener noreferrer" and target="_blank" to external links
        $html = preg_replace_callback(
            '/<a\s+([^>]*href\s*=\s*["\']https?:\/\/[^"\']+["\'][^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];
                
                // Check if it's external (not our domain)
                $siteUrl = parse_url(config('app.url'), PHP_URL_HOST);
                if (!preg_match('/href\s*=\s*["\'][^"\']*' . preg_quote($siteUrl, '/') . '/i', $attrs)) {
                    // Add target and rel if not present
                    if (!preg_match('/target\s*=/i', $attrs)) {
                        $attrs .= ' target="_blank"';
                    }
                    if (!preg_match('/rel\s*=/i', $attrs)) {
                        $attrs .= ' rel="noopener noreferrer"';
                    }
                }
                
                return '<a ' . $attrs . '>';
            },
            $html
        );

        // Add loading="lazy" to images if not present
        $html = preg_replace_callback(
            '/<img\s+([^>]*)>/i',
            function ($matches) {
                $attrs = $matches[1];
                if (!preg_match('/loading\s*=/i', $attrs)) {
                    $attrs .= ' loading="lazy"';
                }
                return '<img ' . $attrs . '>';
            },
            $html
        );

        // Add <caption> to tables that don't have one
        $html = preg_replace_callback(
            '/<table(\s[^>]*)?>(?!\s*<caption)/i',
            function ($matches) {
                $attrs = $matches[1] ?? '';
                return '<table' . $attrs . '><caption class="sr-only">Data Table</caption>';
            },
            $html
        );

        // Wrap first row with <th> in <thead> if table has no <thead>
        $html = preg_replace_callback(
            '/<table([^>]*)>((?:(?!<thead).)*?)(<tr[^>]*>\s*(?:<th[\s>]).*?<\/tr>)/is',
            function ($matches) {
                $tableAttrs = $matches[1];
                $beforeFirstRow = $matches[2];
                $firstRow = $matches[3];
                return '<table' . $tableAttrs . '>' . $beforeFirstRow . '<thead>' . $firstRow . '</thead>';
            },
            $html
        );

        return $html;
    }

    /**
     * Strip all HTML tags except allowed ones
     */
    public static function stripDangerous(?string $html): string
    {
        if (empty($html)) {
            return '';
        }

        // First sanitize
        $html = self::sanitize($html);

        // Build allowed tags string
        $allowedTagsStr = '<' . implode('><', self::$allowedTags) . '>';

        return strip_tags($html, $allowedTagsStr);
    }
}
