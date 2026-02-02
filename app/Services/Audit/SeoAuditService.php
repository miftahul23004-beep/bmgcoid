<?php

namespace App\Services\Audit;

use App\Models\AuditResult;
use App\Models\AuditIssue;
use App\Models\SeoPageAnalysis;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;

class SeoAuditService
{
    protected array $config;
    protected array $issues = [];
    protected ?string $html = null;
    protected ?\DOMDocument $dom = null;
    protected ?\DOMXPath $xpath = null;
    protected ?string $currentUrl = null;

    public function __construct()
    {
        $this->config = config('audit.seo', []);
    }

    /**
     * Run complete SEO audit for a URL
     */
    public function audit(string $url, string $pageType = 'page'): AuditResult
    {
        $this->issues = [];
        $this->currentUrl = $url;
        $startTime = microtime(true);

        // Fetch page content
        $this->fetchPage($url);

        if (!$this->html) {
            return $this->createErrorResult($url, $pageType, 'Failed to fetch page');
        }

        // Run all SEO checks
        $metaAnalysis = $this->analyzeMetaTags();
        $headingAnalysis = $this->analyzeHeadings();
        $imageAnalysis = $this->analyzeImages();
        $linkAnalysis = $this->analyzeLinks();
        $contentAnalysis = $this->analyzeContent();
        $structuredData = $this->analyzeStructuredData();
        $socialTags = $this->analyzeSocialTags();
        $technicalSeo = $this->analyzeTechnicalSeo($url);

        // Calculate SEO score
        $seoScore = $this->calculateSeoScore();

        $executionTime = round((microtime(true) - $startTime) * 1000);

        // Compile all analysis data
        $rawData = [
            'meta' => $metaAnalysis,
            'headings' => $headingAnalysis,
            'images' => $imageAnalysis,
            'links' => $linkAnalysis,
            'content' => $contentAnalysis,
            'structured_data' => $structuredData,
            'social_tags' => $socialTags,
            'technical' => $technicalSeo,
        ];

        // Save result
        $auditResult = AuditResult::create([
            'url' => $url,
            'page_type' => $pageType,
            'audit_type' => 'seo',
            'seo_score' => $seoScore,
            'raw_data' => $rawData,
            'source' => 'manual',
            'notes' => "SEO audit completed in {$executionTime}ms. Found " . count($this->issues) . " issues.",
        ]);

        // Save issues
        foreach ($this->issues as $issue) {
            $issue['audit_result_id'] = $auditResult->id;
            AuditIssue::create($issue);
        }

        // Also save to SeoPageAnalysis for tracking
        $this->saveSeoPageAnalysis($url, $pageType, $seoScore, $rawData);

        return $auditResult;
    }

    /**
     * Fetch page and parse HTML
     */
    protected function fetchPage(string $url): void
    {
        try {
            $http = Http::timeout(30)
                ->withHeaders([
                    'User-Agent' => 'BMG-SEO-Audit/1.0 (compatible; Googlebot/2.1)',
                    'Accept' => 'text/html',
                    'Accept-Language' => 'id-ID,id;q=0.9,en;q=0.8',
                ]);
            
            // Disable SSL verification for local URLs
            if (str_contains($url, 'localhost') || str_contains($url, '.test') || str_contains($url, '.local')) {
                $http = $http->withoutVerifying();
            }
            
            $response = $http->get($url);

            if ($response->successful()) {
                $this->html = $response->body();
                $this->dom = new \DOMDocument();
                @$this->dom->loadHTML($this->html, LIBXML_NOERROR);
                $this->xpath = new \DOMXPath($this->dom);
            }
        } catch (\Exception $e) {
            Log::error('SEO audit fetch failed', ['url' => $url, 'error' => $e->getMessage()]);
        }
    }

    /**
     * Analyze meta tags
     */
    protected function analyzeMetaTags(): array
    {
        $config = $this->config['checks'] ?? [];
        
        // Get title
        $titleTags = $this->xpath->query('//title');
        $title = $titleTags->length > 0 ? trim($titleTags->item(0)->textContent) : null;
        $titleLength = $title ? mb_strlen($title) : 0;

        // Get meta description
        $descriptionNode = $this->xpath->query('//meta[@name="description"]/@content');
        $description = $descriptionNode->length > 0 ? trim($descriptionNode->item(0)->textContent) : null;
        $descriptionLength = $description ? mb_strlen($description) : 0;

        // Get meta keywords
        $keywordsNode = $this->xpath->query('//meta[@name="keywords"]/@content');
        $keywords = $keywordsNode->length > 0 ? trim($keywordsNode->item(0)->textContent) : null;

        // Get canonical
        $canonicalNode = $this->xpath->query('//link[@rel="canonical"]/@href');
        $canonical = $canonicalNode->length > 0 ? trim($canonicalNode->item(0)->textContent) : null;

        // Get robots meta
        $robotsNode = $this->xpath->query('//meta[@name="robots"]/@content');
        $robots = $robotsNode->length > 0 ? trim($robotsNode->item(0)->textContent) : null;

        // Validate title
        if (!$title) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'critical',
                'category' => 'meta_title',
                'title' => 'Missing page title',
                'description' => 'The page does not have a title tag.',
                'suggestion' => 'Add a unique, descriptive title tag between 30-60 characters.',
                'impact_score' => 1.0,
            ]);
        } elseif ($titleLength < ($config['meta_title']['min_length'] ?? 30)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'meta_title',
                'title' => 'Title too short',
                'description' => "Title is {$titleLength} characters. Recommended minimum is 30.",
                'suggestion' => 'Expand your title to include more relevant keywords.',
                'element' => $title,
                'impact_score' => 0.6,
            ]);
        } elseif ($titleLength > ($config['meta_title']['max_length'] ?? 60)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'meta_title',
                'title' => 'Title too long',
                'description' => "Title is {$titleLength} characters. Recommended maximum is 60.",
                'suggestion' => 'Shorten your title. Search engines may truncate longer titles.',
                'element' => $title,
                'impact_score' => 0.4,
            ]);
        }

        // Validate description
        if (!$description) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'critical',
                'category' => 'meta_description',
                'title' => 'Missing meta description',
                'description' => 'The page does not have a meta description.',
                'suggestion' => 'Add a compelling meta description between 120-160 characters.',
                'impact_score' => 0.9,
            ]);
        } elseif ($descriptionLength < ($config['meta_description']['min_length'] ?? 120)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'meta_description',
                'title' => 'Meta description too short',
                'description' => "Description is {$descriptionLength} characters. Recommended minimum is 120.",
                'suggestion' => 'Expand your description to better describe the page content.',
                'element' => $description,
                'impact_score' => 0.5,
            ]);
        } elseif ($descriptionLength > ($config['meta_description']['max_length'] ?? 160)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'meta_description',
                'title' => 'Meta description too long',
                'description' => "Description is {$descriptionLength} characters. Recommended maximum is 160.",
                'suggestion' => 'Shorten your description. Search engines may truncate longer descriptions.',
                'element' => $description,
                'impact_score' => 0.3,
            ]);
        }

        // Check canonical
        if (!$canonical && ($config['canonical_url'] ?? true)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'canonical',
                'title' => 'Missing canonical URL',
                'description' => 'No canonical URL is specified.',
                'suggestion' => 'Add a canonical link to prevent duplicate content issues.',
                'impact_score' => 0.5,
            ]);
        }

        return [
            'title' => $title,
            'title_length' => $titleLength,
            'description' => $description,
            'description_length' => $descriptionLength,
            'keywords' => $keywords,
            'canonical' => $canonical,
            'robots' => $robots,
        ];
    }

    /**
     * Analyze heading structure
     */
    protected function analyzeHeadings(): array
    {
        $headings = [];
        
        for ($i = 1; $i <= 6; $i++) {
            $nodes = $this->xpath->query("//h{$i}");
            $headings["h{$i}"] = [];
            
            foreach ($nodes as $node) {
                $headings["h{$i}"][] = trim($node->textContent);
            }
        }

        // Check H1
        $h1Count = count($headings['h1']);
        if ($h1Count === 0) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'critical',
                'category' => 'headings',
                'title' => 'Missing H1 heading',
                'description' => 'The page does not have an H1 heading.',
                'suggestion' => 'Add a single H1 heading that describes the main content of the page.',
                'impact_score' => 0.9,
            ]);
        } elseif ($h1Count > 1) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'headings',
                'title' => 'Multiple H1 headings',
                'description' => "Found {$h1Count} H1 headings. Best practice is to have only one.",
                'suggestion' => 'Use only one H1 heading per page for better SEO.',
                'impact_score' => 0.4,
            ]);
        }

        // Check heading hierarchy
        $hasH2 = count($headings['h2']) > 0;
        $hasH3 = count($headings['h3']) > 0;
        
        if ($hasH3 && !$hasH2) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'headings',
                'title' => 'Skipped heading level',
                'description' => 'H3 is used without H2. This breaks heading hierarchy.',
                'suggestion' => 'Maintain proper heading hierarchy (H1 > H2 > H3).',
                'impact_score' => 0.3,
            ]);
        }

        return $headings;
    }

    /**
     * Analyze images
     */
    protected function analyzeImages(): array
    {
        $images = $this->xpath->query('//img');
        $analysis = [
            'total' => $images->length,
            'missing_alt' => 0,
            'empty_alt' => 0,
            'missing_dimensions' => 0,
            'external' => 0,
            'lazy_loaded' => 0,
        ];

        $missingAltImages = [];

        foreach ($images as $img) {
            $src = $img->getAttribute('src');
            $alt = $img->getAttribute('alt');
            $width = $img->getAttribute('width');
            $height = $img->getAttribute('height');
            $loading = $img->getAttribute('loading');

            if (!$img->hasAttribute('alt')) {
                $analysis['missing_alt']++;
                $missingAltImages[] = $src;
            } elseif (empty($alt)) {
                $analysis['empty_alt']++;
            }

            if (empty($width) || empty($height)) {
                $analysis['missing_dimensions']++;
            }

            if (Str::startsWith($src, ['http://', 'https://'])) {
                $analysis['external']++;
            }

            if ($loading === 'lazy') {
                $analysis['lazy_loaded']++;
            }
        }

        // Report missing alt tags
        if ($analysis['missing_alt'] > 0) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'images',
                'title' => 'Images missing alt attribute',
                'description' => "{$analysis['missing_alt']} images are missing alt attributes.",
                'suggestion' => 'Add descriptive alt text to all images for accessibility and SEO.',
                'element' => implode(', ', array_slice($missingAltImages, 0, 5)),
                'impact_score' => min(1, $analysis['missing_alt'] * 0.1),
            ]);
        }

        // Report missing dimensions
        if ($analysis['missing_dimensions'] > 3) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'images',
                'title' => 'Images missing dimensions',
                'description' => "{$analysis['missing_dimensions']} images don't have explicit width/height.",
                'suggestion' => 'Set explicit dimensions to prevent layout shifts (improves CLS).',
                'impact_score' => 0.4,
            ]);
        }

        return $analysis;
    }

    /**
     * Analyze links
     */
    protected function analyzeLinks(): array
    {
        $links = $this->xpath->query('//a[@href]');
        $analysis = [
            'total' => $links->length,
            'internal' => 0,
            'external' => 0,
            'nofollow' => 0,
            'empty_text' => 0,
            'broken' => [],
        ];

        $emptyLinks = [];
        
        // Parse the current URL to get the host
        $currentHost = parse_url($this->currentUrl, PHP_URL_HOST);

        foreach ($links as $link) {
            $href = $link->getAttribute('href');
            $text = trim($link->textContent);
            $rel = $link->getAttribute('rel');

            // Skip anchors and javascript
            if (Str::startsWith($href, ['#', 'javascript:', 'mailto:', 'tel:'])) {
                continue;
            }

            // Check if internal or external link
            if (Str::startsWith($href, ['http://', 'https://'])) {
                // Parse the href to get the host
                $linkHost = parse_url($href, PHP_URL_HOST);
                if ($linkHost === $currentHost || $linkHost === 'www.' . $currentHost || 'www.' . $linkHost === $currentHost) {
                    $analysis['internal']++;
                } else {
                    $analysis['external']++;
                }
            } else {
                // Relative URL = internal
                $analysis['internal']++;
            }

            if (Str::contains($rel, 'nofollow')) {
                $analysis['nofollow']++;
            }

            // Check if link has accessible content (text, img, svg, or aria-label)
            $hasAriaLabel = !empty($link->getAttribute('aria-label'));
            $hasTitle = !empty($link->getAttribute('title'));
            $hasImage = $link->getElementsByTagName('img')->length > 0;
            $hasSvg = $link->getElementsByTagName('svg')->length > 0;
            
            if (empty($text) && !$hasImage && !$hasSvg && !$hasAriaLabel && !$hasTitle) {
                $analysis['empty_text']++;
                $emptyLinks[] = $href;
            }
        }

        // Report empty link text
        if ($analysis['empty_text'] > 0) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'links',
                'title' => 'Links with empty anchor text',
                'description' => "{$analysis['empty_text']} links have no visible text.",
                'suggestion' => 'Add descriptive anchor text to all links for accessibility and SEO.',
                'element' => implode(', ', array_slice($emptyLinks, 0, 5)),
                'impact_score' => min(1, $analysis['empty_text'] * 0.15),
            ]);
        }

        // Check for internal linking
        if ($analysis['internal'] < 3) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'links',
                'title' => 'Few internal links',
                'description' => "Only {$analysis['internal']} internal links found.",
                'suggestion' => 'Add more internal links to help users and search engines navigate your site.',
                'impact_score' => 0.3,
            ]);
        }

        return $analysis;
    }

    /**
     * Analyze content quality
     */
    protected function analyzeContent(): array
    {
        // Get text content (exclude scripts, styles)
        $body = $this->xpath->query('//body');
        if ($body->length === 0) {
            return ['word_count' => 0];
        }

        $text = $body->item(0)->textContent;
        
        // Remove extra whitespace
        $text = preg_replace('/\s+/', ' ', $text);
        $text = trim($text);
        
        $wordCount = str_word_count($text);
        $charCount = mb_strlen($text);

        $analysis = [
            'word_count' => $wordCount,
            'char_count' => $charCount,
            'reading_time' => ceil($wordCount / 200), // minutes
        ];

        // Check word count
        if ($wordCount < 300) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'content',
                'title' => 'Thin content',
                'description' => "Page has only {$wordCount} words. Recommended minimum is 300.",
                'suggestion' => 'Add more valuable content to improve SEO and user engagement.',
                'impact_score' => 0.6,
            ]);
        }

        return $analysis;
    }

    /**
     * Analyze structured data (JSON-LD)
     */
    protected function analyzeStructuredData(): array
    {
        $scripts = $this->xpath->query('//script[@type="application/ld+json"]');
        $schemas = [];

        foreach ($scripts as $script) {
            try {
                $data = json_decode($script->textContent, true);
                if ($data) {
                    $type = $data['@type'] ?? 'Unknown';
                    $schemas[] = [
                        'type' => $type,
                        'valid' => true,
                    ];
                }
            } catch (\Exception $e) {
                $schemas[] = [
                    'type' => 'Invalid',
                    'valid' => false,
                    'error' => $e->getMessage(),
                ];
            }
        }

        if (empty($schemas)) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'structured_data',
                'title' => 'No structured data found',
                'description' => 'No JSON-LD structured data detected on this page.',
                'suggestion' => 'Add structured data (Organization, Product, Article) to enhance search results.',
                'impact_score' => 0.4,
            ]);
        }

        return [
            'count' => count($schemas),
            'schemas' => $schemas,
        ];
    }

    /**
     * Analyze social media tags
     */
    protected function analyzeSocialTags(): array
    {
        // Open Graph tags
        $ogTitle = $this->xpath->query('//meta[@property="og:title"]/@content');
        $ogDescription = $this->xpath->query('//meta[@property="og:description"]/@content');
        $ogImage = $this->xpath->query('//meta[@property="og:image"]/@content');
        $ogUrl = $this->xpath->query('//meta[@property="og:url"]/@content');

        // Twitter Card tags
        $twitterCard = $this->xpath->query('//meta[@name="twitter:card"]/@content');
        $twitterTitle = $this->xpath->query('//meta[@name="twitter:title"]/@content');

        $hasOg = $ogTitle->length > 0 && $ogImage->length > 0;
        $hasTwitter = $twitterCard->length > 0;

        if (!$hasOg) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'social',
                'title' => 'Missing Open Graph tags',
                'description' => 'Open Graph meta tags are missing or incomplete.',
                'suggestion' => 'Add og:title, og:description, og:image for better social media sharing.',
                'impact_score' => 0.3,
            ]);
        }

        if (!$hasTwitter) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'social',
                'title' => 'Missing Twitter Card tags',
                'description' => 'Twitter Card meta tags are missing.',
                'suggestion' => 'Add twitter:card, twitter:title for better Twitter sharing.',
                'impact_score' => 0.2,
            ]);
        }

        return [
            'open_graph' => [
                'title' => $ogTitle->length > 0 ? $ogTitle->item(0)->textContent : null,
                'description' => $ogDescription->length > 0 ? $ogDescription->item(0)->textContent : null,
                'image' => $ogImage->length > 0 ? $ogImage->item(0)->textContent : null,
                'url' => $ogUrl->length > 0 ? $ogUrl->item(0)->textContent : null,
            ],
            'twitter' => [
                'card' => $twitterCard->length > 0 ? $twitterCard->item(0)->textContent : null,
                'title' => $twitterTitle->length > 0 ? $twitterTitle->item(0)->textContent : null,
            ],
        ];
    }

    /**
     * Analyze technical SEO aspects
     */
    protected function analyzeTechnicalSeo(string $url): array
    {
        $baseUrl = parse_url($url, PHP_URL_SCHEME) . '://' . parse_url($url, PHP_URL_HOST);
        
        // Check robots.txt
        $robotsTxt = $this->checkRobotsTxt($baseUrl);
        
        // Check sitemap
        $sitemap = $this->checkSitemap($baseUrl);
        
        // Check viewport
        $viewport = $this->xpath->query('//meta[@name="viewport"]/@content');
        $hasViewport = $viewport->length > 0;

        // Check language
        $htmlLang = $this->xpath->query('//html/@lang');
        $hasLang = $htmlLang->length > 0;

        // Check charset
        $charset = $this->xpath->query('//meta[@charset]');
        $hasCharset = $charset->length > 0;

        if (!$hasViewport) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'warning',
                'category' => 'technical',
                'title' => 'Missing viewport meta tag',
                'description' => 'No viewport meta tag found.',
                'suggestion' => 'Add <meta name="viewport" content="width=device-width, initial-scale=1">',
                'impact_score' => 0.5,
            ]);
        }

        if (!$hasLang) {
            $this->addIssue([
                'type' => 'seo',
                'severity' => 'info',
                'category' => 'technical',
                'title' => 'Missing HTML lang attribute',
                'description' => 'The HTML tag does not have a lang attribute.',
                'suggestion' => 'Add lang="id" to the HTML tag for Indonesian content.',
                'impact_score' => 0.3,
            ]);
        }

        return [
            'robots_txt' => $robotsTxt,
            'sitemap' => $sitemap,
            'has_viewport' => $hasViewport,
            'has_lang' => $hasLang,
            'lang' => $hasLang ? $htmlLang->item(0)->textContent : null,
            'has_charset' => $hasCharset,
        ];
    }

    /**
     * Check robots.txt
     */
    protected function checkRobotsTxt(string $baseUrl): array
    {
        try {
            $response = Http::timeout(10)->get("{$baseUrl}/robots.txt");
            return [
                'exists' => $response->successful(),
                'content' => $response->successful() ? $response->body() : null,
            ];
        } catch (\Exception $e) {
            return ['exists' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Check sitemap
     */
    protected function checkSitemap(string $baseUrl): array
    {
        $sitemapUrls = [
            "{$baseUrl}/sitemap.xml",
            "{$baseUrl}/sitemap_index.xml",
        ];

        foreach ($sitemapUrls as $url) {
            try {
                $response = Http::timeout(10)
                    ->withOptions(['verify' => false])
                    ->get($url);
                if ($response->successful()) {
                    $content = $response->body();
                    // Verify it's actually XML
                    if (str_contains($content, '<?xml') || str_contains($content, '<urlset') || str_contains($content, '<sitemapindex')) {
                        return [
                            'exists' => true,
                            'url' => $url,
                        ];
                    }
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        $this->addIssue([
            'type' => 'seo',
            'severity' => 'warning',
            'category' => 'technical',
            'title' => 'Missing XML sitemap',
            'description' => 'No XML sitemap found at standard locations.',
            'suggestion' => 'Create and submit a sitemap.xml to help search engines index your pages.',
            'impact_score' => 0.5,
        ]);

        return ['exists' => false];
    }

    /**
     * Calculate overall SEO score
     */
    protected function calculateSeoScore(): int
    {
        // Start with 100 and deduct based on issues
        $score = 100;
        
        foreach ($this->issues as $issue) {
            $deduction = match ($issue['severity']) {
                'critical' => 15,
                'warning' => 8,
                'info' => 3,
                default => 0,
            };
            $score -= $deduction;
        }

        return max(0, min(100, $score));
    }

    /**
     * Save SEO page analysis
     */
    protected function saveSeoPageAnalysis(string $url, string $pageType, int $score, array $data): void
    {
        SeoPageAnalysis::updateOrCreate(
            ['url' => $url],
            [
                'page_type' => $pageType,
                'score' => $score,
                'meta_title' => $data['meta']['title'] ?? null,
                'meta_description' => $data['meta']['description'] ?? null,
                'h1_count' => count($data['headings']['h1'] ?? []),
                'image_count' => $data['images']['total'] ?? 0,
                'images_without_alt' => $data['images']['missing_alt'] ?? 0,
                'internal_links' => $data['links']['internal'] ?? 0,
                'external_links' => $data['links']['external'] ?? 0,
                'word_count' => $data['content']['word_count'] ?? 0,
                'has_structured_data' => ($data['structured_data']['count'] ?? 0) > 0,
                'has_open_graph' => !empty($data['social_tags']['open_graph']['title']),
                'has_twitter_card' => !empty($data['social_tags']['twitter']['card']),
                'issues' => $this->issues,
                'analyzed_at' => now(),
            ]
        );
    }

    /**
     * Create error result
     */
    protected function createErrorResult(string $url, string $pageType, string $error): AuditResult
    {
        return AuditResult::create([
            'url' => $url,
            'page_type' => $pageType,
            'audit_type' => 'seo',
            'seo_score' => 0,
            'raw_data' => ['error' => $error],
            'source' => 'manual',
            'notes' => "Audit failed: {$error}",
        ]);
    }

    /**
     * Add issue
     */
    protected function addIssue(array $issue): void
    {
        $issue['status'] = 'open';
        $this->issues[] = $issue;
    }

    /**
     * Audit all main pages
     */
    public function auditAllPages(string $baseUrl): array
    {
        $pages = [
            ['url' => $baseUrl, 'type' => 'home'],
            ['url' => "{$baseUrl}/about", 'type' => 'about'],
            ['url' => "{$baseUrl}/products", 'type' => 'product_list'],
            ['url' => "{$baseUrl}/articles", 'type' => 'article_list'],
            ['url' => "{$baseUrl}/contact", 'type' => 'contact'],
        ];

        $results = [];
        foreach ($pages as $page) {
            $results[] = $this->audit($page['url'], $page['type']);
        }

        return $results;
    }
}
