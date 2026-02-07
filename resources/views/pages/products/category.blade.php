@extends('layouts.app')

@section('title', $category->getTranslation('name', app()->getLocale()) . ' - ' . __('Products') . ($products->currentPage() > 1 ? ' - ' . __('Page') . ' ' . $products->currentPage() : '') . ' - ' . config('app.name'))
@section('meta_description', \Illuminate\Support\Str::limit(strip_tags($category->getTranslation('description', app()->getLocale()) ?? __('Explore our quality steel products')), 155))

@php
    $canonicalUrl = route('products.category', $category->slug);
@endphp

@push('meta')
    @if($products->currentPage() > 1)
        <meta name="robots" content="noindex, follow">
    @endif
    @if($products->previousPageUrl())
        <link rel="prev" href="{{ $products->previousPageUrl() }}">
    @endif
    @if($products->nextPageUrl())
        <link rel="next" href="{{ $products->nextPageUrl() }}">
    @endif
@endpush

@section('content')
    {{-- Clean Modern Corporate Hero --}}
    <section class="relative bg-gradient-to-br from-slate-900 via-primary-900 to-primary-800 overflow-hidden">
        {{-- Subtle Pattern Overlay --}}
        <div class="absolute inset-0 opacity-[0.03]" style="background-image: url('data:image/svg+xml,%3Csvg width=\"60\" height=\"60\" viewBox=\"0 0 60 60\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cg fill=\"none\" fill-rule=\"evenodd\"%3E%3Cg fill=\"%23ffffff\" fill-opacity=\"1\"%3E%3Cpath d=\"M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\"/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
        
        {{-- Gradient Orb Decorations --}}
        <div class="absolute -top-24 -right-24 w-96 h-96 bg-primary-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-secondary-500/10 rounded-full blur-3xl"></div>
        
        <div class="container relative z-10">
            {{-- Breadcrumb --}}
            <nav class="pt-8 pb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 text-sm text-white/60">
                    <li><a href="{{ route('home') }}" class="hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li class="text-white/40">/</li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-white transition-colors">{{ __('Products') }}</a></li>
                    @foreach($breadcrumbs as $crumb)
                        <li class="text-white/40">/</li>
                        @if($crumb['url'])
                            <li><a href="{{ $crumb['url'] }}" class="hover:text-white transition-colors">{{ $crumb['name'] }}</a></li>
                        @else
                            <li class="text-white font-medium">{{ $crumb['name'] }}</li>
                        @endif
                    @endforeach
                </ol>
            </nav>
            
            {{-- Main Hero Content --}}
            <div class="py-12 md:py-16 lg:py-20">
                <div class="max-w-4xl">
                    {{-- Category Title --}}
                    <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold text-white mb-6 leading-tight">
                        {{ $category->getTranslation('name', app()->getLocale()) }}
                    </h1>
                    
                    {{-- Description - Cleaned --}}
                    @if($category->description)
                        <p class="text-lg md:text-xl text-white/75 leading-relaxed max-w-3xl">
                            {{ strip_tags($category->getTranslation('description', app()->getLocale())) }}
                        </p>
                    @endif
                    
                    {{-- Stats Row --}}
                    <div class="flex items-center gap-8 mt-10">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">{{ $category->children->count() }}</div>
                                <div class="text-sm text-white/60">{{ __('Subcategories') }}</div>
                            </div>
                        </div>
                        
                        <div class="w-px h-12 bg-white/20"></div>
                        
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 rounded-xl bg-white/10 flex items-center justify-center">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold text-white">{{ $products->total() }}</div>
                                <div class="text-sm text-white/60">{{ __('Products') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- Bottom Edge --}}
        <div class="absolute bottom-0 left-0 right-0 h-px bg-gradient-to-r from-transparent via-white/20 to-transparent"></div>
    </section>

    <section class="py-12 md:py-16">
        <div class="container">
            {{-- Subcategories --}}
            @if($category->children->count() > 0)
                <div class="mb-12">
                    <h2 class="text-xl font-semibold mb-4">Subkategori</h2>
                    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                        @foreach($category->children as $child)
                            <a href="{{ route('products.category', $child->slug) }}" class="card text-center p-4 hover:shadow-lg transition-all">
                                <h3 class="font-medium text-sm">{{ $child->getTranslation('name', app()->getLocale()) }}</h3>
                                <p class="text-xs text-gray-500">{{ $child->products_count ?? 0 }} produk</p>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Products --}}
            @if($products->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($products as $product)
                        @include('components.product-card', ['product' => $product])
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $products->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Produk</h3>
                    <p class="text-gray-500">Kategori ini belum memiliki produk.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary mt-4">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </section>
@endsection

