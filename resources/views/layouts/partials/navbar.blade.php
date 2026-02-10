{{-- Main Navigation --}}
{{-- Data provided by LayoutComposer with caching --}}
@php
    $companyName = $companyInfo['company_name'] ?? config('app.name');
    $companyTagline = $companyInfo['company_tagline'] ?? 'Distributor Besi Baja Terpercaya';
    $logoPath = !empty($companyInfo['logo']) ? Storage::url($companyInfo['logo']) : asset('images/logo.png');
    $navCategories = $navbarCategories ?? collect();
@endphp
<header 
    x-data="{ 
        mobileMenuOpen: false, 
        scrolled: false,
        activeDropdown: null,
        mobileExpanded: null
    }"
    x-on:scroll.window="scrolled = window.scrollY > 50"
    :class="{ 'shadow-lg backdrop-blur-sm bg-white/95': scrolled, 'bg-white': !scrolled }"
    class="sticky top-0 z-50 transition-all duration-300"
>
    <nav class="container mx-auto px-4">
        <div class="flex items-center justify-between h-16 lg:h-[72px]">
            {{-- Logo & Company Name --}}
            <a href="{{ route('home') }}" class="flex items-center gap-2.5 group flex-shrink-0" title="{{ $companyName }}">
                {{-- Logo --}}
                <div class="relative flex-shrink-0">
                    <div class="relative bg-primary-50 p-1.5 rounded-lg border border-primary-200 shadow-sm group-hover:shadow-md group-hover:border-primary-300 transition-all duration-300">
                        <img 
                            src="{{ $logoPath }}" 
                            alt="{{ $companyName }}" 
                            title="{{ $companyName }}"
                            class="h-9 w-auto"
                            width="116"
                            height="80"
                            loading="eager"
                            fetchpriority="high"
                        >
                    </div>
                    {{-- Verified Badge --}}
                    <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                        <svg class="w-4 h-4 text-primary-600" viewBox="0 0 24 24" fill="currentColor">
                            <path fill-rule="evenodd" d="M8.603 3.799A4.49 4.49 0 0 1 12 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 0 1 3.498 1.307 4.491 4.491 0 0 1 1.307 3.497A4.49 4.49 0 0 1 21.75 12a4.49 4.49 0 0 1-1.549 3.397 4.491 4.491 0 0 1-1.307 3.497 4.491 4.491 0 0 1-3.497 1.307A4.49 4.49 0 0 1 12 21.75a4.49 4.49 0 0 1-3.397-1.549 4.49 4.49 0 0 1-3.498-1.306 4.491 4.491 0 0 1-1.307-3.498A4.49 4.49 0 0 1 2.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 0 1 1.307-3.497 4.49 4.49 0 0 1 3.497-1.307Zm7.007 6.387a.75.75 0 1 0-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 0 0-1.06 1.06l2.25 2.25a.75.75 0 0 0 1.14-.094l3.75-5.25Z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                {{-- Company Name --}}
                <div class="flex flex-col min-w-0">
                    <span class="font-bold text-gray-900 text-[11px] sm:text-sm lg:text-base leading-tight group-hover:text-primary-700 transition-colors whitespace-nowrap">{{ $companyName }}</span>
                    <span class="text-[9px] sm:text-xs lg:text-sm text-gray-500 leading-tight truncate">{{ $companyTagline }}</span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center gap-0">
                {{-- Home --}}
                <a href="{{ route('home') }}" 
                   class="relative px-2.5 xl:px-3 py-2 text-[13px] xl:text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    {{ __('Home Page') }}
                    @if(request()->routeIs('home'))
                        <span class="absolute bottom-0 left-2.5 right-2.5 h-0.5 bg-primary-500 rounded-full"></span>
                    @endif
                </a>

                {{-- About Dropdown --}}
                <div class="relative" @mouseenter="activeDropdown = 'about'" @mouseleave="activeDropdown = null">
                    <button 
                        class="flex items-center gap-1 px-2.5 xl:px-3 py-2 text-[13px] xl:text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('about.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}"
                        aria-haspopup="true" 
                        :aria-expanded="activeDropdown === 'about'"
                    >
                        {{ __('About Us') }}
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="activeDropdown === 'about' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div 
                        x-show="activeDropdown === 'about'" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 w-52 bg-white rounded-xl shadow-xl ring-1 ring-black/5 py-2 mt-1 z-50"
                    >
                        <a href="{{ route('about.company') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            {{ __('Company Profile') }}
                        </a>
                        <a href="{{ route('about.vision-mission') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            {{ __('Vision & Mission') }}
                        </a>
                        <a href="{{ route('about.team') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ __('Our Team') }}
                        </a>
                        <a href="{{ route('about.certificates') }}" class="flex items-center gap-3 px-4 py-2.5 text-sm text-gray-600 hover:bg-primary-50 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                            {{ __('Our Certificates') }}
                        </a>
                    </div>
                </div>

                {{-- Products Mega Menu --}}
                <div class="relative" @mouseenter="activeDropdown = 'products'" @mouseleave="activeDropdown = null">
                    <a href="{{ route('products.index') }}" 
                       class="flex items-center gap-1 px-2.5 xl:px-3 py-2 text-[13px] xl:text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('products.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                        {{ __('Our Products') }}
                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="activeDropdown === 'products' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    
                    {{-- Mega Menu Dropdown --}}
                    <div 
                        x-show="activeDropdown === 'products'" x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="absolute top-full left-1/2 -translate-x-1/2 w-[640px] bg-white rounded-xl shadow-xl ring-1 ring-black/5 mt-1 z-50 overflow-hidden"
                    >
                        <div class="p-5">
                            <div class="grid grid-cols-2 gap-2">
                                @foreach($navCategories as $category)
                                    <a href="{{ route('products.category', $category->slug) }}" 
                                       class="group flex items-center gap-3 p-3 rounded-lg hover:bg-gray-50 transition-all duration-200">
                                        <span class="flex-shrink-0 w-10 h-10 rounded-lg bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                            @if($category->icon)
                                                <img src="{{ asset('storage/' . $category->icon) }}" alt="{{ $category->getTranslation('name', app()->getLocale()) }}" class="w-5 h-5 object-contain filter brightness-0 invert" loading="lazy" decoding="async">
                                            @else
                                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            @endif
                                        </span>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                                {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </p>
                                            <p class="text-xs text-gray-400 mt-0.5">
                                                {{ $category->products_count }} {{ __('products') }}
                                            </p>
                                        </div>
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                        <div class="bg-gray-50 px-5 py-3.5 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    {{ __('Browse All Products') }}
                                </a>
                                <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    {{ __('Request Quote') }}
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Articles --}}
                <a href="{{ route('articles.index') }}" 
                   class="relative px-2.5 xl:px-3 py-2 text-[13px] xl:text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('articles.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    {{ __('News & Articles') }}
                    @if(request()->routeIs('articles.*'))
                        <span class="absolute bottom-0 left-2.5 right-2.5 h-0.5 bg-primary-500 rounded-full"></span>
                    @endif
                </a>

                {{-- Contact --}}
                <a href="{{ route('contact') }}" 
                   class="relative px-2.5 xl:px-3 py-2 text-[13px] xl:text-sm font-medium rounded-lg transition-all duration-200 {{ request()->routeIs('contact') ? 'text-primary-600 bg-primary-50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50' }}">
                    {{ __('Contact Us') }}
                    @if(request()->routeIs('contact'))
                        <span class="absolute bottom-0 left-2.5 right-2.5 h-0.5 bg-primary-500 rounded-full"></span>
                    @endif
                </a>
            </div>

            {{-- Right Actions --}}
            <div class="hidden lg:flex items-center gap-2 flex-shrink-0">
                {{-- Search --}}
                <a href="{{ route('search') }}" 
                   class="p-2 text-gray-400 hover:text-primary-600 hover:bg-gray-50 rounded-lg transition-all duration-200"
                   aria-label="{{ __('Search Products') }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Request Quote CTA --}}
                <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary-600 to-secondary-700 hover:from-secondary-700 hover:to-secondary-800 text-white pl-4 pr-3 py-2 rounded-lg text-sm font-semibold transition-all duration-200 shadow-sm hover:shadow-md">
                    {{ __('Request Quote') }}
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 text-gray-600 hover:bg-gray-50 rounded-lg transition-colors"
                aria-label="{{ __('Toggle menu') }}"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div 
            x-show="mobileMenuOpen" x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden border-t border-gray-100"
        >
            <div class="py-3 space-y-0.5">
                <a href="{{ route('home') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mx-2 text-sm font-medium transition-colors {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    {{ __('Home Page') }}
                </a>
                
                {{-- About Accordion --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" aria-label="{{ __('Toggle About menu') }}" :aria-expanded="open" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg mx-2 text-sm font-medium transition-colors text-gray-700 hover:bg-gray-50" style="width: calc(100% - 1rem)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                            <span>{{ __('About Us') }}</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-10 mr-4 border-l-2 border-gray-100">
                        <a href="{{ route('about.company') }}" class="block px-4 py-2.5 text-sm text-gray-500 hover:text-primary-600 transition-colors">{{ __('Company Profile') }}</a>
                        <a href="{{ route('about.vision-mission') }}" class="block px-4 py-2.5 text-sm text-gray-500 hover:text-primary-600 transition-colors">{{ __('Vision & Mission') }}</a>
                        <a href="{{ route('about.team') }}" class="block px-4 py-2.5 text-sm text-gray-500 hover:text-primary-600 transition-colors">{{ __('Our Team') }}</a>
                        <a href="{{ route('about.certificates') }}" class="block px-4 py-2.5 text-sm text-gray-500 hover:text-primary-600 transition-colors">{{ __('Our Certificates') }}</a>
                    </div>
                </div>
                
                {{-- Products Accordion --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" aria-label="{{ __('Toggle Products menu') }}" :aria-expanded="open" class="w-full flex items-center justify-between gap-3 px-4 py-3 rounded-lg mx-2 text-sm font-medium transition-colors {{ request()->routeIs('products.*') ? 'text-primary-600' : 'text-gray-700' }} hover:bg-gray-50" style="width: calc(100% - 1rem)">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                            <span>{{ __('Our Products') }}</span>
                        </div>
                        <svg class="w-4 h-4 transition-transform flex-shrink-0" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="ml-10 mr-4 border-l-2 border-gray-100">
                        <a href="{{ route('products.index') }}" class="block px-4 py-2.5 text-sm text-primary-600 font-medium">{{ __('All Products') }}</a>
                        @foreach($navCategories as $category)
                            <div class="px-4 py-2">
                                <a href="{{ route('products.category', $category->slug) }}" 
                                   class="flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1 hover:text-primary-600 transition-colors py-1">
                                    <span>{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                @if($category->children->count() > 0)
                                    @foreach($category->children->take(4) as $child)
                                        <a href="{{ route('products.category', $child->slug) }}" class="block px-2 py-1.5 text-sm text-gray-500 hover:text-primary-600">
                                            {{ $child->getTranslation('name', app()->getLocale()) }}
                                        </a>
                                    @endforeach
                                    @if($category->children->count() > 4)
                                        <a href="{{ route('products.category', $category->slug) }}" class="block px-2 py-1 text-xs text-primary-600 font-medium">
                                            +{{ $category->children->count() - 4 }} {{ __('more') }} →
                                        </a>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <a href="{{ route('articles.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mx-2 text-sm font-medium transition-colors {{ request()->routeIs('articles.*') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/></svg>
                    {{ __('News & Articles') }}
                </a>
                <a href="{{ route('contact') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg mx-2 text-sm font-medium transition-colors {{ request()->routeIs('contact') ? 'text-primary-600 bg-primary-50' : 'text-gray-700 hover:bg-gray-50' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    {{ __('Contact Us') }}
                </a>
                
                <div class="px-4 pt-3 pb-2 space-y-2 border-t border-gray-100 mt-2">
                    <a href="{{ route('search') }}" class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-600 py-2.5 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('Search Products') }}
                    </a>
                    <a href="{{ route('quote') }}" class="flex items-center justify-center gap-2 w-full bg-gradient-to-r from-secondary-600 to-secondary-700 text-white py-2.5 rounded-lg text-sm font-semibold shadow-sm">
                        {{ __('Request Quote') }}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
    </nav>
</header>

