<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Chat Module Configuration
    |--------------------------------------------------------------------------
    */

    'enabled' => env('CHAT_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | AI Bot Settings
    |--------------------------------------------------------------------------
    */
    
    'ai' => [
        'enabled' => env('CHAT_AI_ENABLED', true),
        'provider' => env('CHAT_AI_PROVIDER', 'openai'), // openai or gemini
        'model' => env('CHAT_AI_MODEL', 'gpt-4o-mini'),
        'openai_api_key' => env('OPENAI_API_KEY'),
        'gemini_api_key' => env('GEMINI_API_KEY'),
        'max_tokens' => 1000,
        'temperature' => 0.7,
        
        // System prompt for the AI
        'system_prompt' => 'Anda adalah asisten customer service untuk PT. Berkah Mandiri Globalindo, sebuah perusahaan distributor dan supplier besi untuk industri, manufaktur, dan konstruksi. Jawab pertanyaan pelanggan dengan ramah, profesional, dan informatif dalam Bahasa Indonesia. Jika tidak yakin dengan jawaban, sarankan pelanggan untuk menghubungi tim sales langsung.',
        
        // Fallback message when AI is unavailable
        'fallback_message' => 'Terima kasih telah menghubungi kami. Tim customer service kami akan segera membalas pesan Anda.',
        
        // Keywords that trigger handover to human operator
        'handover_keywords' => [
            'bicara dengan manusia',
            'operator',
            'customer service',
            'komplain',
            'keluhan',
            'urgent',
            'darurat',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Operator Settings
    |--------------------------------------------------------------------------
    */
    
    'operator' => [
        'auto_assign' => true,
        'max_concurrent_chats' => 5,
        'idle_timeout' => 30, // minutes
        'notification_sound' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Chat Session Settings
    |--------------------------------------------------------------------------
    */
    
    'session' => [
        'timeout' => 60, // minutes of inactivity before session ends
        'max_messages' => 100,
        'require_email' => false,
        'require_name' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Widget Settings
    |--------------------------------------------------------------------------
    */
    
    'widget' => [
        'position' => 'bottom-right', // bottom-right, bottom-left
        'primary_color' => '#1E40AF', // Primary blue
        'secondary_color' => '#DC2626', // Secondary red
        'welcome_message' => 'Selamat datang di PT. Berkah Mandiri Globalindo! Ada yang bisa kami bantu?',
        'placeholder' => 'Ketik pesan Anda...',
        'show_on_pages' => ['*'], // All pages, or specify routes
        'hide_on_pages' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Working Hours
    |--------------------------------------------------------------------------
    */
    
    'working_hours' => [
        'enabled' => true,
        'timezone' => 'Asia/Jakarta',
        'schedule' => [
            'monday' => ['08:00', '17:00'],
            'tuesday' => ['08:00', '17:00'],
            'wednesday' => ['08:00', '17:00'],
            'thursday' => ['08:00', '17:00'],
            'friday' => ['08:00', '17:00'],
            'saturday' => ['08:00', '12:00'],
            'sunday' => null, // Closed
        ],
        'outside_hours_message' => 'Terima kasih telah menghubungi kami. Saat ini di luar jam operasional (Senin-Jumat: 08:00-17:00, Sabtu: 08:00-12:00). Pesan Anda akan dibalas pada jam kerja berikutnya.',
    ],
];
