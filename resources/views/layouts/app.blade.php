<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Site Verification Meta Tags --}}
    @php
        $seoSettings = app(\App\Services\SettingService::class)->getGroup('seo');
    @endphp
    @if(!empty($seoSettings['google_site_verification']))
        <meta name="google-site-verification" content="{{ $seoSettings['google_site_verification'] }}">
    @endif
    @if(!empty($seoSettings['bing_site_verification']))
        <meta name="msvalidate.01" content="{{ $seoSettings['bing_site_verification'] }}">
    @endif
    
    {{-- SEO Meta Tags --}}
    <title>{{ $metaTitle ?? config('seo.defaults.title') }}</title>
    <meta name="description" content="{{ $metaDescription ?? config('seo.defaults.description') }}">
    <meta name="keywords" content="{{ $metaKeywords ?? config('seo.defaults.keywords') }}">
    <meta name="author" content="{{ config('seo.defaults.author') }}">
    <meta name="robots" content="{{ $metaRobots ?? config('seo.defaults.robots') }}">
    <link rel="canonical" href="{{ $canonicalUrl ?? url()->current() }}">

    {{-- Open Graph Meta Tags --}}
    <meta property="og:title" content="{{ $ogTitle ?? $metaTitle ?? config('seo.defaults.title') }}">
    <meta property="og:description" content="{{ $ogDescription ?? $metaDescription ?? config('seo.defaults.description') }}">
    <meta property="og:image" content="{{ $ogImage ?? asset(config('seo.og.image')) }}">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:type" content="{{ $ogType ?? config('seo.og.type') }}">
    <meta property="og:site_name" content="{{ config('seo.og.site_name') }}">
    <meta property="og:locale" content="{{ config('seo.og.locale') }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="{{ config('seo.twitter.card') }}">
    <meta name="twitter:site" content="{{ config('seo.twitter.site') }}">
    <meta name="twitter:title" content="{{ $metaTitle ?? config('seo.defaults.title') }}">
    <meta name="twitter:description" content="{{ $metaDescription ?? config('seo.defaults.description') }}">
    <meta name="twitter:image" content="{{ $ogImage ?? asset(config('seo.og.image')) }}">

    {{-- Favicon --}}
    @php
        $settingService = app(\App\Services\SettingService::class);
        $companyInfo = $settingService->getCompanyInfo();
        $faviconPath = !empty($companyInfo['favicon']) ? Storage::url($companyInfo['favicon']) : asset('images/favicon.png');
    @endphp
    <link rel="icon" type="image/x-icon" href="{{ $faviconPath }}">
    <link rel="apple-touch-icon" href="{{ asset('images/apple-touch-icon.png') }}">

    {{-- DNS Prefetch & Preconnect for performance --}}
    <link rel="dns-prefetch" href="https://fonts.googleapis.com">
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link rel="dns-prefetch" href="https://www.googletagmanager.com">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    {{-- Preload LCP hero image for homepage --}}
    @stack('preload')
    
    {{-- Preload critical font (only weights used above fold) --}}
    <link rel="preload" href="https://fonts.gstatic.com/s/inter/v18/UcCO3FwrK3iLTeHuS_nVMrMxCp50SjIw2boKoduKmMEVuLyfAZ9hiJ-Ek-_EeA.woff2" as="font" type="font/woff2" crossorigin>
    
    {{-- Fonts loaded async to prevent render blocking --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet" media="print" onload="this.media='all'">
    <noscript>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">
    </noscript>

    {{-- Critical CSS for preventing CLS and FOIT --}}
    <style>
        [x-cloak] { display: none !important; }
        /* Critical font fallback to prevent layout shift */
        body { font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; }
        /* Prevent FOIT - show text immediately with fallback */
        .font-display-swap { font-display: swap; }
    </style>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional Head Content --}}
    @stack('head')
    @stack('meta')
    
    {{-- Custom Head Scripts from SEO Settings --}}
    @if(!empty($seoSettings['custom_head_scripts']))
        {!! $seoSettings['custom_head_scripts'] !!}
    @endif
</head>
<body class="font-sans antialiased bg-gray-50 text-gray-900 overflow-x-hidden">
    {{-- Skip to main content for accessibility --}}
    <a href="#main-content" class="sr-only focus:not-sr-only focus:absolute focus:top-4 focus:left-4 bg-primary-600 text-white px-4 py-2 rounded-md z-50">
        {{ __('Skip to main content') }}
    </a>

    {{-- Top Bar --}}
    @include('layouts.partials.topbar')

    {{-- Main Navigation --}}
    @include('layouts.partials.navbar')

    {{-- Main Content --}}
    <main id="main-content">
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.partials.footer')

    {{-- Floating Elements --}}
    @include('layouts.partials.floating-social')
    
    {{-- Livewire Chat Widget - TEMPORARILY DISABLED --}}
    {{-- @livewire('chat-widget') --}}

    {{-- Back to Top Button --}}
    <button 
        x-data="{ show: false }"
        x-on:scroll.window="show = window.scrollY > 500"
        x-show="show"
        x-transition
        x-on:click="window.scrollTo({ top: 0, behavior: 'smooth' })"
        class="fixed bottom-24 right-6 bg-primary-600 text-white p-3 rounded-full shadow-lg hover:bg-primary-700 transition-colors z-40"
        aria-label="{{ __('Back to top') }}"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
        </svg>
    </button>

    {{-- Additional Scripts --}}
    @stack('scripts')

    {{-- Google Analytics - Loaded after page content for better performance --}}
    @if(config('seo.analytics.google_analytics_id'))
    <script>
        // Defer Google Analytics until after page load
        window.addEventListener('load', function() {
            setTimeout(function() {
                var script = document.createElement('script');
                script.src = 'https://www.googletagmanager.com/gtag/js?id={{ config('seo.analytics.google_analytics_id') }}';
                script.async = true;
                document.head.appendChild(script);
                
                window.dataLayer = window.dataLayer || [];
                function gtag(){dataLayer.push(arguments);}
                gtag('js', new Date());
                gtag('config', '{{ config('seo.analytics.google_analytics_id') }}');
            }, 2000); // Wait 2 seconds after page load
        });
    </script>
    @endif

    {{-- Base JSON-LD Schema (Organization, LocalBusiness, WebSite) --}}
    @php
        $schemaService = app(\App\Services\SchemaService::class);
        $baseSchemas = [
            $schemaService->getOrganizationSchema(),
            $schemaService->getWebSiteSchema(),
        ];
        
        // Add LocalBusiness only on homepage
        if(request()->routeIs('home') || request()->is('/')) {
            $baseSchemas[] = $schemaService->getLocalBusinessSchema();
        }
    @endphp
    <x-schema-markup :schemas="$baseSchemas" />

    {{-- Page-specific JSON-LD Schema --}}
    @stack('schema')
    @stack('structured-data')
    
    {{-- Custom Body Scripts from SEO Settings --}}
    @if(!empty($seoSettings['custom_body_scripts']))
        {!! $seoSettings['custom_body_scripts'] !!}
    @endif
</body>
</html>

