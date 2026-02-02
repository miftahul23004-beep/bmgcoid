<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Media Configuration
    |--------------------------------------------------------------------------
    | Settings for image, video, and document uploads
    */

    /*
    |--------------------------------------------------------------------------
    | Image Settings
    |--------------------------------------------------------------------------
    */
    
    'images' => [
        'disk' => 'public',
        'path' => 'images',
        
        // Allowed extensions
        'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'],
        
        // Maximum file size in KB
        'max_size' => 5120, // 5 MB
        
        // Conversion settings
        'conversions' => [
            'thumbnail' => [
                'width' => 150,
                'height' => 150,
                'quality' => 80,
                'format' => 'webp',
            ],
            'small' => [
                'width' => 320,
                'height' => null, // Auto height
                'quality' => 80,
                'format' => 'webp',
            ],
            'medium' => [
                'width' => 640,
                'height' => null,
                'quality' => 85,
                'format' => 'webp',
            ],
            'large' => [
                'width' => 1024,
                'height' => null,
                'quality' => 85,
                'format' => 'webp',
            ],
            'hero' => [
                'width' => 1920,
                'height' => 1080,
                'quality' => 90,
                'format' => 'webp',
            ],
        ],
        
        // Optimization
        'optimize' => true,
        'lazy_loading' => true,
        'generate_srcset' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Video Settings
    |--------------------------------------------------------------------------
    */
    
    'videos' => [
        'disk' => 'public',
        'path' => 'videos',
        
        // Allowed extensions
        'allowed_extensions' => ['mp4', 'webm', 'mov', 'avi'],
        
        // Maximum file size in KB
        'max_size' => 102400, // 100 MB
        
        // YouTube integration
        'youtube' => [
            'enabled' => true,
            'api_key' => env('YOUTUBE_API_KEY'),
            'extract_thumbnail' => true,
        ],
        
        // Thumbnail generation
        'generate_thumbnail' => true,
        'thumbnail_time' => 1, // Seconds into video
    ],

    /*
    |--------------------------------------------------------------------------
    | Document Settings
    |--------------------------------------------------------------------------
    */
    
    'documents' => [
        'disk' => 'public',
        'path' => 'documents',
        
        // Allowed extensions
        'allowed_extensions' => ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx'],
        
        // Maximum file size in KB
        'max_size' => 20480, // 20 MB
        
        // PDF preview
        'generate_pdf_preview' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Product Media Settings
    |--------------------------------------------------------------------------
    */
    
    'product' => [
        'max_images' => 10,
        'max_videos' => 5,
        'max_documents' => 10,
        'primary_image_required' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Gallery Settings
    |--------------------------------------------------------------------------
    */
    
    'gallery' => [
        'lightbox' => true,
        'zoom' => true,
        'thumbnails_position' => 'bottom', // bottom, left, right
        'autoplay_video' => false,
        'show_counter' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Watermark Settings
    |--------------------------------------------------------------------------
    */
    
    'watermark' => [
        'enabled' => false,
        'image' => 'images/watermark.png',
        'position' => 'bottom-right', // top-left, top-right, bottom-left, bottom-right, center
        'opacity' => 50,
        'apply_to' => ['large', 'hero'],
    ],

    /*
    |--------------------------------------------------------------------------
    | CDN Settings
    |--------------------------------------------------------------------------
    */
    
    'cdn' => [
        'enabled' => env('CDN_ENABLED', false),
        'url' => env('CDN_URL'),
        'cloudinary' => [
            'enabled' => env('CLOUDINARY_ENABLED', false),
            'cloud_name' => env('CLOUDINARY_CLOUD_NAME'),
            'api_key' => env('CLOUDINARY_API_KEY'),
            'api_secret' => env('CLOUDINARY_API_SECRET'),
        ],
    ],
];
