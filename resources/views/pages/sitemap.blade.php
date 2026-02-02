@extends('layouts.app')

@section('title')
    @if(app()->getLocale() === 'en')
        Sitemap - {{ config('app.name') }}
    @else
        Peta Situs - {{ config('app.name') }}
    @endif
@endsection

@section('content')
    {{-- Hero Section with Overlay --}}
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white py-16 md:py-20 overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse" style="animation-delay: 1s"></div>
        </div>
        
        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 opacity-5" style="background-image: url('data:image/svg+xml,%3Csvg width=&quot;60&quot; height=&quot;60&quot; viewBox=&quot;0 0 60 60&quot; xmlns=&quot;http://www.w3.org/2000/svg&quot;%3E%3Cg fill=&quot;none&quot; fill-rule=&quot;evenodd&quot;%3E%3Cg fill=&quot;%23ffffff&quot; fill-opacity=&quot;0.4&quot;%3E%3Cpath d=&quot;M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z&quot;/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>

        {{-- Map decorative icon --}}
        <div class="absolute top-1/2 right-10 -translate-y-1/2 opacity-5 hidden xl:block">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M20.5 3l-.16.03L15 5.1 9 3 3.36 4.9c-.21.07-.36.25-.36.48V20.5c0 .28.22.5.5.5l.16-.03L9 18.9l6 2.1 5.64-1.9c.21-.07.36-.25.36-.48V3.5c0-.28-.22-.5-.5-.5zM15 19l-6-2.11V5l6 2.11V19z"/>
            </svg>
        </div>
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">
                        @if(app()->getLocale() === 'en') Sitemap @else Peta Situs @endif
                    </li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Website Navigation @else Navigasi Website @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Site <span class="text-secondary-400">Map</span>
                    @else
                        Peta <span class="text-secondary-400">Situs</span>
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Browse all pages and content on our website. Find what you're looking for quickly and easily.
                    @else
                        Jelajahi semua halaman dan konten di website kami. Temukan apa yang Anda cari dengan cepat dan mudah.
                    @endif
                </p>
            </div>
        </div>
    </section>
    
    {{-- Quick Stats --}}
    <section class="py-8 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-2xl text-gray-900">6+</h3>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Main Pages @else Halaman Utama @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-2xl text-gray-900">{{ $categories->count() }}</h3>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Product Categories @else Kategori Produk @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-2xl text-gray-900">{{ $articles->count() }}</h3>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Articles @else Artikel @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-2xl text-gray-900">3</h3>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Info Pages @else Halaman Info @endif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                <!-- Main Pages -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-primary-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-gray-900">
                            @if(app()->getLocale() === 'en') Main Pages @else Halaman Utama @endif
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('home') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Home') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.company') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('About Us') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.vision-mission') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Vision & Mission') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.team') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Our Team') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('about.certificates') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Certificates') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('contact') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Contact Us') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('quote') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Get Quote') }}
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Products -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-secondary-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-gray-900">{{ __('Products') }}</h2>
                    </div>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('products.index') }}" class="text-gray-900 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all font-medium">
                                <svg class="w-4 h-4 text-primary-500 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                @if(app()->getLocale() === 'en') All Products @else Semua Produk @endif
                            </a>
                        </li>
                        @foreach ($categories as $category)
                        <li>
                            <a href="{{ route('products.category', $category->slug) }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ $category->name }}
                            </a>
                            @if ($category->children->count() > 0)
                            <ul class="ml-6 mt-2 space-y-1">
                                @foreach ($category->children as $child)
                                <li>
                                    <a href="{{ route('products.category', $child->slug) }}" class="text-gray-500 hover:text-primary-600 text-sm flex items-center gap-2 group py-1 px-2 rounded hover:bg-gray-50 transition-all">
                                        <svg class="w-3 h-3 text-gray-300 group-hover:text-primary-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                        </svg>
                                        {{ $child->name }}
                                    </a>
                                </li>
                                @endforeach
                            </ul>
                            @endif
                        </li>
                        @endforeach
                    </ul>
                </div>

                <!-- Articles -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-green-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-green-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-gray-900">{{ __('Articles') }}</h2>
                    </div>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('articles.index') }}" class="text-gray-900 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all font-medium">
                                <svg class="w-4 h-4 text-green-500 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                @if(app()->getLocale() === 'en') All Articles @else Semua Artikel @endif
                            </a>
                        </li>
                        @foreach ($articles->take(8) as $article)
                        <li>
                            <a href="{{ route('articles.show', $article->slug) }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                <span class="truncate">{{ Str::limit($article->title, 35) }}</span>
                            </a>
                        </li>
                        @endforeach
                        @if($articles->count() > 8)
                        <li>
                            <a href="{{ route('articles.index') }}" class="text-primary-600 hover:text-primary-700 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all font-medium text-sm">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                </svg>
                                @if(app()->getLocale() === 'en') View all {{ $articles->count() }} articles @else Lihat semua {{ $articles->count() }} artikel @endif
                            </a>
                        </li>
                        @endif
                    </ul>
                </div>

                <!-- Legal & Others -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center flex-shrink-0 shadow-lg shadow-purple-200">
                            <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="font-heading font-bold text-lg text-gray-900">
                            @if(app()->getLocale() === 'en') Information @else Informasi @endif
                        </h2>
                    </div>
                    <ul class="space-y-3">
                        <li>
                            <a href="{{ route('privacy') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Privacy Policy') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('terms') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Terms & Conditions') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('search') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Search') }}
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('testimonials') }}" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all">
                                <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                                {{ __('Testimonials') }}
                            </a>
                        </li>
                    </ul>
                    
                    {{-- XML Sitemap for SEO --}}
                    <div class="mt-6 pt-6 border-t border-gray-100">
                        <p class="text-xs text-gray-500 mb-3">@if(app()->getLocale() === 'en') For Search Engines: @else Untuk Mesin Pencari: @endif</p>
                        <a href="{{ url('sitemap.xml') }}" target="_blank" class="text-gray-600 hover:text-primary-600 flex items-center gap-3 group p-2 rounded-lg hover:bg-primary-50 transition-all text-sm">
                            <svg class="w-4 h-4 text-gray-400 group-hover:text-primary-600 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/>
                            </svg>
                            sitemap.xml
                            <svg class="w-3 h-3 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    {{-- CTA Section --}}
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 py-16 md:py-20 overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8-3a1 1 0 00-.867.5 1 1 0 11-1.731-1A3 3 0 0113 8a3.001 3.001 0 01-2 2.83V11a1 1 0 11-2 0v-1a1 1 0 011-1 1 1 0 100-2zm0 8a1 1 0 100-2 1 1 0 000 2z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Can't Find What You're Looking For? @else Tidak Menemukan yang Dicari? @endif
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 font-heading">
                    @if(app()->getLocale() === 'en')
                        Use Our <span class="text-secondary-400">Search</span>
                    @else
                        Gunakan <span class="text-secondary-400">Pencarian</span> Kami
                    @endif
                </h2>
                
                <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Search for products, articles, or any information you need on our website.
                    @else
                        Cari produk, artikel, atau informasi apapun yang Anda butuhkan di website kami.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('search') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Search Now @else Cari Sekarang @endif
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

