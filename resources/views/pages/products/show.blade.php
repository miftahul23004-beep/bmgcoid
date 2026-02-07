@extends('layouts.app')

@php
    use App\Helpers\HtmlSanitizer;
    $productName = e($product->getTranslation('name', app()->getLocale()));
    $productDescription = HtmlSanitizer::sanitize($product->getTranslation('description', app()->getLocale()));
    $shortDescription = e($product->getTranslation('short_description', app()->getLocale()) ?? '');
    $metaDesc = Str::limit(strip_tags($shortDescription ?: $productDescription), 160);
@endphp

@section('title', $productName . ' - ' . config('app.name'))
@section('meta_description', $metaDesc)

@php
    $canonicalUrl = route('products.show', $product->slug);
@endphp

@push('meta')
    {{-- Open Graph extras --}}
    <meta property="og:type" content="product">
    @if($product->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $product->featured_image) }}">
        <meta property="og:image:alt" content="{{ $productName }}">
    @elseif($product->productMedia->first())
        <meta property="og:image" content="{{ $product->productMedia->first()->url ?? asset('storage/' . $product->productMedia->first()->file_path) }}">
    @endif
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    @if($product->featured_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $product->featured_image) }}">
    @endif
@endpush

@push('schema')
    @php
        $schemaService = app(\App\Services\SchemaService::class);
        
        // Product Schema - escape untuk keamanan
        $productData = [
            'name' => e($product->getTranslation('name', app()->getLocale())),
            'description' => strip_tags($product->getTranslation('description', app()->getLocale())),
            'short_description' => e($product->getTranslation('short_description', app()->getLocale())),
            'slug' => $product->slug,
            'sku' => e($product->sku ?? $product->slug),
            'price' => $product->price ?? 0,
            'stock_status' => $product->stock_status ?? 'ready',
            'primary_image' => $product->featured_image ?? ($product->productMedia->first()?->file_path ?? null),
            'reviews_count' => $product->reviews_count ?? 0,
            'average_rating' => $product->average_rating ?? 5,
        ];
        
        $categoryData = $product->category ? [
            'name' => $product->category->getTranslation('name', app()->getLocale()),
        ] : null;
        
        $productSchema = $schemaService->getProductSchema($productData, $categoryData);
        
        // Breadcrumb Schema
        $breadcrumbItems = [
            ['name' => __('Home'), 'url' => route('home')],
            ['name' => __('Products'), 'url' => route('products.index')],
        ];
        foreach($breadcrumbs as $crumb) {
            $breadcrumbItems[] = ['name' => $crumb['name'], 'url' => $crumb['url']];
        }
        $breadcrumbSchema = $schemaService->getBreadcrumbSchema($breadcrumbItems);
        
        // FAQ Schema (if product has FAQs)
        $faqSchema = null;
        if(($product->productFaqs ?? collect())->count() > 0) {
            $faqs = $product->productFaqs->map(function($faq) {
                return [
                    'question' => $faq->getTranslation('question', app()->getLocale()),
                    'answer' => $faq->getTranslation('answer', app()->getLocale()),
                ];
            })->toArray();
            $faqSchema = $schemaService->getFaqSchema($faqs);
        }
        
        // Prepare Media Collection
        $allMedia = collect();
        
        // Add featured image first
        if ($product->featured_image) {
            $allMedia->push([
                'type' => 'image',
                'url' => asset('storage/' . $product->featured_image),
                'thumbnail' => asset('storage/' . $product->featured_image),
            ]);
        }
        
        // Add product media
        if ($product->productMedia) {
            foreach ($product->productMedia as $media) {
                $mediaItem = [
                    'type' => $media->type,
                    'youtube_id' => $media->youtube_id ?? null,
                ];
                
                if ($media->type === 'youtube') {
                    $mediaItem['url'] = null; // YouTube doesn't need URL, uses youtube_id
                    $mediaItem['thumbnail'] = $media->youtube_id 
                        ? "https://img.youtube.com/vi/{$media->youtube_id}/mqdefault.jpg"
                        : null;
                } else {
                    $mediaItem['url'] = $media->file_path ? asset('storage/' . $media->file_path) : null;
                    $mediaItem['thumbnail'] = $media->file_path ? asset('storage/' . $media->file_path) : null;
                }
                
                $allMedia->push($mediaItem);
            }
        }
        
        // Fallback if no media
        if ($allMedia->isEmpty()) {
            $allMedia->push([
                'type' => 'placeholder',
                'url' => null,
                'thumbnail' => null,
            ]);
        }
    @endphp
    <x-schema-markup :schemas="[$productSchema, $breadcrumbSchema]" />
    @if($faqSchema)
        <x-schema-markup :schemas="[$faqSchema]" />
    @endif
@endpush

@section('content')
    {{-- Breadcrumb --}}
    <section class="bg-gray-50 border-b border-gray-100">
        <div class="container py-4">
            <nav class="text-sm" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 flex-wrap">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </a>
                    </li>
                    <li><span class="text-gray-400">/</span></li>
                    <li><a href="{{ route('products.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ __('Products') }}</a></li>
                    @foreach($breadcrumbs as $crumb)
                        <li><span class="text-gray-400">/</span></li>
                        @if($crumb['url'])
                            <li><a href="{{ $crumb['url'] }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ $crumb['name'] }}</a></li>
                        @else
                            <li class="text-gray-900 font-medium">{{ Str::limit($crumb['name'], 50) }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
        </div>
    </section>

    {{-- Product Detail Main Section --}}
    <section class="py-8 md:py-12 bg-white">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 lg:gap-12">
                
                {{-- Product Gallery with Zoom --}}
                <div class="product-gallery" x-data="productGallery({{ $allMedia->toJson() }})">
                    <div class="flex flex-col md:flex-row gap-4">
                        {{-- Thumbnails (Left Side on Desktop) --}}
                        <div class="hidden md:flex flex-col gap-2 w-20 flex-shrink-0 max-h-[500px] overflow-y-auto custom-scrollbar">
                            <template x-for="(media, index) in mediaList" :key="index">
                                <button 
                                    @click="setActive(index)" 
                                    :class="activeIndex === index ? 'ring-2 ring-primary-500 ring-offset-1' : 'ring-1 ring-gray-200 hover:ring-gray-300'"
                                    class="aspect-square bg-gray-100 rounded-lg overflow-hidden transition-all flex-shrink-0"
                                >
                                    <template x-if="media.type === 'image' || media.type === 'placeholder'">
                                        <img :src="media.thumbnail || '/images/placeholder-product.png'" alt="Thumbnail" class="w-full h-full object-cover">
                                    </template>
                                    <template x-if="media.type === 'youtube' && media.thumbnail">
                                        <div class="w-full h-full relative">
                                            <img :src="media.thumbnail" alt="YouTube" class="w-full h-full object-cover">
                                            <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                    <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                </svg>
                                            </div>
                                        </div>
                                    </template>
                                </button>
                            </template>
                        </div>
                        
                        {{-- Main Display --}}
                        <div class="flex-1">
                            <div class="relative aspect-square bg-gray-100 rounded-2xl overflow-hidden group cursor-zoom-in" 
                                 @mouseenter="zoomEnabled = true" 
                                 @mouseleave="zoomEnabled = false; zoomStyle = ''"
                                 @mousemove="handleZoom($event)">
                                
                                {{-- Main Image/Video Display --}}
                                <template x-for="(media, index) in mediaList" :key="'main-' + index">
                                    <div x-show="activeIndex === index" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="absolute inset-0">
                                        <template x-if="media.type === 'image'">
                                            <div class="relative w-full h-full overflow-hidden">
                                                <img :src="media.url" :alt="'{{ addslashes($product->getTranslation('name', app()->getLocale())) }}'" class="w-full h-full object-contain transition-transform duration-150" :style="zoomEnabled && activeMedia?.type === 'image' ? zoomStyle : ''">
                                            </div>
                                        </template>
                                        <template x-if="media.type === 'youtube' && media.youtube_id">
                                            <iframe :src="'https://www.youtube.com/embed/' + media.youtube_id + '?rel=0&enablejsapi=1'" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                                        </template>
                                        <template x-if="media.type === 'placeholder'">
                                            <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100">
                                                <svg class="w-24 h-24 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        </template>
                                    </div>
                                </template>
                                
                                {{-- Zoom Indicator (only for images) --}}
                                <div x-show="zoomEnabled && activeMedia?.type === 'image'" class="absolute top-4 right-4 bg-black/60 text-white px-3 py-1.5 rounded-full text-xs font-medium flex items-center gap-1.5 pointer-events-none">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/>
                                    </svg>
                                    {{ __('Move to zoom') }}
                                </div>
                                
                                {{-- Navigation Arrows --}}
                                <template x-if="mediaList.length > 1">
                                    <div>
                                        <button @click.stop="prev()" class="absolute left-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                                            </svg>
                                        </button>
                                        <button @click.stop="next()" class="absolute right-3 top-1/2 -translate-y-1/2 w-10 h-10 bg-white/90 hover:bg-white rounded-full shadow-lg flex items-center justify-center text-gray-700 opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                                
                                {{-- Badges --}}
                                <div class="absolute top-4 left-4 flex flex-wrap gap-2 z-10">
                                    @if($product->is_featured)
                                        <span class="bg-primary-600 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">{{ __('Featured') }}</span>
                                    @endif
                                    @if($product->is_new)
                                        <span class="bg-green-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">{{ __('New') }}</span>
                                    @endif
                                    @if($product->is_bestseller)
                                        <span class="bg-amber-500 text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-sm">{{ __('Best Seller') }}</span>
                                    @endif
                                </div>
                                
                                {{-- Media Counter --}}
                                <template x-if="mediaList.length > 1">
                                    <div class="absolute bottom-4 left-1/2 -translate-x-1/2 bg-black/60 text-white px-3 py-1 rounded-full text-sm font-medium">
                                        <span x-text="activeIndex + 1"></span> / <span x-text="mediaList.length"></span>
                                    </div>
                                </template>
                            </div>
                            
                            {{-- Mobile Thumbnails (Bottom) --}}
                            <div class="flex md:hidden gap-2 mt-4 overflow-x-auto pb-2 scrollbar-hide">
                                <template x-for="(media, index) in mediaList" :key="'mobile-' + index">
                                    <button 
                                        @click="setActive(index)" 
                                        :class="activeIndex === index ? 'ring-2 ring-primary-500' : 'ring-1 ring-gray-200'"
                                        class="w-16 h-16 flex-shrink-0 bg-gray-100 rounded-lg overflow-hidden"
                                    >
                                        <template x-if="media.type === 'image' || media.type === 'placeholder'">
                                            <img :src="media.thumbnail || '/images/placeholder-product.png'" alt="Thumbnail" class="w-full h-full object-cover">
                                        </template>
                                        <template x-if="media.type === 'youtube' && media.thumbnail">
                                            <div class="w-full h-full relative">
                                                <img :src="media.thumbnail" alt="YouTube" class="w-full h-full object-cover">
                                                <div class="absolute inset-0 flex items-center justify-center bg-black/20">
                                                    <svg class="w-5 h-5 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                                                        <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                                                    </svg>
                                                </div>
                                            </div>
                                        </template>
                                        <template x-if="media.type === 'video'">
                                            <div class="w-full h-full flex items-center justify-center bg-gray-200">
                                                <svg class="w-5 h-5 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/>
                                                </svg>
                                            </div>
                                        </template>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Product Info --}}
                <div class="product-info">
                    {{-- Category Badge --}}
                    @if($product->category)
                        <a href="{{ route('products.category', $product->category->slug) }}" class="inline-flex items-center gap-1.5 text-sm text-primary-600 hover:text-primary-700 font-medium mb-3 transition-colors">
                            @if($product->category->icon)
                                <img src="{{ asset('storage/' . $product->category->icon) }}" alt="{{ $product->category->getTranslation('name', app()->getLocale()) }}" class="w-4 h-4 object-contain">
                            @endif
                            {{ $product->category->getTranslation('name', app()->getLocale()) }}
                        </a>
                    @endif
                    
                    {{-- Product Title --}}
                    <h1 class="text-2xl md:text-3xl lg:text-4xl font-bold font-heading text-gray-900 leading-tight">
                        {{ $product->getTranslation('name', app()->getLocale()) }}
                    </h1>
                    
                    {{-- Meta Info: SKU, Rating, Stock --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 mt-4">
                        @if($product->sku)
                            <span class="text-sm text-gray-500">
                                <span class="font-medium">SKU:</span> {{ $product->sku }}
                            </span>
                        @endif
                        
                        {{-- Rating (if available) --}}
                        @if(($product->reviews_count ?? 0) > 0)
                            <div class="flex items-center gap-1.5">
                                <div class="flex items-center text-amber-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= round($product->average_rating ?? 0))
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @else
                                            <svg class="w-4 h-4 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                        @endif
                                    @endfor
                                </div>
                                <span class="text-sm text-gray-600">({{ $product->reviews_count }} {{ __('reviews') }})</span>
                            </div>
                        @endif
                        
                        {{-- Stock Status - Only show In Stock and Pre Order --}}
                        @if($product->stock_status === 'in_stock')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-sm font-medium">
                                <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                                {{ __('In Stock') }}
                            </span>
                        @elseif($product->stock_status === 'pre_order')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-sm font-medium">
                                <span class="w-2 h-2 bg-amber-500 rounded-full"></span>
                                {{ __('Pre Order') }}
                            </span>
                        @endif
                        {{-- Out of Stock badge hidden as requested --}}
                    </div>

                    {{-- Short Description --}}
                    @if($product->short_description)
                        <p class="text-gray-600 mt-6 text-lg leading-relaxed">
                            {{ $product->getTranslation('short_description', app()->getLocale()) }}
                        </p>
                    @endif

                    {{-- Variant Selector --}}
                    @if($product->variants->count() > 0)
                        <div class="mt-8 p-5 bg-gray-50 rounded-xl" x-data="{ selectedVariant: '{{ $product->variants->first()->id }}' }">
                            <h3 class="font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                                </svg>
                                {{ __('Select Variant') }}
                            </h3>
                            <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                @foreach($product->variants as $variant)
                                    <label 
                                        class="relative flex items-center justify-center p-3 border-2 rounded-xl cursor-pointer transition-all"
                                        :class="selectedVariant === '{{ $variant->id }}' ? 'border-primary-500 bg-primary-50' : 'border-gray-200 bg-white hover:border-gray-300'"
                                    >
                                        <input type="radio" name="variant" value="{{ $variant->id }}" x-model="selectedVariant" class="sr-only">
                                        <div class="text-center">
                                            <span class="font-medium text-gray-900">{{ $variant->getTranslation('name', app()->getLocale()) }}</span>
                                            @if($variant->sku)
                                                <span class="block text-xs text-gray-500 mt-0.5">{{ $variant->sku }}</span>
                                            @endif
                                        </div>
                                        <div x-show="selectedVariant === '{{ $variant->id }}'" class="absolute -top-1 -right-1 w-5 h-5 bg-primary-500 rounded-full flex items-center justify-center">
                                            <svg class="w-3 h-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                            </svg>
                                        </div>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Quick Specs Preview --}}
                    @if($product->specifications && count($product->specifications) > 0)
                        <div class="mt-6 p-5 bg-gray-50 rounded-xl">
                            <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                                {{ __('Quick Specifications') }}
                            </h3>
                            <div class="grid grid-cols-2 gap-3">
                                @foreach(collect($product->specifications)->take(4) as $key => $value)
                                    <div class="flex flex-col">
                                        <span class="text-xs text-gray-500 uppercase tracking-wide">{{ $key }}</span>
                                        <span class="font-medium text-gray-900">{{ $value }}</span>
                                    </div>
                                @endforeach
                            </div>
                            @if(count($product->specifications) > 4)
                                <button onclick="document.querySelector('[data-tab=specifications]')?.click()" class="mt-3 text-sm text-primary-600 hover:text-primary-700 font-medium">
                                    {{ __('View all specifications') }} →
                                </button>
                            @endif
                        </div>
                    @endif

                    {{-- CTA Buttons --}}
                    <div class="mt-8 flex flex-wrap gap-3">
                        <a href="{{ route('quote') }}?product={{ $product->id }}" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-primary-600 hover:bg-primary-700 text-white text-lg font-semibold px-8 py-4 rounded-xl transition-colors shadow-lg shadow-primary-600/25">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                            </svg>
                            {{ __('Request Quote') }}
                        </a>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('social.whatsapp') ?? '6281234567890') }}?text={{ urlencode(__('Hello, I am interested in') . ' ' . $product->getTranslation('name', 'id') . ' (SKU: ' . ($product->sku ?? '-') . ')') }}" target="_blank" rel="noopener" class="flex-1 sm:flex-none inline-flex items-center justify-center gap-2 bg-green-600 hover:bg-green-700 text-white text-lg font-semibold px-8 py-4 rounded-xl transition-colors border-2 border-green-700 hover:border-green-800">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            WhatsApp
                        </a>
                    </div>

                    {{-- Marketplace Links --}}
                    @php
                        $marketplaceSettings = app(\App\Services\SettingService::class)->getMarketplaceLinks();
                        $showMarketplaceProduct = $marketplaceSettings['show_marketplace_product'] ?? false;
                    @endphp
                    @if($showMarketplaceProduct && $product->marketplaceLinks->count() > 0)
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <h3 class="text-sm font-medium text-gray-500 mb-3">{{ __('Also Available at') }}:</h3>
                            <div class="flex flex-wrap gap-2">
                                @foreach($product->marketplaceLinks as $link)
                                    <a href="{{ route('marketplace.redirect', ['platform' => $link->platform, 'productId' => $product->id]) }}" target="_blank" rel="noopener" class="inline-flex items-center gap-2 px-4 py-2.5 rounded-lg border border-gray-200 bg-white hover:border-primary-500 hover:bg-primary-50 transition-all group">
                                        @switch($link->platform)
                                            @case('shopee')
                                                <span class="w-6 h-6 rounded bg-orange-500 flex items-center justify-center text-white text-xs font-bold">S</span>
                                                @break
                                            @case('tokopedia')
                                                <span class="w-6 h-6 rounded bg-green-500 flex items-center justify-center text-white text-xs font-bold">T</span>
                                                @break
                                            @case('tiktok')
                                                <span class="w-6 h-6 rounded bg-black flex items-center justify-center text-white text-xs font-bold">TT</span>
                                                @break
                                            @case('lazada')
                                                <span class="w-6 h-6 rounded bg-blue-800 flex items-center justify-center text-white text-xs font-bold">L</span>
                                                @break
                                            @case('blibli')
                                                <span class="w-6 h-6 rounded bg-blue-500 flex items-center justify-center text-white text-xs font-bold">B</span>
                                                @break
                                            @default
                                                <span class="w-6 h-6 rounded bg-gray-400 flex items-center justify-center text-white text-xs font-bold">M</span>
                                        @endswitch
                                        <span class="font-medium text-gray-700 group-hover:text-primary-700">{{ ucfirst($link->platform) }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Share Buttons --}}
                    <div class="mt-6 pt-6 border-t border-gray-100 flex items-center gap-4">
                        <span class="text-sm text-gray-500">{{ __('Share') }}:</span>
                        <div class="flex gap-2">
                            <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-blue-600 text-white hover:bg-blue-700 transition-colors" title="Facebook">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            </a>
                            <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($product->getTranslation('name', app()->getLocale())) }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-black text-white hover:bg-gray-800 transition-colors" title="X (Twitter)">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            </a>
                            <a href="https://wa.me/?text={{ urlencode($product->getTranslation('name', app()->getLocale()) . ' - ' . request()->url()) }}" target="_blank" rel="noopener" class="w-9 h-9 flex items-center justify-center rounded-full bg-green-500 text-white hover:bg-green-600 transition-colors" title="WhatsApp">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                            </a>
                            <button onclick="navigator.clipboard.writeText('{{ request()->url() }}'); this.classList.add('bg-green-500'); setTimeout(() => this.classList.remove('bg-green-500'), 2000)" class="w-9 h-9 flex items-center justify-center rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300 transition-colors" title="{{ __('Copy Link') }}">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Product Tabs Section --}}
    <section class="py-8 md:py-12 bg-gray-50">
        <div class="container">
            <div x-data="{ activeTab: 'description' }">
                {{-- Tab Navigation --}}
                <div class="bg-white rounded-t-2xl border border-b-0 border-gray-200 overflow-hidden">
                    <div class="flex overflow-x-auto scrollbar-hide">
                        <button @click="activeTab = 'description'" data-tab="description" :class="activeTab === 'description' ? 'border-b-2 border-primary-500 text-primary-600 bg-primary-50/50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'" class="flex items-center gap-2 px-6 py-4 font-medium whitespace-nowrap transition-colors">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                            </svg>
                            {{ __('Description') }}
                        </button>
                        
                        @if($product->specifications && count($product->specifications) > 0)
                            <button @click="activeTab = 'specifications'" data-tab="specifications" :class="activeTab === 'specifications' ? 'border-b-2 border-primary-500 text-primary-600 bg-primary-50/50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'" class="flex items-center gap-2 px-6 py-4 font-medium whitespace-nowrap transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                                </svg>
                                {{ __('Specifications') }}
                            </button>
                        @endif
                        
                        @if($product->variants->count() > 0)
                            <button @click="activeTab = 'variants'" data-tab="variants" :class="activeTab === 'variants' ? 'border-b-2 border-primary-500 text-primary-600 bg-primary-50/50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'" class="flex items-center gap-2 px-6 py-4 font-medium whitespace-nowrap transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                                {{ __('Variants') }}
                                <span class="bg-primary-100 text-primary-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $product->variants->count() }}</span>
                            </button>
                        @endif
                        
                        @if($product->documents->count() > 0)
                            <button @click="activeTab = 'documents'" data-tab="documents" :class="activeTab === 'documents' ? 'border-b-2 border-primary-500 text-primary-600 bg-primary-50/50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'" class="flex items-center gap-2 px-6 py-4 font-medium whitespace-nowrap transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                {{ __('Documents') }}
                                <span class="bg-primary-100 text-primary-700 text-xs font-semibold px-2 py-0.5 rounded-full">{{ $product->documents->count() }}</span>
                            </button>
                        @endif
                        
                        @if(($product->productFaqs ?? collect())->count() > 0)
                            <button @click="activeTab = 'faq'" data-tab="faq" :class="activeTab === 'faq' ? 'border-b-2 border-primary-500 text-primary-600 bg-primary-50/50' : 'text-gray-600 hover:text-gray-900 hover:bg-gray-50'" class="flex items-center gap-2 px-6 py-4 font-medium whitespace-nowrap transition-colors">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                {{ __('FAQ') }}
                            </button>
                        @endif
                    </div>
                </div>

                {{-- Tab Content --}}
                <div class="bg-white rounded-b-2xl border border-t-0 border-gray-200 p-6 md:p-8">
                    {{-- Description Tab --}}
                    <div x-show="activeTab === 'description'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0">
                        <div class="product-description">
                            {!! $productDescription !!}
                        </div>
                    </div>

                    {{-- Specifications Tab --}}
                    @if($product->specifications && count($product->specifications) > 0)
                        <div x-show="activeTab === 'specifications'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="overflow-hidden rounded-xl border border-gray-200">
                                <table class="w-full">
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($product->specifications as $key => $value)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-4 px-6 font-medium text-gray-900 bg-gray-50 w-1/3">{{ $key }}</td>
                                                <td class="py-4 px-6 text-gray-600">{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Variants Tab --}}
                    @if($product->variants->count() > 0)
                        <div x-show="activeTab === 'variants'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="overflow-hidden rounded-xl border border-gray-200">
                                <table class="w-full">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="py-4 px-6 text-left font-semibold text-gray-900">{{ __('Variant') }}</th>
                                            <th class="py-4 px-6 text-left font-semibold text-gray-900">{{ __('SKU') }}</th>
                                            <th class="py-4 px-6 text-left font-semibold text-gray-900">{{ __('Status') }}</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200">
                                        @foreach($product->variants as $variant)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="py-4 px-6 font-medium text-gray-900">{{ $variant->getTranslation('name', app()->getLocale()) }}</td>
                                                <td class="py-4 px-6 text-gray-600">{{ $variant->sku ?? '-' }}</td>
                                                <td class="py-4 px-6">
                                                    @if($variant->is_active ?? true)
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-green-100 text-green-700 text-xs font-medium">
                                                            <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                                            {{ __('Available') }}
                                                        </span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 text-xs font-medium">
                                                            {{ __('Unavailable') }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    {{-- Documents Tab --}}
                    @if($product->documents->count() > 0)
                        <div x-show="activeTab === 'documents'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="grid gap-4 sm:grid-cols-2">
                                @foreach($product->documents as $document)
                                    <a href="{{ $document->file_url }}" target="_blank" rel="noopener" class="flex items-center gap-4 p-5 bg-gray-50 border border-gray-200 rounded-xl hover:bg-primary-50 hover:border-primary-200 transition-colors group">
                                        <div class="w-14 h-14 bg-primary-100 rounded-xl flex items-center justify-center flex-shrink-0 group-hover:bg-primary-200 transition-colors">
                                            <svg class="w-7 h-7 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                            </svg>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <h4 class="font-semibold text-gray-900 truncate group-hover:text-primary-700">{{ $document->getTranslation('title', app()->getLocale()) }}</h4>
                                            <p class="text-sm text-gray-500 mt-0.5">{{ strtoupper($document->file_type ?? 'PDF') }} • {{ $document->formatted_file_size ?? __('Download') }}</p>
                                        </div>
                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                        </svg>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- FAQ Tab --}}
                    @if(($product->productFaqs ?? collect())->count() > 0)
                        <div x-show="activeTab === 'faq'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                            <div class="space-y-3" x-data="{ openFaq: 0 }">
                                @foreach($product->productFaqs as $index => $faq)
                                    <div class="border border-gray-200 rounded-xl overflow-hidden">
                                        <button @click="openFaq = openFaq === {{ $index }} ? null : {{ $index }}" class="w-full flex items-center justify-between p-5 text-left hover:bg-gray-50 transition-colors">
                                            <span class="font-semibold text-gray-900 pr-4">{{ $faq->getTranslation('question', app()->getLocale()) }}</span>
                                            <svg class="w-5 h-5 text-gray-500 flex-shrink-0 transform transition-transform" :class="openFaq === {{ $index }} ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                            </svg>
                                        </button>
                                        <div x-show="openFaq === {{ $index }}" x-collapse>
                                            <div class="px-5 pb-5 text-gray-600 leading-relaxed border-t border-gray-100 pt-4">
                                                {!! nl2br(e($faq->getTranslation('answer', app()->getLocale()))) !!}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Product Inquiry Form Section --}}
    <section class="py-8 md:py-12 bg-white">
        <div class="container">
            <div class="max-w-3xl mx-auto">
                <div class="bg-gradient-to-br from-primary-600 to-primary-700 rounded-2xl p-8 md:p-10 text-white shadow-xl">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl md:text-3xl font-bold font-heading">{{ __('Request a Quote') }}</h2>
                        <p class="text-primary-200 mt-2">{{ __('Get the best price for this product') }}</p>
                    </div>
                    <div class="bg-white rounded-xl p-6">
                        @livewire('product-inquiry-form', ['product' => $product])
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Related Products --}}
    @if($relatedProducts->count() > 0)
        <section class="py-12 md:py-16 bg-gray-50">
            <div class="container">
                <div class="flex items-center justify-between mb-8">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold font-heading text-gray-900">{{ __('Related Products') }}</h2>
                        <p class="text-gray-600 mt-1">{{ __('You might also like these products') }}</p>
                    </div>
                    @if($product->category)
                        <a href="{{ route('products.category', $product->category->slug) }}" class="hidden sm:flex items-center gap-1.5 text-primary-600 hover:text-primary-700 font-medium transition-colors">
                            {{ __('View All') }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    @endif
                </div>
                <div class="grid grid-cols-2 sm:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
                    @foreach($relatedProducts as $related)
                        @include('components.product-card', ['product' => $related])
                    @endforeach
                </div>
                @if($product->category)
                    <div class="mt-8 text-center sm:hidden">
                        <a href="{{ route('products.category', $product->category->slug) }}" class="btn btn-outline">
                            {{ __('View All Products') }}
                        </a>
                    </div>
                @endif
            </div>
        </section>
    @endif

    {{-- Recently Viewed Products --}}
    <section class="py-12 md:py-16 bg-white" x-data="recentlyViewed({{ $product->id }})" x-show="products.length > 0" x-cloak>
        <div class="container">
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h2 class="text-2xl md:text-3xl font-bold font-heading text-gray-900">{{ __('Recently Viewed') }}</h2>
                    <p class="text-gray-600 mt-1">{{ __('Products you have viewed recently') }}</p>
                </div>
                <button @click="clearHistory()" class="text-gray-500 hover:text-gray-700 text-sm font-medium flex items-center gap-1.5 transition-colors">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    {{ __('Clear') }}
                </button>
            </div>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4">
                <template x-for="product in products.slice(0, 5)" :key="product.id">
                    <a :href="product.url" class="group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden">
                        <div class="aspect-square bg-gray-100 overflow-hidden">
                            <img :src="product.image || '/images/placeholder-product.png'" :alt="product.name" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                        </div>
                        <div class="p-3">
                            <h4 class="font-medium text-gray-900 text-sm line-clamp-2 group-hover:text-primary-600 transition-colors" x-text="product.name"></h4>
                            <p x-show="product.category" class="text-xs text-gray-500 mt-1" x-text="product.category"></p>
                        </div>
                    </a>
                </template>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    function productGallery(mediaList) {
        return {
            mediaList: mediaList,
            activeIndex: 0,
            zoomEnabled: false,
            zoomStyle: '',
            
            get activeMedia() {
                return this.mediaList[this.activeIndex];
            },
            
            setActive(index) {
                this.activeIndex = index;
            },
            
            prev() {
                this.activeIndex = this.activeIndex > 0 ? this.activeIndex - 1 : this.mediaList.length - 1;
            },
            
            next() {
                this.activeIndex = this.activeIndex < this.mediaList.length - 1 ? this.activeIndex + 1 : 0;
            },
            
            handleZoom(event) {
                if (!this.zoomEnabled || this.activeMedia?.type !== 'image') return;
                
                const rect = event.currentTarget.getBoundingClientRect();
                const x = ((event.clientX - rect.left) / rect.width) * 100;
                const y = ((event.clientY - rect.top) / rect.height) * 100;
                
                this.zoomStyle = `transform: scale(2); transform-origin: ${x}% ${y}%;`;
            }
        }
    }
    
    function recentlyViewed(currentProductId) {
        return {
            products: [],
            
            init() {
                // Get from localStorage
                let viewed = JSON.parse(localStorage.getItem('recentlyViewedProducts') || '[]');
                
                // Filter out current product
                viewed = viewed.filter(p => p.id !== currentProductId);
                
                this.products = viewed;
                
                // Add current product to history
                this.addToHistory({
                    id: currentProductId,
                    name: '{{ addslashes($product->getTranslation('name', app()->getLocale())) }}',
                    image: '{{ $product->featured_image ? asset('storage/' . $product->featured_image) : ($product->productMedia->first() ? ($product->productMedia->first()->url ?? asset('storage/' . $product->productMedia->first()->file_path)) : '') }}',
                    url: '{{ route('products.show', $product->slug) }}',
                    category: '{{ $product->category ? addslashes($product->category->getTranslation('name', app()->getLocale())) : '' }}'
                });
            },
            
            addToHistory(product) {
                let viewed = JSON.parse(localStorage.getItem('recentlyViewedProducts') || '[]');
                
                // Remove if already exists
                viewed = viewed.filter(p => p.id !== product.id);
                
                // Add to beginning
                viewed.unshift(product);
                
                // Keep only last 10
                viewed = viewed.slice(0, 10);
                
                localStorage.setItem('recentlyViewedProducts', JSON.stringify(viewed));
            },
            
            clearHistory() {
                localStorage.removeItem('recentlyViewedProducts');
                this.products = [];
            }
        }
    }
</script>
@endpush

