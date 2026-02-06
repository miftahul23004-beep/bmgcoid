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
    :class="{ 'shadow-md': scrolled }"
    class="sticky top-0 z-50 transition-all duration-300 bg-white min-h-[72px] md:min-h-[80px]"
>
    <nav class="container mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            {{-- Logo with Site Name & Badges --}}
            <a href="{{ route('home') }}" class="flex items-center gap-3 group">
                {{-- Logo Container with Glow Effect --}}
                <div class="relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-primary-500/20 to-amber-500/20 rounded-xl blur-lg opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="relative bg-gradient-to-br from-white to-gray-50 p-2 rounded-xl border border-gray-100 shadow-sm group-hover:shadow-md group-hover:border-primary-200 transition-all duration-300">
                        <img 
                            src="{{ $logoPath }}" 
                            alt="{{ $companyName }}" 
                            class="h-8 md:h-10 w-auto"
                            width="40"
                            height="40"
                            fetchpriority="high"
                        >
                    </div>
                    {{-- Trust Badge --}}
                    <div class="absolute -bottom-1 -right-1 bg-gradient-to-r from-amber-500 to-amber-600 text-white text-[8px] font-bold px-1.5 py-0.5 rounded-full shadow-sm flex items-center gap-0.5">
                        <svg class="w-2 h-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                {{-- Company Name & Tagline --}}
                <div class="hidden sm:flex flex-col">
                    <div class="flex items-center gap-2">
                        <span class="font-bold text-gray-900 text-lg leading-tight group-hover:text-primary-700 transition-colors">{{ $companyName }}</span>
                        {{-- Verified Badge --}}
                        <span class="inline-flex items-center gap-0.5 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-[9px] font-semibold px-1.5 py-0.5 rounded-full">
                            <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <span class="hidden md:inline">Terpercaya</span>
                        </span>
                    </div>
                    <span class="text-xs text-gray-500 leading-tight">{{ $companyTagline }}</span>
                </div>
            </a>

            {{-- Desktop Navigation --}}
            <div class="hidden lg:flex items-center space-x-8">
                {{-- Main Menu Items --}}
                <a href="{{ route('home') }}" class="font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('home') ? 'text-primary-600' : '' }}">
                    {{ __('Home') }}
                </a>

                {{-- About Dropdown --}}
                <div 
                    class="relative"
                    @mouseenter="activeDropdown = 'about'"
                    @mouseleave="activeDropdown = null"
                >
                    <button class="flex items-center font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('about.*') ? 'text-primary-600' : '' }}" aria-haspopup="true" aria-expanded="false">
                        {{ __('About') }}
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div 
                        x-show="activeDropdown === 'about'"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 translate-y-1"
                        class="absolute top-full left-0 w-48 bg-white rounded-lg shadow-lg py-2 mt-2 z-50"
                    >
                        <a href="{{ route('about.company') }}" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600">{{ __('Company Profile') }}</a>
                        <a href="{{ route('about.vision-mission') }}" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600">{{ __('Vision & Mission') }}</a>
                        <a href="{{ route('about.team') }}" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600">{{ __('Our Team') }}</a>
                        <a href="{{ route('about.certificates') }}" class="block px-4 py-2 text-gray-700 hover:bg-primary-50 hover:text-primary-600">{{ __('Certificates') }}</a>
                    </div>
                </div>

                {{-- Products Mega Menu --}}
                <div 
                    class="relative"
                    @mouseenter="activeDropdown = 'products'"
                    @mouseleave="activeDropdown = null"
                >
                    <a href="{{ route('products.index') }}" class="flex items-center font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('products.*') ? 'text-primary-600' : '' }}">
                        {{ __('Products') }}
                        <svg class="w-4 h-4 ml-1 transition-transform duration-200" :class="activeDropdown === 'products' ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </a>
                    
                    {{-- Clean Mega Menu Dropdown --}}
                    <div 
                        x-show="activeDropdown === 'products'"
                        x-cloak
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="opacity-0 -translate-y-1"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="opacity-100 translate-y-0"
                        x-transition:leave-end="opacity-0 -translate-y-1"
                        class="absolute top-full left-1/2 -translate-x-1/2 w-[640px] bg-white rounded-xl shadow-xl ring-1 ring-gray-900/5 mt-3 z-50 overflow-hidden"
                    >
                        {{-- Categories Grid --}}
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4">
                                @foreach($navCategories as $category)
                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                       class="group flex items-center gap-4 p-4 rounded-xl hover:bg-gray-50 transition-all duration-200">
                                        {{-- Icon --}}
                                        <span class="flex-shrink-0 w-12 h-12 rounded-xl bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center shadow-sm group-hover:shadow-md group-hover:scale-105 transition-all duration-200">
                                            @if($category->icon)
                                                <img src="{{ asset('storage/' . $category->icon) }}" alt="" class="w-6 h-6 object-contain filter brightness-0 invert">
                                            @else
                                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                            @endif
                                        </span>
                                        {{-- Text --}}
                                        <div class="flex-1 min-w-0">
                                            <p class="text-sm font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">
                                                {{ $category->getTranslation('name', app()->getLocale()) }}
                                            </p>
                                            <p class="text-xs text-gray-500 mt-0.5">
                                                {{ $category->products_count }} {{ __('products') }}
                                            </p>
                                        </div>
                                        {{-- Arrow --}}
                                        <svg class="w-4 h-4 text-gray-300 group-hover:text-primary-500 group-hover:translate-x-0.5 transition-all" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>

                        {{-- Footer Actions --}}
                        <div class="bg-gray-50 px-6 py-4 border-t border-gray-100">
                            <div class="flex items-center justify-between">
                                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-primary-600 hover:text-primary-700 transition-colors">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    {{ __('Browse All Products') }}
                                </a>
                                <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-600 hover:bg-primary-700 text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
                                    {{ __('Request Quote') }}
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <a href="{{ route('articles.index') }}" class="font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('articles.*') ? 'text-primary-600' : '' }}">
                    {{ __('Articles') }}
                </a>

                <a href="{{ route('contact') }}" class="font-medium text-gray-700 hover:text-primary-600 transition-colors {{ request()->routeIs('contact') ? 'text-primary-600' : '' }}">
                    {{ __('Contact') }}
                </a>
            </div>

            {{-- CTA Button & Search --}}
            <div class="hidden lg:flex items-center space-x-3">
                {{-- Search Button --}}
                <a 
                    href="{{ route('search') }}"
                    class="p-2 text-gray-600 hover:text-primary-600 transition-colors"
                    aria-label="{{ __('Search') }}"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </a>

                {{-- Request Quote Button --}}
                <a href="{{ route('quote') }}" class="bg-secondary-600 hover:bg-secondary-700 text-white px-6 py-2.5 rounded-lg font-medium transition-colors">
                    {{ __('Request Quote') }}
                </a>
            </div>

            {{-- Mobile Menu Button --}}
            <button 
                @click="mobileMenuOpen = !mobileMenuOpen"
                class="lg:hidden p-2 text-gray-600"
                aria-label="{{ __('Toggle menu') }}"
            >
                <svg x-show="!mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg x-show="mobileMenuOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>

        {{-- Mobile Menu --}}
        <div 
            x-show="mobileMenuOpen"
            x-cloak
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 -translate-y-2"
            x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 -translate-y-2"
            class="lg:hidden bg-white border-t"
        >
            <div class="py-4 space-y-1">
                <a href="{{ route('home') }}" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('home') ? 'text-primary-600 bg-primary-50' : '' }}">{{ __('Home') }}</a>
                
                {{-- About Accordion --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" aria-label="{{ __('Toggle About menu') }}" :aria-expanded="open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600">
                        <span>{{ __('About') }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="bg-gray-50">
                        <a href="{{ route('about.company') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600">{{ __('Company Profile') }}</a>
                        <a href="{{ route('about.vision-mission') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600">{{ __('Vision & Mission') }}</a>
                        <a href="{{ route('about.team') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600">{{ __('Our Team') }}</a>
                        <a href="{{ route('about.certificates') }}" class="block px-8 py-2 text-sm text-gray-600 hover:text-primary-600">{{ __('Certificates') }}</a>
                    </div>
                </div>
                
                {{-- Products Accordion --}}
                <div x-data="{ open: false }">
                    <button @click="open = !open" aria-label="{{ __('Toggle Products menu') }}" :aria-expanded="open" class="w-full flex items-center justify-between px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('products.*') ? 'text-primary-600' : '' }}">
                        <span>{{ __('Products') }}</span>
                        <svg class="w-4 h-4 transition-transform" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    <div x-show="open" x-collapse class="bg-gray-50">
                        <a href="{{ route('products.index') }}" class="block px-8 py-2 text-sm text-primary-600 font-medium">{{ __('All Products') }}</a>
                        @foreach($navCategories as $category)
                            <div class="px-6 py-2">
                                {{-- Parent category - always clickable --}}
                                <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                   class="flex items-center justify-between text-xs font-semibold text-gray-700 uppercase tracking-wide mb-1 hover:text-primary-600 transition-colors py-1">
                                    <span>{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                                {{-- Children (if any) --}}
                                @if($category->children->count() > 0)
                                    @foreach($category->children->take(4) as $child)
                                        <a href="{{ route('products.index', ['category' => $child->slug]) }}" class="block px-2 py-1.5 text-sm text-gray-600 hover:text-primary-600">
                                            {{ $child->getTranslation('name', app()->getLocale()) }}
                                        </a>
                                    @endforeach
                                    @if($category->children->count() > 4)
                                        <a href="{{ route('products.index', ['category' => $category->slug]) }}" class="block px-2 py-1 text-xs text-primary-600 font-medium">
                                            +{{ $category->children->count() - 4 }} {{ __('more') }} →
                                        </a>
                                    @endif
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <a href="{{ route('articles.index') }}" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('articles.*') ? 'text-primary-600 bg-primary-50' : '' }}">{{ __('Articles') }}</a>
                <a href="{{ route('contact') }}" class="block px-4 py-3 text-gray-700 hover:bg-primary-50 hover:text-primary-600 {{ request()->routeIs('contact') ? 'text-primary-600 bg-primary-50' : '' }}">{{ __('Contact') }}</a>
                
                <div class="px-4 pt-4 space-y-2">
                    <a href="{{ route('search') }}" class="flex items-center justify-center gap-2 w-full border border-gray-200 text-gray-700 py-3 rounded-lg font-medium">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        {{ __('Search') }}
                    </a>
                    <a href="{{ route('quote') }}" class="block w-full bg-secondary-600 text-white text-center py-3 rounded-lg font-medium">{{ __('Request Quote') }}</a>
                </div>
            </div>
        </div>
    </nav>
</header>

