<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Marketplace Configuration
    |--------------------------------------------------------------------------
    | Configuration for e-commerce marketplace links on product pages
    */

    'platforms' => [
        'shopee' => [
            'name' => 'Shopee',
            'enabled' => env('MARKETPLACE_SHOPEE_ENABLED', true),
            'base_url' => 'https://shopee.co.id',
            'store_url' => env('MARKETPLACE_SHOPEE_STORE_URL', ''),
            'icon' => 'shopee',
            'color' => '#EE4D2D',
            'order' => 1,
        ],
        
        'tokopedia' => [
            'name' => 'Tokopedia',
            'enabled' => env('MARKETPLACE_TOKOPEDIA_ENABLED', true),
            'base_url' => 'https://www.tokopedia.com',
            'store_url' => env('MARKETPLACE_TOKOPEDIA_STORE_URL', ''),
            'icon' => 'tokopedia',
            'color' => '#42B549',
            'order' => 2,
        ],
        
        'tiktok_shop' => [
            'name' => 'TikTok Shop',
            'enabled' => env('MARKETPLACE_TIKTOK_ENABLED', true),
            'base_url' => 'https://www.tiktok.com',
            'store_url' => env('MARKETPLACE_TIKTOK_STORE_URL', ''),
            'icon' => 'tiktok',
            'color' => '#000000',
            'order' => 3,
        ],
        
        'lazada' => [
            'name' => 'Lazada',
            'enabled' => env('MARKETPLACE_LAZADA_ENABLED', true),
            'base_url' => 'https://www.lazada.co.id',
            'store_url' => env('MARKETPLACE_LAZADA_STORE_URL', ''),
            'icon' => 'lazada',
            'color' => '#0F146D',
            'order' => 4,
        ],
        
        'blibli' => [
            'name' => 'Blibli',
            'enabled' => env('MARKETPLACE_BLIBLI_ENABLED', true),
            'base_url' => 'https://www.blibli.com',
            'store_url' => env('MARKETPLACE_BLIBLI_STORE_URL', ''),
            'icon' => 'blibli',
            'color' => '#0095DA',
            'order' => 5,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Display Settings
    |--------------------------------------------------------------------------
    */
    
    'display' => [
        'show_on_product_list' => false,
        'show_on_product_detail' => true,
        'button_style' => 'filled', // filled, outlined, icon-only
        'show_platform_name' => true,
        'open_in_new_tab' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Tracking Settings
    |--------------------------------------------------------------------------
    */
    
    'tracking' => [
        'enabled' => true,
        'track_clicks' => true,
        'utm_source' => 'website',
        'utm_medium' => 'referral',
    ],
];
