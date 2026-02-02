<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Social Media Configuration
    |--------------------------------------------------------------------------
    */

    'platforms' => [
        'whatsapp' => [
            'enabled' => env('SOCIAL_WHATSAPP_ENABLED', true),
            'number' => env('SOCIAL_WHATSAPP_NUMBER', ''),
            'default_message' => 'Halo, saya tertarik dengan produk PT. Berkah Mandiri Globalindo',
            'icon' => 'whatsapp',
            'color' => '#25D366',
        ],
        
        'instagram' => [
            'enabled' => env('SOCIAL_INSTAGRAM_ENABLED', true),
            'url' => env('SOCIAL_INSTAGRAM_URL', ''),
            'username' => env('SOCIAL_INSTAGRAM_USERNAME', ''),
            'icon' => 'instagram',
            'color' => '#E4405F',
        ],
        
        'facebook' => [
            'enabled' => env('SOCIAL_FACEBOOK_ENABLED', true),
            'url' => env('SOCIAL_FACEBOOK_URL', ''),
            'page_id' => env('SOCIAL_FACEBOOK_PAGE_ID', ''),
            'icon' => 'facebook',
            'color' => '#1877F2',
        ],
        
        'tiktok' => [
            'enabled' => env('SOCIAL_TIKTOK_ENABLED', true),
            'url' => env('SOCIAL_TIKTOK_URL', ''),
            'username' => env('SOCIAL_TIKTOK_USERNAME', ''),
            'icon' => 'tiktok',
            'color' => '#000000',
        ],
        
        'youtube' => [
            'enabled' => env('SOCIAL_YOUTUBE_ENABLED', true),
            'url' => env('SOCIAL_YOUTUBE_URL', ''),
            'channel_id' => env('SOCIAL_YOUTUBE_CHANNEL_ID', ''),
            'icon' => 'youtube',
            'color' => '#FF0000',
        ],
        
        'linkedin' => [
            'enabled' => env('SOCIAL_LINKEDIN_ENABLED', false),
            'url' => env('SOCIAL_LINKEDIN_URL', ''),
            'icon' => 'linkedin',
            'color' => '#0A66C2',
        ],
        
        'twitter' => [
            'enabled' => env('SOCIAL_TWITTER_ENABLED', false),
            'url' => env('SOCIAL_TWITTER_URL', ''),
            'username' => env('SOCIAL_TWITTER_USERNAME', ''),
            'icon' => 'twitter',
            'color' => '#1DA1F2',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Floating Social Button Settings
    |--------------------------------------------------------------------------
    */
    
    'floating_button' => [
        'enabled' => true,
        'position' => 'bottom-left', // bottom-left, bottom-right
        'show_on_mobile' => true,
        'show_labels' => false,
        'animation' => 'slide-up',
        'platforms' => ['whatsapp', 'instagram', 'facebook', 'tiktok'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Share Button Settings
    |--------------------------------------------------------------------------
    */
    
    'share' => [
        'enabled' => true,
        'platforms' => ['whatsapp', 'facebook', 'twitter', 'linkedin', 'email', 'copy'],
    ],
];
