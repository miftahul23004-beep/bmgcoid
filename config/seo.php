<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default SEO Settings
    |--------------------------------------------------------------------------
    */
    
    'defaults' => [
        'title' => 'Supplier Besi Baja Surabaya - PT. Berkah Mandiri',
        'title_separator' => ' | ',
        'description' => 'Supplier besi baja terpercaya melayani Surabaya, Jawa Timur & seluruh Indonesia. Partai besar & eceran, harga kompetitif.',
        'keywords' => 'supplier besi baja Surabaya, supplier besi baja Sidoarjo, supplier besi baja Gresik, supplier besi baja Mojokerto, supplier besi baja Jombang, supplier besi baja Jawa Timur, supplier besi baja Indonesia, distributor besi nasional, jual besi baja partai besar, jual besi baja eceran, harga besi baja grosir, besi konstruksi, besi industri, PT Berkah Mandiri Globalindo',
        'author' => 'PT. Berkah Mandiri Globalindo',
        'robots' => 'index, follow',
        'canonical' => null,
    ],

    /*
    |--------------------------------------------------------------------------
    | Local SEO - Service Areas
    |--------------------------------------------------------------------------
    */
    
    'local' => [
        'primary_city' => 'Surabaya',
        'province' => 'Jawa Timur',
        'country' => 'Indonesia',
        'national_coverage' => true,
        'service_areas' => [
            // Jawa Timur (Primary)
            'Surabaya',
            'Sidoarjo', 
            'Gresik',
            'Mojokerto',
            'Jombang',
            'Pasuruan',
            'Lamongan',
            'Tuban',
            'Bojonegoro',
        ],
        'national_regions' => [
            'Jawa Timur',
            'Jawa Tengah',
            'Jawa Barat',
            'DKI Jakarta',
            'Banten',
            'Bali',
            'Kalimantan',
            'Sulawesi',
            'Sumatera',
            'Papua',
        ],
        'market_segments' => [
            'wholesale' => 'Partai Besar / Grosir',
            'retail' => 'Eceran / Satuan',
            'project' => 'Proyek Konstruksi',
            'industrial' => 'Industri & Manufaktur',
        ],
        'geo' => [
            'latitude' => '-7.2575', // Surabaya coordinates
            'longitude' => '112.7521',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Open Graph Settings
    |--------------------------------------------------------------------------
    */
    
    'og' => [
        'site_name' => 'PT. Berkah Mandiri Globalindo',
        'type' => 'website',
        'locale' => 'id_ID',
        'image' => '/images/og-image.jpg',
        'image_width' => 1200,
        'image_height' => 630,
    ],

    /*
    |--------------------------------------------------------------------------
    | Twitter Card Settings
    |--------------------------------------------------------------------------
    */
    
    'twitter' => [
        'card' => 'summary_large_image',
        'site' => '@berkahmandiriglobalindo',
        'creator' => '@berkahmandiriglobalindo',
    ],

    /*
    |--------------------------------------------------------------------------
    | JSON-LD Schema Settings
    |--------------------------------------------------------------------------
    */
    
    'schema' => [
        'organization' => [
            '@type' => 'Organization',
            'name' => 'PT. Berkah Mandiri Globalindo',
            'url' => env('APP_URL'),
            'logo' => env('APP_URL') . '/images/logo.png',
            'description' => 'Supplier dan distributor besi baja terpercaya di Surabaya, Sidoarjo, Gresik, Mojokerto, Jombang & seluruh Jawa Timur.',
            'address' => [
                '@type' => 'PostalAddress',
                'streetAddress' => '',
                'addressLocality' => 'Surabaya',
                'addressRegion' => 'Jawa Timur',
                'postalCode' => '',
                'addressCountry' => 'ID',
            ],
            'contactPoint' => [
                '@type' => 'ContactPoint',
                'telephone' => '',
                'contactType' => 'customer service',
                'availableLanguage' => ['Indonesian', 'English'],
            ],
            'sameAs' => [
                // Social media URLs will be added dynamically
            ],
            'areaServed' => [
                ['@type' => 'City', 'name' => 'Surabaya'],
                ['@type' => 'City', 'name' => 'Sidoarjo'],
                ['@type' => 'City', 'name' => 'Gresik'],
                ['@type' => 'City', 'name' => 'Mojokerto'],
                ['@type' => 'City', 'name' => 'Jombang'],
                ['@type' => 'AdministrativeArea', 'name' => 'Jawa Timur'],
            ],
        ],
        
        'localBusiness' => [
            '@type' => 'LocalBusiness',
            'additionalType' => 'https://schema.org/WholesaleStore',
            'name' => 'PT. Berkah Mandiri Globalindo',
            'description' => 'Supplier dan distributor besi baja terpercaya untuk industri, manufaktur & konstruksi di Jawa Timur',
            'priceRange' => '$$',
            'openingHours' => 'Mo-Fr 08:00-17:00, Sa 08:00-14:00',
            'paymentAccepted' => 'Cash, Bank Transfer',
            'currenciesAccepted' => 'IDR',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Sitemap Settings
    |--------------------------------------------------------------------------
    */
    
    'sitemap' => [
        'enabled' => true,
        'path' => 'sitemap.xml',
        'cache_duration' => 60 * 24, // 24 hours in minutes
        'include' => [
            'pages' => true,
            'products' => true,
            'articles' => true,
            'categories' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Analytics Settings
    |--------------------------------------------------------------------------
    */
    
    'analytics' => [
        'google_analytics_id' => env('GOOGLE_ANALYTICS_ID'),
        'google_tag_manager_id' => env('GOOGLE_TAG_MANAGER_ID'),
        'facebook_pixel_id' => env('FACEBOOK_PIXEL_ID'),
    ],
];
