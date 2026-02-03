@extends('layouts.app')

@section('title', __('Home') . ' - ' . ($companyInfo['company_name'] ?? config('app.name')))

{{-- Preload LCP hero image for faster rendering --}}
@push('preload')
@if($heroSlides->count() > 0)
<link rel="preload" href="{{ $heroSlides->first()->image_url }}" as="image" fetchpriority="high">
@endif
@endpush

@section('content')
    {{-- SEO: Main H1 heading (visually hidden but accessible) --}}
    <h1 class="sr-only">{{ $companyInfo['company_name'] ?? config('app.name') }} - {{ $companyInfo['company_tagline'] ?? __('Your Trusted Steel Partner') }}</h1>
    
    {{-- Hero Slider Section --}}
    @if($homepageSections->has('hero') && $homepageSections->get('hero')->is_active)
    @if($heroSlides->count() > 0)
    <section class="relative h-[420px] md:h-[480px] lg:h-[540px] overflow-hidden" x-data="{
        currentSlide: 0,
        totalSlides: {{ $heroSlides->count() }},
        autoplayInterval: null,
        init() {
            this.startAutoplay();
        },
        startAutoplay() {
            if (this.totalSlides > 1) {
                this.autoplayInterval = setInterval(() => {
                    this.nextSlide();
                }, 5000);
            }
        },
        stopAutoplay() {
            clearInterval(this.autoplayInterval);
        },
        nextSlide() {
            this.currentSlide = (this.currentSlide + 1) % this.totalSlides;
        },
        prevSlide() {
            this.currentSlide = (this.currentSlide - 1 + this.totalSlides) % this.totalSlides;
        },
        goToSlide(index) {
            this.currentSlide = index;
            this.stopAutoplay();
            this.startAutoplay();
        }
    }" @mouseenter="stopAutoplay()" @mouseleave="startAutoplay()">
        
        {{-- Slides --}}
        @foreach($heroSlides as $index => $slide)
        <div @if($index === 0)
                x-show="currentSlide === {{ $index }}"
             @else
                x-show="currentSlide === {{ $index }}"
                x-cloak
             @endif
             x-transition:enter="transition ease-out duration-700"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="absolute inset-0">
            
            {{-- Background Image - Use <img> for LCP optimization --}}
            <img 
                src="{{ $slide->image_url }}" 
                alt="{{ $slide->title }}"
                class="absolute inset-0 w-full h-full object-cover"
                @if($index === 0) fetchpriority="high" loading="eager" @else loading="lazy" @endif
            >
            
            {{-- Gradient Overlay --}}
            <div class="absolute inset-0 bg-gradient-to-r {{ $slide->gradient_class }}"></div>
            
            {{-- Decorative Elements --}}
            <div class="absolute inset-0 opacity-10 hidden md:block">
                <div class="absolute top-5 right-5 w-48 h-48 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
                <div class="absolute bottom-5 left-5 w-64 h-64 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 1s"></div>
            </div>
            
            {{-- Content --}}
            <div class="container relative z-10 h-full flex items-center">
                <div class="max-w-2xl text-{{ $slide->text_color }}">
                    @if($slide->badge_text)
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-{{ $slide->text_color }} px-4 py-2 rounded-full text-sm font-medium mb-5 border border-white/20">
                        <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        {{ $slide->badge_text }}
                    </div>
                    @endif
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading mb-4 leading-tight">
                        {{ $slide->title }}
                    </h2>
                    
                    @if($slide->subtitle)
                    <p class="text-base md:text-lg lg:text-xl mb-6 text-{{ $slide->text_color }}/90 leading-relaxed line-clamp-3">
                        {{ $slide->subtitle }}
                    </p>
                    @endif
                    
                    <div class="flex flex-wrap gap-3">
                        @if($slide->primary_button_text && $slide->primary_button_url)
                        <a href="{{ $slide->primary_button_url }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-6 py-3 md:px-7 md:py-3.5 rounded-xl font-semibold text-sm md:text-base hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                            {{ $slide->primary_button_text }}
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                        @endif
                        
                        @if($slide->secondary_button_text && $slide->secondary_button_url)
                        <a href="{{ $slide->secondary_button_url }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-{{ $slide->text_color }} border-2 border-white/30 px-6 py-3 md:px-7 md:py-3.5 rounded-xl font-semibold text-sm md:text-base hover:bg-white/20 transition-all">
                            {{ $slide->secondary_button_text }}
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endforeach
        
        {{-- Navigation Arrows (show only if more than 1 slide) --}}
        @if($heroSlides->count() > 1)
        <button @click="prevSlide()" aria-label="{{ __('Previous slide') }}" class="absolute left-2 md:left-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all border border-white/20 hover:scale-110 group">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        
        <button @click="nextSlide()" aria-label="{{ __('Next slide') }}" class="absolute right-2 md:right-4 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-white/10 backdrop-blur-sm hover:bg-white/20 text-white rounded-full flex items-center justify-center transition-all border border-white/20 hover:scale-110 group">
            <svg class="w-5 h-5 group-hover:translate-x-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>
        
        {{-- Dots Indicator --}}
        <div class="absolute bottom-4 left-1/2 -translate-x-1/2 z-20 flex items-center gap-2" role="tablist" aria-label="{{ __('Slide navigation') }}">
            @foreach($heroSlides as $index => $slide)
            <button @click="goToSlide({{ $index }})" 
                    aria-label="{{ __('Go to slide') }} {{ $index + 1 }}"
                    role="tab"
                    :aria-selected="currentSlide === {{ $index }}"
                    class="transition-all rounded-full"
                    :class="currentSlide === {{ $index }} ? 'w-8 h-2 bg-white' : 'w-2 h-2 bg-white/40 hover:bg-white/60'">
            </button>
            @endforeach
        </div>
        @endif
        
        {{-- Decorative wave --}}
        <div class="absolute bottom-0 left-0 right-0 z-10">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                <path d="M0 50L60 45.7C120 41 240 33 360 35.3C480 38 600 52 720 55C840 58 960 50 1080 43.3C1200 37 1320 33 1380 30.7L1440 28V100H1380C1320 100 1200 100 1080 100C960 100 840 100 720 100C600 100 480 100 360 100C240 100 120 100 60 100H0V50Z" fill="white"/>
            </svg>
        </div>
    </section>
    @else
    {{-- Fallback Hero if no slides --}}
    <section class="relative h-[420px] md:h-[480px] lg:h-[540px] overflow-hidden bg-gradient-to-r from-primary-900 via-primary-800 to-primary-700">
        <div class="container relative z-10 h-full flex items-center">
            <div class="max-w-3xl text-white">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading mb-5 leading-tight">
                    {{ $companyInfo['hero_title'] ?? __('Your Trusted Steel Partner') }}
                </h2>
                <p class="text-base md:text-lg lg:text-xl mb-6 text-white/90 leading-relaxed">
                    {{ $companyInfo['hero_subtitle'] ?? 'Distributor besi baja berkualitas dengan pelayanan terbaik untuk kebutuhan konstruksi dan industri Anda.' }}
                </p>
                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                        @if(app()->getLocale() === 'en') View Products @else Lihat Produk @endif
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 right-0 z-10">
            <svg viewBox="0 0 1440 100" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full h-auto">
                <path d="M0 50L60 45.7C120 41 240 33 360 35.3C480 38 600 52 720 55C840 58 960 50 1080 43.3C1200 37 1320 33 1380 30.7L1440 28V100H1380C1320 100 1200 100 1080 100C960 100 840 100 720 100C600 100 480 100 360 100C240 100 120 100 60 100H0V50Z" fill="white"/>
            </svg>
        </div>
    </section>
    @endif
    @endif
    
    {{-- Stats Bar --}}
    @if($homepageSections->has('stats') && $homepageSections->get('stats')->is_active)
    <section class="py-12 md:py-16 {{ $homepageSections->get('stats')->bg_class }}">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 text-primary-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold text-primary-600 mb-2">500+</div>
                    <div class="text-sm md:text-base text-gray-600">@if(app()->getLocale() === 'en') Happy Clients @else Klien Puas @endif</div>
                </div>
                
                <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 100ms">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary-100 text-secondary-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold text-secondary-600 mb-2">1000+</div>
                    <div class="text-sm md:text-base text-gray-600">@if(app()->getLocale() === 'en') Projects Completed @else Proyek Selesai @endif</div>
                </div>
                
                <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 200ms">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-100 text-accent-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold text-accent-600 mb-2">300+</div>
                    <div class="text-sm md:text-base text-gray-600">@if(app()->getLocale() === 'en') Products @else Produk @endif</div>
                </div>
                
                <div class="text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 300ms">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 text-green-600 rounded-2xl mb-4">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2">34</div>
                    <div class="text-sm md:text-base text-gray-600">@if(app()->getLocale() === 'en') Provinces @else Provinsi @endif</div>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Categories --}}
    @if($homepageSections->has('categories') && $homepageSections->get('categories')->is_active)
    <section class="py-16 md:py-20 {{ $homepageSections->get('categories')->bg_class }}">
        <div class="container">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M3 4a1 1 0 011-1h12a1 1 0 011 1v2a1 1 0 01-1 1H4a1 1 0 01-1-1V4zM3 10a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H4a1 1 0 01-1-1v-6zM14 9a1 1 0 00-1 1v6a1 1 0 001 1h2a1 1 0 001-1v-6a1 1 0 00-1-1h-2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Our Products @else Produk Kami @endif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-4">{{ __('Product Categories') }}</h2>
                    <p class="text-gray-600 max-w-2xl text-lg">
                        @if(app()->getLocale() === 'en') 
                            Various categories of quality steel products to meet your construction and industrial needs
                        @else 
                            Berbagai kategori produk besi baja berkualitas untuk memenuhi kebutuhan konstruksi dan industri Anda
                        @endif
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-primary-600 hover:bg-primary-700 text-white font-semibold rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 group">
                    {{ __('View All Products') }}
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                @foreach($featuredCategories as $category)
                    <a href="{{ route('products.category', $category->slug) }}" 
                       class="group relative bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-2xl hover:-translate-y-2 transition-all duration-300 overflow-hidden" 
                       x-data 
                       x-intersect.once="$el.classList.add('animate-fade-in-up')">
                        
                        {{-- Decorative corner --}}
                        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-primary-100 to-transparent rounded-bl-full opacity-0 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="aspect-[4/3] bg-gray-100 overflow-hidden">
                            @if($category->icon)
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
                                    <img src="{{ asset('storage/' . $category->icon) }}" 
                                         alt="{{ $category->getTranslation('name', app()->getLocale()) }}" 
                                         class="w-20 h-20 object-contain"
                                         width="80" height="80" loading="lazy">
                                </div>
                            @elseif($category->image)
                                <img src="{{ asset('storage/' . $category->image) }}" 
                                     alt="{{ $category->getTranslation('name', app()->getLocale()) }}" 
                                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                     width="320" height="240" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-primary-50 to-primary-100">
                                    <svg class="w-12 h-12 text-primary-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <div class="p-5">
                            <h3 class="font-bold text-gray-900 group-hover:text-primary-600 transition-colors mb-1">
                                {{ $category->getTranslation('name', app()->getLocale()) }}
                            </h3>
                            <p class="text-sm text-gray-500 flex items-center gap-1">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                                {{ $category->products_count }} {{ __('Products') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Featured Products --}}
    @if($homepageSections->has('featured') && $homepageSections->get('featured')->is_active)
    <section class="py-16 md:py-20 {{ $homepageSections->get('featured')->bg_class ?: 'bg-white' }}">
        <div class="container">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Best Seller @else Terlaris @endif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-2">{{ __('Featured Products') }}</h2>
                    <p class="text-gray-600 text-lg">
                        @if(app()->getLocale() === 'en') Featured products with the best quality @else Produk unggulan dengan kualitas terbaik @endif
                    </p>
                </div>
                <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                    {{ __('View All') }}
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($featuredProducts as $product)
                    @include('components.product-card', ['product' => $product])
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Why Choose Us --}}
    @if($homepageSections->has('why_us') && $homepageSections->get('why_us')->is_active)
    <section class="py-16 md:py-20 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-4 border border-white/20">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Why Choose Us @else Keunggulan Kami @endif
                </div>
                <h2 class="text-3xl md:text-4xl font-bold font-heading mb-4">{{ __('Why Choose Us') }}</h2>
                <p class="text-white/90 max-w-2xl mx-auto text-lg">
                    @if(app()->getLocale() === 'en') 
                        We are committed to providing the best products and services for every customer
                    @else 
                        Kami berkomitmen memberikan produk dan layanan terbaik untuk setiap pelanggan
                    @endif
                </p>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <div class="w-16 h-16 bg-white text-primary-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3">
                        @if(app()->getLocale() === 'en') Quality Guaranteed @else Kualitas Terjamin @endif
                    </h3>
                    <p class="text-white/80 leading-relaxed">
                        @if(app()->getLocale() === 'en') 
                            All our products have gone through strict quality control and are SNI certified
                        @else 
                            Semua produk kami telah melalui quality control yang ketat dan bersertifikasi SNI
                        @endif
                    </p>
                </div>
                <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 100ms">
                    <div class="w-16 h-16 bg-white text-secondary-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3">
                        @if(app()->getLocale() === 'en') Fast Delivery @else Pengiriman Cepat @endif
                    </h3>
                    <p class="text-white/80 leading-relaxed">
                        @if(app()->getLocale() === 'en') 
                            Fast delivery service throughout Indonesia with our own fleet
                        @else 
                            Layanan pengiriman cepat ke seluruh Indonesia dengan armada sendiri
                        @endif
                    </p>
                </div>
                <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 200ms">
                    <div class="w-16 h-16 bg-white text-accent-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3">
                        @if(app()->getLocale() === 'en') Competitive Prices @else Harga Kompetitif @endif
                    </h3>
                    <p class="text-white/80 leading-relaxed">
                        @if(app()->getLocale() === 'en') 
                            Competitive prices directly from authorized distributors without intermediaries
                        @else 
                            Harga bersaing langsung dari distributor resmi tanpa perantara
                        @endif
                    </p>
                </div>
                <div class="group bg-white/10 backdrop-blur-sm rounded-2xl p-6 border border-white/20 hover:bg-white/20 transition-all duration-300 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: 300ms">
                    <div class="w-16 h-16 bg-white text-green-600 rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-xl mb-3">
                        @if(app()->getLocale() === 'en') Support 24/7 @else Support 24/7 @endif
                    </h3>
                    <p class="text-white/80 leading-relaxed">
                        @if(app()->getLocale() === 'en') 
                            Customer service team ready to help you whenever needed
                        @else 
                            Tim customer service siap membantu Anda kapanpun dibutuhkan
                        @endif
                    </p>
                </div>
            </div>
        </div>
    </section>
    @endif

    {{-- New Products - Hidden for now --}}
    {{-- @if($newProducts->count() > 0)
    <section class="py-16 md:py-20 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900 text-white relative overflow-hidden">
        {{-- Animated background --}}
        {{-- <div class="absolute inset-0 opacity-5">
            <div class="absolute top-0 left-1/4 w-72 h-72 bg-primary-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-secondary-500 rounded-full mix-blend-screen filter blur-3xl animate-pulse" style="animation-delay: 1.5s"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-4 border border-white/20">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') New Arrivals @else Produk Terbaru @endif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold font-heading mb-2">{{ __('New Products') }}</h2>
                    <p class="text-white/80 text-lg">
                        @if(app()->getLocale() === 'en') Our latest steel products collection @else Koleksi terbaru produk besi baja kami @endif
                    </p>
                </div>
                <a href="{{ route('products.index') }}?sort=newest" class="inline-flex items-center gap-2 bg-white text-gray-900 px-6 py-3 rounded-xl font-semibold hover:bg-gray-100 transition-all hover:shadow-xl hover:scale-105">
                    {{ __('View All') }}
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @foreach($newProducts as $product)
                    @include('components.product-card', ['product' => $product, 'dark' => true])
                @endforeach
            </div>
        </div>
    </section>
    @endif --}}

    {{-- Clients --}}
    @if($homepageSections->has('clients') && $homepageSections->get('clients')->is_active)
    @if($clients->count() > 0)
    <section class="py-16 md:py-20 {{ $homepageSections->get('clients')->bg_class }}">
        <div class="container">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Our Partners @else Partner Kami @endif
                </div>
                <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-4">{{ __('Trusted By') }}</h2>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">
                    @if(app()->getLocale() === 'en') 
                        Trusted by various leading companies and contractors in Indonesia
                    @else 
                        Dipercaya oleh berbagai perusahaan dan kontraktor terkemuka di Indonesia
                    @endif
                </p>
            </div>
            <div class="bg-white rounded-3xl shadow-lg p-8 md:p-12 border border-gray-100 overflow-hidden">
                {{-- Auto-scrolling logo slider --}}
                <div class="relative" x-data="{ isPaused: false }">
                    <div class="flex animate-scroll-left hover:[animation-play-state:paused]"
                         @mouseenter="isPaused = true" 
                         @mouseleave="isPaused = false"
                         :class="{ '[animation-play-state:paused]': isPaused }">
                        {{-- First set of logos --}}
                        @foreach($clients as $client)
                            <div class="flex-shrink-0 mx-4 grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100 hover:scale-105">
                                <div class="rounded-xl p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow border border-gray-100"
                                     style="background-color: {{ $client->bg_color ?? '#ffffff' }}">
                                    @if($client->logo_url)
                                        <img src="{{ $client->logo_url }}" 
                                             alt="{{ $client->name }} - Client Logo" 
                                             class="h-14 md:h-20 w-auto min-w-[120px] max-w-[180px] object-contain mx-auto" 
                                             width="180" 
                                             height="80" 
                                             loading="lazy"
                                             decoding="async">
                                    @else
                                        <span class="text-gray-500 font-semibold text-sm block text-center whitespace-nowrap px-4">{{ $client->name }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                        {{-- Duplicate set for seamless loop --}}
                        @foreach($clients as $client)
                            <div class="flex-shrink-0 mx-4 grayscale hover:grayscale-0 transition-all duration-300 opacity-70 hover:opacity-100 hover:scale-105">
                                <div class="rounded-xl p-5 md:p-6 shadow-sm hover:shadow-md transition-shadow border border-gray-100"
                                     style="background-color: {{ $client->bg_color ?? '#ffffff' }}">
                                    @if($client->logo_url)
                                        <img src="{{ $client->logo_url }}" 
                                             alt="{{ $client->name }} - Client Logo" 
                                             class="h-14 md:h-20 w-auto min-w-[120px] max-w-[180px] object-contain mx-auto" 
                                             width="180" 
                                             height="80" 
                                             loading="lazy"
                                             decoding="async">
                                    @else
                                        <span class="text-gray-500 font-semibold text-sm block text-center whitespace-nowrap px-4">{{ $client->name }}</span>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Marketplace Section --}}
    @if($homepageSections->has('marketplace') && $homepageSections->get('marketplace')->is_active)
    @php
        $marketplaceSettings = app(\App\Services\SettingService::class)->getMarketplaceLinks();
        $showMarketplace = $marketplaceSettings['show_marketplace_homepage'] ?? false;
        $marketplaces = [
            ['key' => 'shopee_url', 'name' => 'Shopee', 'color' => 'bg-orange-500', 'icon' => 'shopee'],
            ['key' => 'tokopedia_url', 'name' => 'Tokopedia', 'color' => 'bg-green-500', 'icon' => 'tokopedia'],
            ['key' => 'tiktok_shop_url', 'name' => 'TikTok Shop', 'color' => 'bg-black', 'icon' => 'tiktok'],
            ['key' => 'lazada_url', 'name' => 'Lazada', 'color' => 'bg-blue-800', 'icon' => 'lazada'],
            ['key' => 'blibli_url', 'name' => 'Blibli', 'color' => 'bg-blue-600', 'icon' => 'blibli'],
        ];
        $activeMarketplaces = collect($marketplaces)->filter(fn($m) => !empty($marketplaceSettings[$m['key']]));
    @endphp
    @if($showMarketplace && $activeMarketplaces->count() > 0)
    <section class="py-12 md:py-16 bg-gradient-to-r from-primary-50 to-secondary-50">
        <div class="container">
            <div class="text-center mb-10">
                <h2 class="text-2xl md:text-3xl font-bold font-heading text-gray-900 mb-3">
                    @if(app()->getLocale() === 'en') Shop at Our Official Store @else Belanja di Toko Resmi Kami @endif
                </h2>
                <p class="text-gray-600">
                    @if(app()->getLocale() === 'en') 
                        Also available at these marketplaces
                    @else 
                        Tersedia juga di marketplace berikut
                    @endif
                </p>
            </div>
            <div class="flex flex-wrap justify-center gap-4 md:gap-6">
                @foreach($activeMarketplaces as $mp)
                    <a href="{{ $marketplaceSettings[$mp['key']] }}" target="_blank" rel="noopener noreferrer" class="group flex items-center gap-3 bg-white px-6 py-4 rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 hover:-translate-y-1 border border-gray-100">
                        <div class="w-10 h-10 rounded-lg flex items-center justify-center overflow-hidden">
                            <img src="/images/marketplaces/{{ $mp['icon'] }}.png" alt="{{ $mp['name'] }}" class="w-full h-full object-contain" width="40" height="40" loading="lazy">
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors">{{ $mp['name'] }}</div>
                            <div class="text-xs text-gray-500">{{ __('Shop Now') }}</div>
                        </div>
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Testimonials --}}
    @if($homepageSections->has('testimonials') && $homepageSections->get('testimonials')->is_active)
    @if($testimonials->count() > 0)
    <section class="py-16 md:py-20 {{ $homepageSections->get('testimonials')->bg_class ?: 'bg-white' }}">
        <div class="container">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Testimonials @else Testimoni @endif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-2">{{ __('Client Testimonials') }}</h2>
                    <p class="text-gray-600 text-lg">
                        @if(app()->getLocale() === 'en') 
                            What our customers say about our products and services
                        @else 
                            Apa kata pelanggan kami tentang produk dan layanan kami
                        @endif
                    </p>
                </div>
                <a href="{{ route('testimonials') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                    {{ __('View All') }}
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($testimonials as $testimonial)
                    <div class="group relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-2xl transition-all duration-300 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                        {{-- Quote icon --}}
                        <div class="absolute top-6 right-6 opacity-10 group-hover:opacity-20 transition-opacity">
                            <svg class="w-12 h-12 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                            </svg>
                        </div>
                        
                        <div class="flex items-center gap-4 mb-4">
                            @if($testimonial->author_photo)
                                <img src="{{ Storage::url($testimonial->author_photo) }}" alt="{{ $testimonial->author_name }}" class="w-14 h-14 rounded-full object-cover ring-2 ring-primary-100" width="56" height="56" loading="lazy">
                            @else
                                <div class="w-14 h-14 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-white flex items-center justify-center font-bold text-xl ring-2 ring-primary-100">
                                    {{ substr($testimonial->author_name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $testimonial->author_name }}</h4>
                                <p class="text-sm text-gray-500">{{ $testimonial->getTranslation('author_position', app()->getLocale()) }}@if($testimonial->author_company), {{ $testimonial->author_company }}@endif</p>
                                @if($testimonial->getTranslation('project_name', app()->getLocale()))
                                    <p class="text-xs text-gray-400 mt-0.5">{{ __('Project') }}: {{ $testimonial->getTranslation('project_name', app()->getLocale()) }}</p>
                                @endif
                            </div>
                        </div>
                        
                        <div class="flex gap-1 mb-4">
                            @for($i = 0; $i < 5; $i++)
                                <svg class="w-5 h-5 {{ $i < $testimonial->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            @endfor
                        </div>
                        
                        <p class="text-gray-600 italic leading-relaxed">"{{ $testimonial->getTranslation('content', app()->getLocale()) }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Latest Articles --}}
    @if($homepageSections->has('articles') && $homepageSections->get('articles')->is_active)
    @if($latestArticles->count() > 0)
    <section class="py-16 md:py-20 {{ $homepageSections->get('articles')->bg_class }}">
        <div class="container">
            <div class="flex flex-wrap items-end justify-between gap-4 mb-12">
                <div>
                    <div class="inline-flex items-center gap-2 bg-accent-100 text-accent-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M2 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 002 2H4a2 2 0 01-2-2V5zm3 1h6v4H5V6zm6 6H5v2h6v-2z" clip-rule="evenodd"/>
                            <path d="M15 7h1a2 2 0 012 2v5.5a1.5 1.5 0 01-3 0V7z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Blog & News @else Blog & Berita @endif
                    </div>
                    <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-2">{{ __('Latest Articles') }}</h2>
                    <p class="text-gray-600 text-lg">
                        @if(app()->getLocale() === 'en') 
                            Latest news and articles about steel industry
                        @else 
                            Berita dan artikel terbaru seputar industri besi baja
                        @endif
                    </p>
                </div>
                <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 bg-primary-600 text-white px-6 py-3 rounded-xl font-semibold hover:bg-primary-700 transition-all hover:shadow-lg hover:scale-105">
                    {{ __('View All') }}
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </a>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($latestArticles as $article)
                    @include('components.article-card', ['article' => $article])
                @endforeach
            </div>
        </div>
    </section>
    @endif
    @endif

    {{-- Area Layanan Section - Local & National SEO --}}
    @if($homepageSections->has('service_areas') && $homepageSections->get('service_areas')->is_active)
    <section class="py-16 md:py-20 {{ $homepageSections->get('service_areas')->bg_class }}">
        <div class="container">
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Service Coverage @else Jangkauan Layanan @endif
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold font-heading text-gray-900 mb-4">
                    @if(app()->getLocale() === 'en') 
                        Steel Supplier Serving All of Indonesia
                    @else 
                        Supplier Besi Baja Melayani Seluruh Indonesia
                    @endif
                </h2>
                
                <p class="text-lg text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en') 
                        Based in Surabaya with national coverage. We serve wholesale and retail customers - from large construction projects to individual purchases.
                    @else 
                        Berbasis di Surabaya dengan jangkauan nasional. Kami melayani pembelian partai besar maupun eceran - dari proyek konstruksi besar hingga pembelian satuan.
                    @endif
                </p>
            </div>
            
            {{-- Market Segments --}}
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10">
                @php
                    $segments = [
                        ['name_id' => 'Partai Besar', 'name_en' => 'Wholesale', 'desc_id' => 'Grosir & Distributor', 'desc_en' => 'Bulk Orders', 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10'],
                        ['name_id' => 'Eceran', 'name_en' => 'Retail', 'desc_id' => 'Pembelian Satuan', 'desc_en' => 'Individual Orders', 'icon' => 'M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z'],
                        ['name_id' => 'Proyek', 'name_en' => 'Projects', 'desc_id' => 'Konstruksi & Bangunan', 'desc_en' => 'Construction', 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4'],
                        ['name_id' => 'Industri', 'name_en' => 'Industrial', 'desc_id' => 'Manufaktur & Pabrik', 'desc_en' => 'Manufacturing', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                    ];
                @endphp
                
                @foreach($segments as $segment)
                    <div class="bg-white rounded-xl p-5 text-center shadow-sm hover:shadow-md transition-shadow border border-gray-200">
                        <div class="w-12 h-12 mx-auto mb-3 rounded-full bg-secondary-100 text-secondary-600 flex items-center justify-center">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $segment['icon'] }}"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-gray-900">{{ app()->getLocale() === 'en' ? $segment['name_en'] : $segment['name_id'] }}</h3>
                        <p class="text-sm text-gray-500">{{ app()->getLocale() === 'en' ? $segment['desc_en'] : $segment['desc_id'] }}</p>
                    </div>
                @endforeach
            </div>

            {{-- Primary Service Area - Jawa Timur --}}
            <div class="bg-white rounded-2xl p-6 md:p-8 shadow-sm border border-primary-200 mb-6">
                <div class="flex items-start gap-4 mb-4">
                    <div class="w-12 h-12 rounded-full bg-primary-100 text-primary-600 flex items-center justify-center flex-shrink-0">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <h3 class="text-xl font-bold text-gray-900">
                                @if(app()->getLocale() === 'en') Primary Service Area @else Area Layanan Utama @endif
                            </h3>
                            <span class="bg-primary-100 text-primary-700 text-xs font-semibold px-2 py-1 rounded-full">
                                @if(app()->getLocale() === 'en') Fast Delivery @else Pengiriman Cepat @endif
                            </span>
                        </div>
                        <p class="text-gray-600 mb-3">
                            @if(app()->getLocale() === 'en') 
                                East Java region with same-day/next-day delivery options
                            @else 
                                Wilayah Jawa Timur dengan opsi pengiriman hari yang sama / hari berikutnya
                            @endif
                        </p>
                        <div class="flex flex-wrap gap-2">
                            @foreach(['Surabaya', 'Sidoarjo', 'Gresik', 'Mojokerto', 'Jombang', 'Pasuruan', 'Lamongan', 'Tuban', 'Malang', 'Kediri'] as $city)
                                <span class="bg-gray-100 text-gray-700 text-sm px-3 py-1 rounded-full">{{ $city }}</span>
                            @endforeach
                            <span class="text-gray-500 text-sm py-1">@if(app()->getLocale() === 'en') + all East Java @else + seluruh Jawa Timur @endif</span>
                        </div>
                    </div>
                </div>
            </div>
            
            {{-- National Coverage --}}
            <div class="bg-gradient-to-br from-secondary-50 to-white rounded-2xl p-6 md:p-8 shadow-sm border border-secondary-200">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">
                    <div class="flex items-start gap-4">
                        <div class="w-12 h-12 rounded-full bg-secondary-100 text-secondary-600 flex items-center justify-center flex-shrink-0">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM4.332 8.027a6.012 6.012 0 011.912-2.706C6.512 5.73 6.974 6 7.5 6A1.5 1.5 0 019 7.5V8a2 2 0 004 0 2 2 0 011.523-1.943A5.977 5.977 0 0116 10c0 .34-.028.675-.083 1H15a2 2 0 00-2 2v2.197A5.973 5.973 0 0110 16v-2a2 2 0 00-2-2 2 2 0 01-2-2 2 2 0 00-1.668-1.973z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-gray-900 mb-2">
                                @if(app()->getLocale() === 'en') 
                                    Nationwide Delivery Across Indonesia
                                @else 
                                    Pengiriman ke Seluruh Indonesia
                                @endif
                            </h3>
                            <p class="text-gray-600 mb-3">
                                @if(app()->getLocale() === 'en') 
                                    We ship to all provinces: Java, Sumatra, Kalimantan, Sulawesi, Bali, Nusa Tenggara, Papua and other islands.
                                @else 
                                    Kami mengirim ke seluruh provinsi: Jawa, Sumatera, Kalimantan, Sulawesi, Bali, Nusa Tenggara, Papua dan pulau lainnya.
                                @endif
                            </p>
                            <div class="flex flex-wrap gap-2">
                                @foreach(['Jawa', 'Sumatera', 'Kalimantan', 'Sulawesi', 'Bali & NTT', 'Papua'] as $region)
                                    <span class="bg-secondary-100 text-secondary-700 text-sm px-3 py-1 rounded-full">{{ $region }}</span>
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-secondary-600 hover:bg-secondary-700 text-white px-6 py-3 rounded-xl font-semibold transition-colors whitespace-nowrap">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Request Quote @else Minta Penawaran @endif
                    </a>
                </div>
            </div>
            
            {{-- SEO Text --}}
            <div class="mt-8 text-center">
                <p class="text-sm text-gray-500 max-w-4xl mx-auto">
                    @if(app()->getLocale() === 'en') 
                        As a trusted steel supplier in Indonesia, we provide various steel products including H-beam, WF, channel, angle bar, plate, pipe, and other iron materials for construction and industrial needs. Both wholesale and retail orders are welcome with competitive prices.
                    @else 
                        Sebagai supplier besi baja terpercaya di Indonesia, kami menyediakan berbagai produk baja termasuk H-beam, WF, kanal, siku, plat, pipa, dan material besi lainnya untuk kebutuhan konstruksi dan industri. Melayani pembelian partai besar maupun eceran dengan harga kompetitif.
                    @endif
                </p>
            </div>
        </div>
    </section>
    @endif

    {{-- CTA Section --}}
    @if($homepageSections->has('cta') && $homepageSections->get('cta')->is_active)
    @php
        $socialLinks = app(\App\Services\SettingService::class)->getSocialLinks();
        $whatsappNumber = preg_replace('/[^0-9]/', '', $socialLinks['whatsapp'] ?? '');
    @endphp
    <section class="py-16 md:py-20 bg-gradient-to-br from-secondary-600 via-secondary-500 to-secondary-400 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-10 left-10 w-80 h-80 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 1s"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-semibold mb-6 border border-white/20">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Let's Work Together @else Mari Bekerja Sama @endif
                </div>
                
                <h2 class="text-3xl md:text-5xl font-bold font-heading mb-6">
                    @if(app()->getLocale() === 'en') 
                        Need a Special Offer?
                    @else 
                        Butuh Penawaran Khusus?
                    @endif
                </h2>
                
                <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto mb-8 leading-relaxed">
                    @if(app()->getLocale() === 'en') 
                        Contact our team to get the best price quote according to your project needs. We provide competitive prices and flexible payment terms.
                    @else 
                        Hubungi tim kami untuk mendapatkan penawaran harga terbaik sesuai kebutuhan proyek Anda. Kami menyediakan harga kompetitif dan sistem pembayaran yang fleksibel.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 bg-white text-secondary-600 px-8 py-4 rounded-xl font-bold hover:bg-gray-100 transition-all hover:shadow-2xl hover:scale-105 text-lg">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        {{ __('Request Quote') }}
                    </a>
                    <a href="https://wa.me/{{ $whatsappNumber }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 hover:bg-green-600 text-white px-8 py-4 rounded-xl font-bold transition-all hover:shadow-2xl hover:scale-105 text-lg">
                        <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Chat WhatsApp @else Chat WhatsApp @endif
                    </a>
                </div>
                
                {{-- Feature badges --}}
                <div class="flex flex-wrap justify-center gap-6 mt-10">
                    <div class="flex items-center gap-2 text-white/90">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">@if(app()->getLocale() === 'en') Fast Response @else Respon Cepat @endif</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/90">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">@if(app()->getLocale() === 'en') Free Consultation @else Konsultasi Gratis @endif</span>
                    </div>
                    <div class="flex items-center gap-2 text-white/90">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        <span class="font-semibold">@if(app()->getLocale() === 'en') Best Prices @else Harga Terbaik @endif</span>
                    </div>
                </div>
            </div>
        </div>
    </section>
    @endif
@endsection

