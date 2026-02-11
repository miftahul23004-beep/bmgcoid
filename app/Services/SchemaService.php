<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class SchemaService
{
    protected SettingService $settingService;

    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }

    /**
     * Generate Organization Schema
     */
    public function getOrganizationSchema(): array
    {
        $companyInfo = $this->settingService->getCompanyInfo();
        $socialLinks = $this->settingService->getSocialLinks();

        $logo = !empty($companyInfo['logo']) 
            ? url(Storage::url($companyInfo['logo'])) 
            : asset('images/logo.png');

        $sameAs = collect($socialLinks)
            ->filter(fn($url) => !empty($url) && filter_var($url, FILTER_VALIDATE_URL))
            ->values()
            ->toArray();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Organization',
            'name' => $companyInfo['company_name'] ?? config('app.name'),
            'description' => $companyInfo['company_description'] ?? '',
            'url' => config('app.url'),
            'logo' => $logo,
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => $companyInfo['phone'] ?? '',
                'contactType' => 'customer service',
                'email' => $companyInfo['email'] ?? '',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
            'sameAs' => $sameAs,
        ];
    }

    /**
     * Generate LocalBusiness Schema with Local SEO optimization
     */
    public function getLocalBusinessSchema(): array
    {
        $companyInfo = $this->settingService->getCompanyInfo();
        $localConfig = config('seo.local', []);
        $serviceAreas = $localConfig['service_areas'] ?? ['Surabaya', 'Sidoarjo', 'Gresik', 'Mojokerto', 'Jombang'];

        $logo = !empty($companyInfo['logo']) 
            ? url(Storage::url($companyInfo['logo'])) 
            : asset('images/logo.png');

        $primaryCity = $localConfig['primary_city'] ?? 'Surabaya';
        $province = $localConfig['province'] ?? 'Jawa Timur';
        $nationalRegions = $localConfig['national_regions'] ?? [];

        $address = [
            '@type' => 'PostalAddress',
            'streetAddress' => $companyInfo['address'] ?? '',
            'addressLocality' => $primaryCity,
            'addressRegion' => $province,
            'postalCode' => $companyInfo['postal_code'] ?? '',
            'addressCountry' => 'ID',
        ];

        // Build areaServed with cities, province, and national coverage
        $areaServed = array_map(function($city) {
            return [
                '@type' => 'City',
                'name' => $city,
            ];
        }, $serviceAreas);
        
        // Add province level
        $areaServed[] = [
            '@type' => 'AdministrativeArea',
            'name' => $province,
        ];

        // Add national regions if national coverage is enabled
        if ($localConfig['national_coverage'] ?? false) {
            foreach ($nationalRegions as $region) {
                $areaServed[] = [
                    '@type' => 'AdministrativeArea',
                    'name' => $region,
                ];
            }
            // Add country level
            $areaServed[] = [
                '@type' => 'Country',
                'name' => 'Indonesia',
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => ['LocalBusiness', 'WholesaleStore'],
            '@id' => config('app.url') . '/#localbusiness',
            'name' => $companyInfo['company_name'] ?? config('app.name'),
            'description' => 'Supplier dan distributor besi baja terpercaya di Indonesia. Berbasis di ' . $primaryCity . ', melayani partai besar & eceran ke seluruh Indonesia.',
            'url' => config('app.url'),
            'logo' => $logo,
            'image' => $logo,
            'telephone' => $companyInfo['phone'] ?? '',
            'email' => $companyInfo['email'] ?? '',
            'address' => $address,
            'geo' => [
                '@type' => 'GeoCoordinates',
                'latitude' => $localConfig['geo']['latitude'] ?? '-7.2575',
                'longitude' => $localConfig['geo']['longitude'] ?? '112.7521',
            ],
            'areaServed' => $areaServed,
            'priceRange' => '$-$$$',
            'currenciesAccepted' => 'IDR',
            'paymentAccepted' => 'Cash, Bank Transfer, Credit',
            'openingHoursSpecification' => [
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
                    'opens' => '08:00',
                    'closes' => '17:00',
                ],
                [
                    '@type' => 'OpeningHoursSpecification',
                    'dayOfWeek' => 'Saturday',
                    'opens' => '08:00',
                    'closes' => '14:00',
                ],
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name' => 'Katalog Produk Besi Baja',
                'itemListElement' => [
                    ['@type' => 'OfferCatalog', 'name' => 'Besi Beton'],
                    ['@type' => 'OfferCatalog', 'name' => 'Besi Hollow'],
                    ['@type' => 'OfferCatalog', 'name' => 'Besi Siku'],
                    ['@type' => 'OfferCatalog', 'name' => 'Plat Besi'],
                    ['@type' => 'OfferCatalog', 'name' => 'Pipa Besi'],
                ],
            ],
        ];
    }

    /**
     * Generate Product Schema
     */
    public function getProductSchema(array $product, ?array $category = null): array
    {
        $companyInfo = $this->settingService->getCompanyInfo();

        $image = !empty($product['primary_image']) 
            ? url(Storage::url($product['primary_image'])) 
            : asset('images/product-placeholder.png');

        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'Product',
            'name' => $product['name'] ?? '',
            'description' => strip_tags($product['description'] ?? $product['short_description'] ?? ''),
            'image' => $image,
            'url' => route('products.show', $product['slug'] ?? ''),
            'sku' => $product['sku'] ?? $product['slug'] ?? '',
            'brand' => [
                '@type' => 'Brand',
                'name' => $companyInfo['company_name'] ?? config('app.name'),
            ],
            'manufacturer' => [
                '@type' => 'Organization',
                'name' => $companyInfo['company_name'] ?? config('app.name'),
            ],
        ];

        // Add category
        if ($category) {
            $schema['category'] = $category['name'] ?? '';
        }

        // Add offers — only include when product has a real price.
        // Google requires price+priceCurrency in Offer; omitting Offer entirely
        // for "price on request" products avoids the critical error.
        $hasPrice = !empty($product['base_price']) && $product['base_price'] > 0 && empty($product['price_on_request']);

        if ($hasPrice) {
            $schema['offers'] = [
                '@type' => 'Offer',
                'url' => route('products.show', $product['slug'] ?? ''),
                'priceCurrency' => 'IDR',
                'price' => number_format((float) $product['base_price'], 2, '.', ''),
                'priceValidUntil' => now()->addYear()->format('Y-m-d'),
                'availability' => $this->getStockAvailability($product['stock_status'] ?? 'ready'),
                'seller' => [
                    '@type' => 'Organization',
                    'name' => $companyInfo['company_name'] ?? config('app.name'),
                ],
            ];
        }
        // For price_on_request products: no offers block = no price error from Google

        // Add reviews if available
        if (!empty($product['reviews_count']) && $product['reviews_count'] > 0) {
            $schema['aggregateRating'] = [
                '@type' => 'AggregateRating',
                'ratingValue' => $product['average_rating'] ?? 5,
                'reviewCount' => $product['reviews_count'],
            ];
        }

        return $schema;
    }

    /**
     * Generate Article Schema
     */
    public function getArticleSchema(array $article): array
    {
        $companyInfo = $this->settingService->getCompanyInfo();

        $image = !empty($article['featured_image']) 
            ? url(Storage::url($article['featured_image'])) 
            : asset('images/article-placeholder.png');

        $logo = !empty($companyInfo['logo']) 
            ? url(Storage::url($companyInfo['logo'])) 
            : asset('images/logo.png');

        return [
            '@context' => 'https://schema.org',
            '@type' => 'Article',
            'headline' => $article['title'] ?? '',
            'description' => $article['excerpt'] ?? '',
            'image' => $image,
            'url' => route('articles.show', $article['slug'] ?? ''),
            'datePublished' => $article['published_at'] ?? $article['created_at'] ?? now()->toIso8601String(),
            'dateModified' => $article['updated_at'] ?? now()->toIso8601String(),
            'author' => [
                '@type' => 'Organization',
                'name' => $companyInfo['company_name'] ?? config('app.name'),
            ],
            'publisher' => [
                '@type' => 'Organization',
                'name' => $companyInfo['company_name'] ?? config('app.name'),
                'logo' => [
                    '@type' => 'ImageObject',
                    'url' => $logo,
                ],
            ],
            'mainEntityOfPage' => [
                '@type' => 'WebPage',
                '@id' => route('articles.show', $article['slug'] ?? ''),
            ],
        ];
    }

    /**
     * Generate Breadcrumb Schema
     */
    public function getBreadcrumbSchema(array $breadcrumbs): array
    {
        $items = [];
        foreach ($breadcrumbs as $index => $breadcrumb) {
            $items[] = [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $breadcrumb['name'],
                'item' => $breadcrumb['url'] ?? null,
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $items,
        ];
    }

    /**
     * Generate FAQ Schema
     */
    public function getFaqSchema(array $faqs): array
    {
        $items = [];
        foreach ($faqs as $faq) {
            $items[] = [
                '@type' => 'Question',
                'name' => $faq['question'] ?? '',
                'acceptedAnswer' => [
                    '@type' => 'Answer',
                    'text' => strip_tags($faq['answer'] ?? ''),
                ],
            ];
        }

        return [
            '@context' => 'https://schema.org',
            '@type' => 'FAQPage',
            'mainEntity' => $items,
        ];
    }

    /**
     * Generate WebSite Schema with SearchAction
     */
    public function getWebSiteSchema(): array
    {
        $companyInfo = $this->settingService->getCompanyInfo();

        return [
            '@context' => 'https://schema.org',
            '@type' => 'WebSite',
            'name' => $companyInfo['company_name'] ?? config('app.name'),
            'url' => config('app.url'),
            'potentialAction' => [
                '@type' => 'SearchAction',
                'target' => [
                    '@type' => 'EntryPoint',
                    'urlTemplate' => route('search') . '?q={search_term_string}',
                ],
                'query-input' => 'required name=search_term_string',
            ],
        ];
    }

    /**
     * Convert stock status to schema.org availability
     */
    protected function getStockAvailability(string $status): string
    {
        return match ($status) {
            'ready' => 'https://schema.org/InStock',
            'preorder' => 'https://schema.org/PreOrder',
            'out_of_stock' => 'https://schema.org/OutOfStock',
            default => 'https://schema.org/InStock',
        };
    }

    /**
     * Render schema as JSON-LD script tag
     */
    public function render(array $schema): string
    {
        return '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . '</script>';
    }

    /**
     * Render multiple schemas
     */
    public function renderMultiple(array $schemas): string
    {
        $output = '';
        foreach ($schemas as $schema) {
            $output .= $this->render($schema) . "\n";
        }
        return $output;
    }
}
