<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Audit Module Configuration
    |--------------------------------------------------------------------------
    | Settings for performance and SEO audit functionality
    */

    'enabled' => env('AUDIT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Performance Audit Settings
    |--------------------------------------------------------------------------
    */
    
    'performance' => [
        'enabled' => true,
        
        // Target scores (0-100)
        'targets' => [
            'overall' => 95,
            'first_contentful_paint' => 1800, // ms
            'largest_contentful_paint' => 2500, // ms
            'cumulative_layout_shift' => 0.1,
            'time_to_interactive' => 3800, // ms
            'total_blocking_time' => 200, // ms
            'speed_index' => 3400, // ms
        ],
        
        // Lighthouse integration
        'lighthouse' => [
            'enabled' => env('LIGHTHOUSE_ENABLED', true),
            'api_key' => env('PAGESPEED_API_KEY'),
        ],
        
        // Log settings
        'logging' => [
            'enabled' => true,
            'retention_days' => 90,
            'sample_rate' => 0.1, // 10% of requests
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | SEO Audit Settings
    |--------------------------------------------------------------------------
    */
    
    'seo' => [
        'enabled' => true,
        
        // Checks to perform
        'checks' => [
            'meta_title' => [
                'enabled' => true,
                'min_length' => 30,
                'max_length' => 60,
            ],
            'meta_description' => [
                'enabled' => true,
                'min_length' => 120,
                'max_length' => 160,
            ],
            'canonical_url' => true,
            'headings_structure' => true,
            'image_alt_tags' => true,
            'internal_links' => true,
            'external_links' => true,
            'broken_links' => true,
            'mobile_friendly' => true,
            'page_speed' => true,
            'ssl_certificate' => true,
            'xml_sitemap' => true,
            'robots_txt' => true,
            'structured_data' => true,
            'open_graph' => true,
            'twitter_cards' => true,
        ],
        
        // Auto-fix settings
        'auto_fix' => [
            'enabled' => false,
            'missing_alt_tags' => false,
            'duplicate_titles' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Scheduled Audit Settings
    |--------------------------------------------------------------------------
    */
    
    'schedule' => [
        'enabled' => true,
        'frequency' => 'daily', // hourly, daily, weekly, monthly
        'time' => '02:00', // Run at 2 AM
        'timezone' => 'Asia/Jakarta',
        
        // Pages to audit
        'pages' => [
            'homepage' => true,
            'products' => true,
            'articles' => true,
            'categories' => true,
            'custom' => [], // Additional URLs to audit
        ],
        
        // Limits
        'max_pages_per_run' => 50,
    ],

    /*
    |--------------------------------------------------------------------------
    | Alert Settings
    |--------------------------------------------------------------------------
    */
    
    'alerts' => [
        'enabled' => true,
        
        // Email alerts
        'email' => [
            'enabled' => true,
            'recipients' => explode(',', env('AUDIT_ALERT_EMAILS', '')),
        ],
        
        // Slack alerts
        'slack' => [
            'enabled' => env('AUDIT_SLACK_ENABLED', false),
            'webhook_url' => env('AUDIT_SLACK_WEBHOOK'),
        ],
        
        // Thresholds for alerts
        'thresholds' => [
            'performance_score_drop' => 10, // Alert if score drops by 10+ points
            'seo_critical_issues' => 1, // Alert if any critical SEO issues
            'broken_links' => 5, // Alert if 5+ broken links found
            'page_load_time' => 5000, // Alert if page takes > 5 seconds
        ],
        
        // Alert frequency
        'cooldown' => 60, // Minutes between repeated alerts
    ],

    /*
    |--------------------------------------------------------------------------
    | Dashboard Settings
    |--------------------------------------------------------------------------
    */
    
    'dashboard' => [
        'default_period' => 30, // Days
        'chart_type' => 'line', // line, bar
        'show_comparisons' => true,
        'items_per_page' => 20,
    ],

    /*
    |--------------------------------------------------------------------------
    | Cache Settings
    |--------------------------------------------------------------------------
    */
    
    'cache' => [
        'enabled' => true,
        'ttl' => 3600, // 1 hour
        'prefix' => 'audit_',
    ],
];
