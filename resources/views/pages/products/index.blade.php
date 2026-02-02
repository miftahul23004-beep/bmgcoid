@extends('layouts.app')

@php
    $pageTitle = ($activeCategory ?? null) 
        ? $activeCategory->getTranslation('name', app()->getLocale()) . ' - ' . __('Product Catalog')
        : __('Product Catalog');
    $pageDescription = ($activeCategory ?? null)
        ? strip_tags($activeCategory->getTranslation('description', app()->getLocale()) ?? __('Explore our quality steel products'))
        : __('Explore our complete range of quality steel and iron products for construction, manufacturing and infrastructure needs.');
    $pageDescription = \Illuminate\Support\Str::limit($pageDescription, 155);
@endphp

@section('title', $pageTitle . ' - ' . config('app.name'))

@push('meta')
    {{-- SEO Meta Tags --}}
    <meta name="description" content="{{ e($pageDescription) }}">
    <meta name="keywords" content="{{ ($activeCategory ?? null) ? e($activeCategory->getTranslation('name', app()->getLocale())) . ', ' : '' }}steel products, iron products, construction materials, {{ config('app.name') }}">
    <link rel="canonical" href="{{ url()->current() }}">
    
    {{-- Pagination SEO --}}
    @if($products->previousPageUrl())
        <link rel="prev" href="{{ $products->previousPageUrl() }}">
    @endif
    @if($products->nextPageUrl())
        <link rel="next" href="{{ $products->nextPageUrl() }}">
    @endif
    
    {{-- Noindex for search/filter pages --}}
    @if(request('search'))
        <meta name="robots" content="noindex, follow">
    @endif
    
    {{-- Open Graph --}}
    <meta property="og:title" content="{{ e($pageTitle) }}">
    <meta property="og:description" content="{{ e($pageDescription) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <meta property="og:locale" content="{{ str_replace('-', '_', app()->getLocale()) }}">
    @if($activeCategory ?? null)
        @if($activeCategory->image)
            <meta property="og:image" content="{{ asset('storage/' . $activeCategory->image) }}">
        @endif
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ e($pageTitle) }}">
    <meta name="twitter:description" content="{{ e($pageDescription) }}">
@endpush

@push('structured-data')
@php
    $structuredData = [
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => $pageTitle,
        'description' => $pageDescription,
        'url' => url()->current(),
        'mainEntity' => [
            '@type' => 'ItemList',
            'numberOfItems' => $products->total(),
            'itemListElement' => $products->take(10)->map(function($product, $index) {
                $item = [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'item' => [
                        '@type' => 'Product',
                        'name' => e($product->getTranslation('name', app()->getLocale())),
                        'url' => route('products.show', $product->slug),
                        'sku' => e($product->sku ?? ''),
                        'brand' => [
                            '@type' => 'Brand',
                            'name' => config('app.name'),
                        ],
                    ],
                ];
                if ($product->featured_image) {
                    $item['item']['image'] = asset('storage/' . $product->featured_image);
                }
                return $item;
            })->values()->toArray(),
        ],
        'breadcrumb' => [
            '@type' => 'BreadcrumbList',
            'itemListElement' => array_filter([
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => __('Home'),
                    'item' => route('home'),
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => __('Product Catalog'),
                    'item' => route('products.index'),
                ],
                ($activeCategory ?? null) ? [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $activeCategory->getTranslation('name', app()->getLocale()),
                    'item' => route('products.index', ['category' => $activeCategory->slug]),
                ] : null,
            ]),
        ],
    ];
@endphp
<script type="application/ld+json">
{!! json_encode($structuredData, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endpush

@section('content')
    {{-- Compact Modern Hero --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-primary-900 to-primary-800 text-white overflow-hidden">
        {{-- Subtle Pattern --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        <div class="container relative z-10 py-8 md:py-10">
            {{-- Breadcrumb --}}
            <nav class="mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-sm text-white/60">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li class="text-white/40">/</li>
                    @if($activeCategory ?? null)
                        <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">{{ __('Product Catalog') }}</a></li>
                        <li class="text-white/40">/</li>
                        <li class="text-white font-medium">{{ $activeCategory->getTranslation('name', app()->getLocale()) }}</li>
                    @else
                        <li class="text-white font-medium">{{ __('Product Catalog') }}</li>
                    @endif
                </ol>
            </nav>
            
            {{-- Two Column Layout --}}
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">
                {{-- Left: Title & Description --}}
                <div class="flex-1">
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold leading-tight mb-3">
                        @if($activeCategory ?? null)
                            {{ $activeCategory->getTranslation('name', app()->getLocale()) }}
                        @else
                            {{ __('Product Catalog') }}
                        @endif
                    </h1>
                    
                    <p class="text-base md:text-lg text-white/70 leading-relaxed">
                        @if($activeCategory ?? null)
                            {{ strip_tags($activeCategory->getTranslation('description', app()->getLocale()) ?? '') }}
                        @else
                            {{ __('Explore our complete range of quality steel and iron products for construction, manufacturing and infrastructure needs.') }}
                        @endif
                    </p>
                </div>
                
                {{-- Right: Stats (passed from controller with caching) --}}
                <div class="flex items-center gap-6 lg:gap-8">
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-white">{{ $stats['categories'] ?? 0 }}</div>
                        <div class="text-sm text-white/60">{{ __('Categories') }}</div>
                    </div>
                    <div class="w-px h-12 bg-white/20" aria-hidden="true"></div>
                    <div class="text-center">
                        <div class="text-3xl md:text-4xl font-bold text-white">{{ $stats['products'] ?? 0 }}</div>
                        <div class="text-sm text-white/60">{{ __('Products') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="container">
            <div class="flex flex-col lg:flex-row gap-8">
                
                {{-- Sidebar --}}
                <aside class="w-full lg:w-80 flex-shrink-0" x-data="{ mobileOpen: false }">
                    {{-- Mobile Toggle --}}
                    <button @click="mobileOpen = !mobileOpen" class="lg:hidden w-full flex items-center justify-between p-4 bg-white rounded-xl shadow-sm mb-4">
                        <span class="font-semibold text-gray-900">{{ __('Filter Categories') }}</span>
                        <svg class="w-5 h-5 transition-transform" :class="{ 'rotate-180': mobileOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>
                    
                    <div class="lg:sticky lg:top-24 space-y-6" :class="{ 'hidden lg:block': !mobileOpen }">
                        {{-- Categories Card --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                            <div class="bg-gradient-to-r from-primary-600 to-primary-700 px-5 py-4">
                                <h2 class="font-bold text-white flex items-center gap-2 text-base">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                    {{ __('Product Categories') }}
                                </h2>
                            </div>
                            <div class="p-4">
                                <ul class="space-y-1">
                                    {{-- All Products --}}
                                    <li>
                                        <a href="{{ route('products.index') }}" 
                                           class="flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ !request('category') ? 'bg-primary-50 text-primary-700 font-semibold border-l-4 border-primary-500' : 'text-gray-700 hover:bg-gray-50' }}"
                                           aria-current="{{ !request('category') ? 'page' : 'false' }}">
                                            <span class="flex items-center gap-3">
                                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                {{ __('All Products') }}
                                            </span>
                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ !request('category') ? 'bg-primary-200 text-primary-800' : 'bg-gray-100 text-gray-600' }}">
                                                {{ $stats['products'] ?? 0 }}
                                            </span>
                                        </a>
                                    </li>
                                    
                                    {{-- Parent Categories with Children --}}
                                    @foreach($categories as $category)
                                        @php
                                            $isParentActive = request('category') === $category->slug;
                                            $hasActiveChild = $category->children->contains(fn($c) => request('category') === $c->slug);
                                        @endphp
                                        <li x-data="{ open: {{ $isParentActive || $hasActiveChild ? 'true' : 'false' }} }">
                                            <div class="flex items-center">
                                                @if($category->children->count() > 0)
                                                    {{-- Parent with children: not clickable, just expand/collapse --}}
                                                    <button @click="open = !open" 
                                                       class="flex-1 flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 text-gray-700 hover:bg-gray-50 cursor-pointer">
                                                        <span class="flex items-center gap-3">
                                                            @if($category->icon)
                                                                <img src="{{ asset('storage/' . $category->icon) }}" alt="" class="w-5 h-5 object-contain">
                                                            @else
                                                                <span class="w-5 h-5 rounded bg-primary-100 flex items-center justify-center">
                                                                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                                                                </span>
                                                            @endif
                                                            <span class="font-medium">{{ $category->getTranslation('name', app()->getLocale()) }}</span>
                                                        </span>
                                                        <span class="flex items-center gap-2">
                                                            <span class="px-2.5 py-0.5 text-xs font-medium rounded-full bg-gray-100 text-gray-600">
                                                                {{ $category->products_count }}
                                                            </span>
                                                            <svg class="w-4 h-4 transition-transform duration-200" :class="{ 'rotate-180': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                            </svg>
                                                        </span>
                                                    </button>
                                                @else
                                                    {{-- Parent without children: clickable --}}
                                                    <a href="{{ route('products.index', ['category' => $category->slug]) }}" 
                                                       class="flex-1 flex items-center justify-between px-4 py-3 rounded-xl transition-all duration-200 {{ $isParentActive ? 'bg-primary-50 text-primary-700 font-semibold border-l-4 border-primary-500' : 'text-gray-700 hover:bg-gray-50' }}">
                                                        <span class="flex items-center gap-3">
                                                            @if($category->icon)
                                                                <img src="{{ asset('storage/' . $category->icon) }}" alt="" class="w-5 h-5 object-contain">
                                                            @else
                                                                <span class="w-5 h-5 rounded bg-primary-100 flex items-center justify-center">
                                                                    <span class="w-2 h-2 rounded-full bg-primary-500"></span>
                                                                </span>
                                                            @endif
                                                            {{ $category->getTranslation('name', app()->getLocale()) }}
                                                        </span>
                                                        <span class="px-2.5 py-0.5 text-xs font-medium rounded-full {{ $isParentActive ? 'bg-primary-200 text-primary-800' : 'bg-gray-100 text-gray-600' }}">
                                                            {{ $category->products_count }}
                                                        </span>
                                                    </a>
                                                @endif
                                            </div>
                                            
                                            {{-- Children --}}
                                            @if($category->children->count() > 0)
                                                <ul x-show="open" x-collapse class="ml-6 mt-1 space-y-1 border-l-2 border-gray-100 pl-4">
                                                    @foreach($category->children as $child)
                                                        <li>
                                                            <a href="{{ route('products.index', ['category' => $child->slug]) }}" 
                                                               class="flex items-center justify-between px-3 py-2 rounded-lg text-sm transition-all duration-200 {{ request('category') === $child->slug ? 'bg-primary-50 text-primary-700 font-medium' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                                                                <span>{{ $child->getTranslation('name', app()->getLocale()) }}</span>
                                                                <span class="text-xs {{ request('category') === $child->slug ? 'text-primary-600' : 'text-gray-400' }}">{{ $child->products_count ?? 0 }}</span>
                                                            </a>
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        
                        {{-- Contact CTA Card --}}
                        <div class="bg-gradient-to-br from-secondary-600 to-secondary-700 rounded-2xl p-6 text-white">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                                </svg>
                            </div>
                            <h3 class="font-bold text-lg mb-2">{{ __('Need Help?') }}</h3>
                            <p class="text-secondary-100 text-sm mb-4">{{ __('Our team is ready to help you find the right product') }}</p>
                            <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-secondary-700 px-4 py-2 rounded-lg font-semibold text-sm hover:bg-secondary-50 transition-colors">
                                {{ __('Contact Us') }}
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </aside>

                {{-- Main Content --}}
                <div class="flex-1 min-w-0">
                    {{-- Search & Sort Bar --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
                        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-4">
                            {{-- Search with security & accessibility improvements --}}
                            <form action="{{ route('products.index') }}" method="GET" class="flex-1 relative" role="search">
                                @if(request('category'))
                                    <input type="hidden" name="category" value="{{ e(request('category')) }}">
                                @endif
                                @if(request('sort'))
                                    <input type="hidden" name="sort" value="{{ e(request('sort')) }}">
                                @endif
                                <label for="product-search" class="sr-only">{{ __('Search products') }}</label>
                                <input type="search" 
                                       id="product-search"
                                       name="search" 
                                       value="{{ e(request('search', '')) }}" 
                                       placeholder="{{ __('Search products...') }}" 
                                       maxlength="100"
                                       autocomplete="off"
                                       class="w-full pl-12 pr-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 transition-all">
                                <svg class="w-5 h-5 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                @if(request('search'))
                                    <a href="{{ route('products.index', request()->except('search')) }}" 
                                       class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600"
                                       aria-label="{{ __('Clear search') }}">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                                        </svg>
                                    </a>
                                @endif
                            </form>
                            
                            {{-- Sort --}}
                            <div class="flex items-center gap-3">
                                <span class="text-sm text-gray-500 whitespace-nowrap hidden sm:inline">{{ __('Sort by') }}:</span>
                                <select name="sort" 
                                        onchange="window.location.href = this.value" 
                                        class="px-4 py-3 rounded-xl border border-gray-200 focus:border-primary-500 focus:ring-2 focus:ring-primary-200 text-gray-700 bg-white min-w-[150px]">
                                    <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'newest'])) }}" {{ request('sort') === 'newest' || !request('sort') ? 'selected' : '' }}>{{ __('Newest') }}</option>
                                    <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'oldest'])) }}" {{ request('sort') === 'oldest' ? 'selected' : '' }}>{{ __('Oldest') }}</option>
                                    <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_asc'])) }}" {{ request('sort') === 'name_asc' ? 'selected' : '' }}>{{ __('Name A-Z') }}</option>
                                    <option value="{{ route('products.index', array_merge(request()->except('sort'), ['sort' => 'name_desc'])) }}" {{ request('sort') === 'name_desc' ? 'selected' : '' }}>{{ __('Name Z-A') }}</option>
                                </select>
                            </div>
                        </div>
                        
                        {{-- Results Info --}}
                        <div class="mt-4 pt-4 border-t border-gray-100 flex items-center justify-between">
                            <p class="text-sm text-gray-600">
                                @if($products->total() > 0)
                                    {{ __('Showing') }} <span class="font-semibold text-gray-900">{{ $products->firstItem() }}-{{ $products->lastItem() }}</span> {{ __('of') }} <span class="font-semibold text-gray-900">{{ $products->total() }}</span> {{ __('products') }}
                                @else
                                    {{ __('No products found') }}
                                @endif
                            </p>
                            
                            {{-- View Toggle --}}
                            <div class="hidden sm:flex items-center gap-1 bg-gray-100 rounded-lg p-1" x-data x-on:click="$dispatch('toggle-view', $event.target.closest('button')?.dataset.view)">
                                <button data-view="grid" class="p-2 rounded-md transition-all" :class="$store.productView.mode === 'grid' ? 'bg-white shadow-sm text-primary-600' : 'text-gray-400 hover:text-gray-600'" @click="$store.productView.mode = 'grid'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                                    </svg>
                                </button>
                                <button data-view="list" class="p-2 rounded-md transition-all" :class="$store.productView.mode === 'list' ? 'bg-white shadow-sm text-primary-600' : 'text-gray-400 hover:text-gray-600'" @click="$store.productView.mode = 'list'">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Products Grid/List --}}
                    @if($products->count() > 0)
                        <div class="min-h-[400px]">
                            {{-- Grid View --}}
                            <div x-data x-show="$store.productView.mode === 'grid'" x-cloak class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-6">
                                @foreach($products as $product)
                                    @include('components.product-card', ['product' => $product, 'listView' => false])
                                @endforeach
                            </div>
                            
                            {{-- List View (default - shown immediately to prevent CLS) --}}
                            <div x-data 
                                 x-show="$store.productView.mode === 'list'" 
                                 x-init="if ($store.productView.mode !== 'list') $el.style.display = 'none'"
                                 class="space-y-4">
                                @foreach($products as $product)
                                    @include('components.product-card', ['product' => $product, 'listView' => true])
                                @endforeach
                            </div>
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-8">
                            {{ $products->links() }}
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
                            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                                <svg class="w-10 h-10 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                                </svg>
                            </div>
                            <h2 class="text-xl font-bold text-gray-900 mb-2">{{ __('No Products Found') }}</h2>
                            <p class="text-gray-600 mb-6 max-w-md mx-auto">{{ __('No products matching your search. Try adjusting your filters or search terms.') }}</p>
                            <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                                </svg>
                                {{ __('Reset Filters') }}
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

