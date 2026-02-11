@extends('layouts.app')

@php
    $pageTitle = __('Articles') . ($articles->currentPage() > 1 ? ' - ' . __('Page') . ' ' . $articles->currentPage() : '') . ' - ' . config('app.name');
    $pageDescription = __('Latest news and articles about steel industry and construction tips');
    $canonicalUrl = request()->url();
@endphp

@section('title', $pageTitle)
@section('meta_description', $pageDescription)

@push('meta')
    {{-- Pagination SEO --}}
    @if($articles->previousPageUrl())
        <link rel="prev" href="{{ $articles->previousPageUrl() }}">
    @endif
    @if($articles->nextPageUrl())
        <link rel="next" href="{{ $articles->nextPageUrl() }}">
    @endif
    
    {{-- Noindex for search/filter/paginated pages is handled via $metaRobots in the layout --}}
@endpush

@section('content')
    {{-- Compact Modern Hero Section --}}
    <section class="bg-white border-b border-gray-100">
        <div class="container py-6">
            {{-- Breadcrumb --}}
            <nav class="text-sm mb-3" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ __('Home Page') }}</a></li>
                    <li><svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="text-gray-900 font-medium">{{ __('Articles') }}</li>
                </ol>
            </nav>
            
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold font-heading text-gray-900">{{ __('Articles') }}</h1>
                    <p class="text-gray-600 mt-1">{{ __('Latest news and articles about steel industry and construction tips') }}</p>
                </div>
                
                {{-- Search (Desktop) --}}
                <form action="{{ route('articles.index') }}" method="GET" class="hidden md:block">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('Search articles...') }}" 
                               class="w-64 pl-10 pr-4 py-2 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 transition-all">
                        <svg class="w-4 h-4 text-gray-500 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Main Content --}}
    <section class="py-8 md:py-10 bg-gray-50" x-data="{ viewMode: 'list' }">
        <div class="container">
            {{-- Mobile Search & Filters --}}
            <div class="mb-6 md:hidden">
                <form action="{{ route('articles.index') }}" method="GET">
                    <div class="relative">
                        <input type="text" name="search" value="{{ request('search') }}" 
                               placeholder="{{ __('Search articles...') }}" 
                               class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg text-sm focus:ring-2 focus:ring-primary-500 focus:border-primary-500 bg-white">
                        <svg class="w-4 h-4 text-gray-400 absolute left-3 top-1/2 -translate-y-1/2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                </form>
            </div>

            {{-- Toolbar: Tags & View Toggle --}}
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                {{-- Tags Filter --}}
                <div class="flex items-center gap-2 overflow-x-auto pb-2 sm:pb-0 scrollbar-hide">
                    <a href="{{ route('articles.index') }}" 
                       class="flex-shrink-0 px-3 py-1.5 rounded-full text-sm font-medium transition-colors {{ !request('tag') && !request()->is('articles/tag/*') ? 'bg-primary-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                        {{ __('All Articles') }}
                    </a>
                    @foreach($tags->take(6) as $tag)
                        <a href="{{ route('articles.tag', $tag->slug) }}" 
                           class="flex-shrink-0 px-3 py-1.5 rounded-full text-sm font-medium transition-colors {{ request()->is('articles/tag/' . $tag->slug) ? 'bg-primary-500 text-white' : 'bg-white text-gray-600 hover:bg-gray-100 border border-gray-200' }}">
                            {{ __('Article') }} {{ $tag->name }}
                        </a>
                    @endforeach
                    @if($tags->count() > 6)
                        <button type="button" x-data x-on:click="$dispatch('open-modal', 'tags-modal')" class="flex-shrink-0 px-3 py-1.5 rounded-full text-sm font-medium bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
                            +{{ $tags->count() - 6 }} {{ __('more') }}
                        </button>
                    @endif
                </div>

                {{-- View Toggle --}}
                <div class="inline-flex items-center bg-white rounded-lg border border-gray-200 p-1 shadow-sm flex-shrink-0">
                    <button @click="viewMode = 'list'" 
                            :class="viewMode === 'list' ? 'bg-primary-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium transition-all"
                            aria-label="{{ __('List view') }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <span class="hidden sm:inline">{{ __('List') }}</span>
                    </button>
                    <button @click="viewMode = 'grid'" 
                            :class="viewMode === 'grid' ? 'bg-primary-500 text-white shadow-sm' : 'text-gray-500 hover:text-gray-700'"
                            class="flex items-center gap-2 px-3 py-1.5 rounded-md text-sm font-medium transition-all"
                            aria-label="{{ __('Grid view') }}">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/>
                        </svg>
                        <span class="hidden sm:inline">{{ __('Grid') }}</span>
                    </button>
                </div>
            </div>

            @if($articles->count() > 0)
                {{-- List View --}}
                <div x-show="viewMode === 'list'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                    <div class="space-y-4">
                        @foreach($articles as $article)
                            <a href="{{ route('articles.show', $article->slug) }}" class="block bg-white rounded-xl border border-gray-100 hover:border-primary-200 hover:shadow-md transition-all duration-300 overflow-hidden group">
                                <div class="flex flex-col sm:flex-row">
                                    {{-- Image --}}
                                    <div class="sm:w-48 md:w-56 flex-shrink-0">
                                        @if($article->featured_image)
                                            <img src="{{ asset('storage/' . $article->featured_image) }}" 
                                                 alt="{{ e($article->getTranslation('title', app()->getLocale())) }}"
                                                 class="w-full h-40 sm:h-full object-cover group-hover:scale-105 transition-transform duration-500"
                                                 width="224"
                                                 height="160"
                                                 loading="lazy"
                                                 decoding="async">
                                        @else
                                            <div class="w-full h-40 sm:h-full bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    {{-- Content --}}
                                    <div class="flex-1 p-4 sm:p-5 flex flex-col justify-between">
                                        <div>
                                            {{-- Tags & Type --}}
                                            <div class="flex items-center gap-2 mb-2">
                                                @if($article->type)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium {{ $article->type === 'news' ? 'bg-red-100 text-red-700' : ($article->type === 'tutorial' ? 'bg-green-100 text-green-700' : 'bg-blue-100 text-blue-700') }}">
                                                        {{ __($article->type === 'news' ? 'News' : ($article->type === 'tutorial' ? 'Tutorial' : 'Article')) }}
                                                    </span>
                                                @endif
                                                @foreach($article->tags->take(2) as $tag)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-600">
                                                        {{ $tag->name }}
                                                    </span>
                                                @endforeach
                                            </div>
                                            
                                            {{-- Title --}}
                                            <h2 class="text-lg font-bold text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2 mb-2">
                                                {{ e($article->getTranslation('title', app()->getLocale())) }}
                                            </h2>
                                            
                                            {{-- Excerpt --}}
                                            <p class="text-gray-600 text-sm line-clamp-2 mb-3">
                                                {{ e($article->getTranslation('excerpt', app()->getLocale()) ?: Str::limit(strip_tags($article->getTranslation('content', app()->getLocale())), 150)) }}
                                            </p>
                                        </div>
                                        
                                        {{-- Meta --}}
                                        <div class="flex items-center gap-4 text-xs text-gray-500">
                                            @if($article->author)
                                                <div class="flex items-center gap-1.5">
                                                    <div class="w-5 h-5 rounded-full bg-primary-500 flex items-center justify-center text-white text-xs font-medium">
                                                        {{ substr($article->author->name, 0, 1) }}
                                                    </div>
                                                    <span>{{ $article->author->name }}</span>
                                                </div>
                                            @endif
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                                <time datetime="{{ $article->published_at?->toISOString() }}">{{ $article->published_at?->format('d M Y') }}</time>
                                            </div>
                                            <div class="flex items-center gap-1">
                                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                </svg>
                                                {{ number_format($article->view_count) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

                {{-- Grid View (hidden duplicate for visual toggle only, aria-hidden to avoid duplicate headings) --}}
                <div x-show="viewMode === 'grid'" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-cloak aria-hidden="true">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($articles as $article)
                            @include('components.article-card', ['article' => $article, 'headingTag' => 'span'])
                        @endforeach
                    </div>
                </div>

                {{-- Pagination --}}
                <div class="mt-8">
                    {{ $articles->links() }}
                </div>
            @else
                {{-- Empty State --}}
                <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                    <h3 class="text-lg font-semibold text-gray-600 mb-2">{{ __('No articles found') }}</h3>
                    <p class="text-gray-500 mb-4">{{ __('No articles match your search criteria.') }}</p>
                    <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-primary-500 text-white rounded-lg hover:bg-primary-600 transition-colors font-medium text-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        {{ __('View All Articles') }}
                    </a>
                </div>
            @endif
        </div>
    </section>
@endsection

