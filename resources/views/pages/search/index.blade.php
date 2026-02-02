@extends('layouts.app')

@section('title', __('Search Results') . ' - ' . config('app.name'))

@section('content')
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-900 to-primary-700 text-white py-16 md:py-20">
        <div class="container">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white">{{ __('Home') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ __('Search Results') }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading">{{ __('Search Results') }}</h1>
            @if($query)
                <p class="text-lg text-primary-200 mt-4">Hasil pencarian untuk: "{{ $query }}"</p>
            @endif
        </div>
    </section>

    <section class="py-12 md:py-16">
        <div class="container">
            {{-- Search Form --}}
            <div class="max-w-2xl mx-auto mb-12">
                <form action="{{ route('search') }}" method="GET" class="flex gap-4">
                    <div class="relative flex-1">
                        <input type="text" name="q" value="{{ $query }}" placeholder="{{ __('Search products...') }}" class="input w-full pl-12 py-4 text-lg">
                        <svg class="w-6 h-6 text-gray-400 absolute left-4 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <button type="submit" class="btn btn-primary px-8">{{ __('Search') }}</button>
                </form>
                
                {{-- Filter Tabs --}}
                <div class="flex justify-center gap-4 mt-6">
                    <a href="{{ route('search', ['q' => $query, 'type' => 'all']) }}" class="px-4 py-2 rounded-full {{ $type === 'all' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        Semua ({{ $totalResults }})
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'products']) }}" class="px-4 py-2 rounded-full {{ $type === 'products' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('Products') }} ({{ $products->count() }})
                    </a>
                    <a href="{{ route('search', ['q' => $query, 'type' => 'articles']) }}" class="px-4 py-2 rounded-full {{ $type === 'articles' ? 'bg-primary-600 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        {{ __('Articles') }} ({{ $articles->count() }})
                    </a>
                </div>
            </div>

            @if($totalResults > 0)
                {{-- Products --}}
                @if(($type === 'all' || $type === 'products') && $products->count() > 0)
                    <div class="mb-12">
                        <h2 class="text-xl font-bold mb-6">{{ __('Products') }} ({{ $products->count() }})</h2>
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                            @foreach($products as $product)
                                @include('components.product-card', ['product' => $product])
                            @endforeach
                        </div>
                        @if($products->count() >= 20)
                            <div class="text-center mt-6">
                                <a href="{{ route('products', ['search' => $query]) }}" class="btn btn-outline">
                                    Lihat Semua Produk
                                    <svg class="w-4 h-4 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                    </svg>
                                </a>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- Articles --}}
                @if(($type === 'all' || $type === 'articles') && $articles->count() > 0)
                    <div>
                        <h2 class="text-xl font-bold mb-6">{{ __('Articles') }} ({{ $articles->count() }})</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles as $article)
                                @include('components.article-card', ['article' => $article])
                            @endforeach
                        </div>
                        @if($articles->count() >= 20)
                            <div class="text-center mt-6">
                                <a href="{{ route('articles', ['search' => $query]) }}" class="btn btn-outline">
                                    Lihat Semua Artikel
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            @else
                {{-- No Results --}}
                <div class="text-center py-16">
                    <svg class="w-20 h-20 text-gray-300 mx-auto mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    @if($query)
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('No results found') }}</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">Tidak ada hasil yang cocok dengan pencarian "{{ $query }}". Coba gunakan kata kunci yang berbeda.</p>
                    @else
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Masukkan Kata Kunci</h3>
                        <p class="text-gray-500 max-w-md mx-auto mb-6">Ketik kata kunci untuk mencari produk atau artikel yang Anda butuhkan.</p>
                    @endif
                    <div class="flex flex-wrap justify-center gap-4">
                        <a href="{{ route('products.index') }}" class="btn btn-primary">Lihat Produk</a>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline">Lihat Artikel</a>
                    </div>
                </div>
            @endif
        </div>
    </section>
@endsection

