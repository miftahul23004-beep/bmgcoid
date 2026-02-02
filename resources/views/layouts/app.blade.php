<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
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

    {{-- Preconnect to external resources --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    
    {{-- Fonts --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&family=Plus+Jakarta+Sans:wght@600;700&display=swap" rel="stylesheet">

    {{-- Critical CSS for preventing CLS --}}
    <style>
        [x-cloak] { display: none !important; }
    </style>

    {{-- Vite Assets --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Additional Head Content --}}
    @stack('head')
    @stack('meta')

    {{-- Google Analytics --}}
    @if(config('seo.analytics.google_analytics_id'))
    <script async src="https://www.googletagmanager.com/gtag/js?id={{ config('seo.analytics.google_analytics_id') }}"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());
        gtag('config', '{{ config('seo.analytics.google_analytics_id') }}');
    </script>
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
</body>
</html>

