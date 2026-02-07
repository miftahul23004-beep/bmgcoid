@extends('layouts.app')

@php
    use App\Helpers\HtmlSanitizer;
    
    $locale = app()->getLocale();
    $articleTitle = $article->getTranslation('title', $locale);
    $articleExcerpt = $article->getTranslation('excerpt', $locale);
    $articleContent = HtmlSanitizer::sanitize($article->getTranslation('content', $locale));
    $metaDescription = $articleExcerpt ?: Str::limit(strip_tags($articleContent), 160);
    $canonicalUrl = route('articles.show', $article->slug);
    $wordCount = str_word_count(strip_tags($articleContent));
    $readingTime = max(1, ceil($wordCount / 200));
@endphp

@section('title', $articleTitle . ' - ' . config('app.name'))
@section('meta_description', $metaDescription)

@push('meta')
    {{-- Open Graph extras --}}
    <meta property="og:type" content="article">
    @if($article->featured_image)
        <meta property="og:image" content="{{ asset('storage/' . $article->featured_image) }}">
        <meta property="og:image:alt" content="{{ e($articleTitle) }}">
    @endif
    <meta property="article:published_time" content="{{ $article->published_at?->toISOString() }}">
    @if($article->updated_at)
        <meta property="article:modified_time" content="{{ $article->updated_at->toISOString() }}">
    @endif
    @if($article->author)
        <meta property="article:author" content="{{ e($article->author->name) }}">
    @endif
    @foreach($article->tags as $tag)
        <meta property="article:tag" content="{{ e($tag->name) }}">
    @endforeach
    
    {{-- Twitter Card --}}
    <meta name="twitter:card" content="summary_large_image">
    @if($article->featured_image)
        <meta name="twitter:image" content="{{ asset('storage/' . $article->featured_image) }}">
    @endif
@endpush

@push('schema')
    @php
        $schemaService = app(\App\Services\SchemaService::class);
        
        // Article Schema - use cached variables
        $articleData = [
            'title' => $articleTitle,
            'excerpt' => $articleExcerpt,
            'slug' => $article->slug,
            'featured_image' => $article->featured_image,
            'published_at' => $article->published_at?->toIso8601String(),
            'created_at' => $article->created_at?->toIso8601String(),
            'updated_at' => $article->updated_at?->toIso8601String(),
            'word_count' => $wordCount,
        ];
        
        $articleSchema = $schemaService->getArticleSchema($articleData);
        
        // Breadcrumb Schema
        $breadcrumbItems = [
            ['name' => __('Home'), 'url' => route('home')],
            ['name' => __('Articles'), 'url' => route('articles.index')],
            ['name' => $articleTitle, 'url' => null],
        ];
        $breadcrumbSchema = $schemaService->getBreadcrumbSchema($breadcrumbItems);
    @endphp
    <x-schema-markup :schemas="[$articleSchema, $breadcrumbSchema]" />
@endpush

@push('head')
<style>
    /* Compact Article Typography */
    .article-content {
        font-size: 0.9375rem;
        line-height: 1.65;
        color: #4b5563;
    }
    
    /* Paragraphs */
    .article-content p {
        margin-bottom: 0.875rem;
    }
    
    .article-content p:last-child {
        margin-bottom: 0;
    }
    
    /* Headings - Modern Style with Left Accent */
    .article-content h2 {
        font-size: 1.25rem;
        font-weight: 700;
        margin-top: 1.5rem;
        margin-bottom: 0.625rem;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        line-height: 1.3;
    }
    
    .article-content h2::before {
        content: '';
        width: 3px;
        height: 1.25rem;
        background: linear-gradient(180deg, #3b82f6, #2563eb);
        border-radius: 2px;
        flex-shrink: 0;
    }
    
    .article-content h2:first-child {
        margin-top: 0;
    }
    
    .article-content h3 {
        font-size: 1.0625rem;
        font-weight: 600;
        margin-top: 1.25rem;
        margin-bottom: 0.5rem;
        color: #1f2937;
        line-height: 1.3;
    }
    
    .article-content h3:first-child {
        margin-top: 0;
    }
    
    .article-content h4 {
        font-size: 1rem;
        font-weight: 600;
        margin-top: 1rem;
        margin-bottom: 0.375rem;
        color: #374151;
    }
    
    /* Strong/Bold text with accent */
    .article-content strong,
    .article-content b {
        font-weight: 700;
        color: #1f2937;
    }
    
    /* Italic/Emphasis */
    .article-content em,
    .article-content i {
        font-style: italic;
        color: #4b5563;
    }
    
    /* Links */
    .article-content a {
        color: #2563eb;
        text-decoration: underline;
        text-decoration-color: rgba(37, 99, 235, 0.3);
        text-underline-offset: 2px;
        transition: all 0.2s ease;
    }
    
    .article-content a:hover {
        color: #1d4ed8;
        text-decoration-color: rgba(29, 78, 216, 0.5);
    }
    
    /* Unordered Lists - Compact Bullet Style */
    .article-content ul {
        margin: 0.75rem 0;
        padding-left: 0;
        list-style: none;
    }
    
    .article-content ul li {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: 0.375rem;
        color: #4b5563;
    }
    
    .article-content ul li::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0.5rem;
        width: 0.375rem;
        height: 0.375rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        border-radius: 50%;
    }
    
    /* Nested unordered lists */
    .article-content ul ul {
        margin-top: 0.375rem;
        margin-bottom: 0;
    }
    
    .article-content ul ul li::before {
        width: 0.3rem;
        height: 0.3rem;
        background: #93c5fd;
    }
    
    /* Ordered Lists - Compact Circular Badge Style */
    .article-content ol {
        margin: 0.75rem 0;
        padding-left: 0;
        list-style: none;
        counter-reset: item;
    }
    
    .article-content ol li {
        position: relative;
        padding-left: 2rem;
        margin-bottom: 0.5rem;
        color: #4b5563;
        counter-increment: item;
    }
    
    .article-content ol li::before {
        content: counter(item);
        position: absolute;
        left: 0;
        top: 0.125rem;
        width: 1.375rem;
        height: 1.375rem;
        background: linear-gradient(135deg, #3b82f6, #2563eb);
        color: white;
        font-weight: 700;
        font-size: 0.6875rem;
        border-radius: 50%;
        text-align: center;
        line-height: 1.375rem;
        box-shadow: 0 1px 3px rgba(59, 130, 246, 0.25);
    }
    
    /* Nested ordered lists */
    .article-content ol ol {
        margin-top: 0.375rem;
        margin-bottom: 0;
        counter-reset: subitem;
    }
    
    .article-content ol ol li {
        padding-left: 1.75rem;
        counter-increment: subitem;
    }
    
    .article-content ol ol li::before {
        content: counter(subitem);
        width: 1.125rem;
        height: 1.125rem;
        font-size: 0.5625rem;
        line-height: 1.125rem;
        background: #93c5fd;
    }
    
    /* Blockquotes - Clean Compact Style */
    .article-content blockquote {
        margin: 1rem 0;
        padding: 0.75rem 1rem;
        background: linear-gradient(135deg, #eff6ff 0%, #f0f9ff 100%);
        border-left: 3px solid #3b82f6;
        border-radius: 0 0.5rem 0.5rem 0;
        font-style: italic;
        color: #1e40af;
        font-size: 0.9rem;
    }
    
    .article-content blockquote p {
        margin-bottom: 0;
    }
    
    /* Code */
    .article-content code {
        background: #f1f5f9;
        color: #be185d;
        padding: 0.125rem 0.3rem;
        border-radius: 0.1875rem;
        font-size: 0.85em;
        font-family: 'JetBrains Mono', 'Fira Code', monospace;
    }
    
    .article-content pre {
        background: linear-gradient(135deg, #1e293b, #0f172a);
        color: #e2e8f0;
        padding: 1rem;
        border-radius: 0.5rem;
        overflow-x: auto;
        margin: 1rem 0;
        border: 1px solid #334155;
        font-size: 0.85rem;
    }
    
    .article-content pre code {
        background: transparent;
        padding: 0;
        color: inherit;
    }
    
    /* Tables - Clean Compact Style */
    .article-content table {
        width: 100%;
        margin: 1rem 0;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 0.5rem;
        overflow: hidden;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.06);
        font-size: 0.875rem;
    }
    
    .article-content thead {
        background: linear-gradient(135deg, #3b82f6, #2563eb);
    }
    
    .article-content th {
        padding: 0.625rem 0.875rem;
        text-align: left;
        font-weight: 600;
        color: white;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 0.05em;
    }
    
    .article-content td {
        padding: 0.625rem 0.875rem;
        border-bottom: 1px solid #e5e7eb;
        color: #4b5563;
    }
    
    .article-content tbody tr:hover {
        background-color: #f9fafb;
    }
    
    .article-content tbody tr:last-child td {
        border-bottom: none;
    }
    
    /* Horizontal Rule */
    .article-content hr {
        margin: 1.25rem 0;
        border: none;
        height: 1px;
        background: linear-gradient(90deg, transparent, #d1d5db, transparent);
    }
    
    /* Images */
    .article-content img {
        margin: 1rem auto;
        border-radius: 0.5rem;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        max-width: 100%;
        height: auto;
        display: block;
    }
    
    /* Figure with caption */
    .article-content figure {
        margin: 1rem 0;
    }
    
    .article-content figcaption {
        margin-top: 0.375rem;
        text-align: center;
        font-size: 0.8125rem;
        color: #6b7280;
        font-style: italic;
    }
    
    /* Special highlight box for important content */
    .article-content .highlight,
    .article-content .note {
        margin: 0.875rem 0;
        padding: 0.75rem 1rem;
        background: #fef3c7;
        border: 1px solid #fcd34d;
        border-radius: 0.375rem;
        color: #92400e;
        font-size: 0.875rem;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .article-content {
            font-size: 0.9rem;
        }
        
        .article-content h2 {
            font-size: 1.125rem;
            margin-top: 1.25rem;
        }
        
        .article-content h3 {
            font-size: 1rem;
            margin-top: 1rem;
        }
        
        .article-content ol li {
            padding-left: 1.75rem;
        }
        
        .article-content ol li::before {
            width: 1.25rem;
            height: 1.25rem;
            font-size: 0.625rem;
            line-height: 1.25rem;
        }
    }
</style>
@endpush

@section('content')
    {{-- Compact Modern Hero Section --}}
    <section class="bg-white border-b border-gray-100">
        <div class="container py-6">
            {{-- Breadcrumb --}}
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2 flex-wrap">
                    <li><a href="{{ route('home') }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ __('Home') }}</a></li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li><a href="{{ route('articles.index') }}" class="text-gray-500 hover:text-primary-600 transition-colors">{{ __('Articles') }}</a></li>
                    <li><svg class="w-4 h-4 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></li>
                    <li class="text-gray-900 font-medium line-clamp-1">{{ Str::limit($articleTitle, 50) }}</li>
                </ol>
            </nav>

            <div class="flex flex-col lg:flex-row lg:items-start gap-6">
                {{-- Featured Image (Compact) --}}
                @if($article->featured_image)
                    <div class="lg:w-80 flex-shrink-0">
                        <img src="{{ asset('storage/' . $article->featured_image) }}" 
                             alt="{{ e($articleTitle) }}" 
                             class="w-full h-48 lg:h-44 object-cover rounded-xl shadow-sm"
                             fetchpriority="high"
                             decoding="async">
                    </div>
                @endif

                {{-- Article Info --}}
                <div class="flex-1 min-w-0">
                    {{-- Tags --}}
                    @if($article->tags->count() > 0)
                        <div class="flex flex-wrap gap-2 mb-3" role="list" aria-label="{{ __('Article tags') }}">
                            @foreach($article->tags->take(3) as $tag)
                                <a href="{{ route('articles.tag', $tag->slug) }}" 
                                   class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-primary-50 text-primary-700 hover:bg-primary-100 transition-colors"
                                   role="listitem">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                            @if($article->tags->count() > 3)
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-gray-100 text-gray-600">
                                    +{{ $article->tags->count() - 3 }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Title --}}
                    <h1 class="text-xl md:text-2xl lg:text-3xl font-bold font-heading text-gray-900 mb-3 leading-tight">
                        {{ $articleTitle }}
                    </h1>

                    {{-- Excerpt --}}
                    @if($articleExcerpt)
                        <p class="text-gray-600 text-sm md:text-base mb-4 line-clamp-2">{{ $articleExcerpt }}</p>
                    @endif

                    {{-- Meta Info --}}
                    <div class="flex flex-wrap items-center gap-4 text-sm text-gray-500">
                        @if($article->author)
                            <div class="flex items-center gap-2">
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-semibold text-xs">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                                <span class="font-medium text-gray-700">{{ $article->author->name }}</span>
                            </div>
                        @endif

                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <time datetime="{{ $article->published_at?->toISOString() }}">
                                {{ $article->published_at?->translatedFormat('d M Y') }}
                            </time>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $readingTime }} {{ __('min read') }}</span>
                        </div>

                        <div class="flex items-center gap-1.5">
                            <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <span>{{ number_format($article->view_count) }} {{ __('views') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Article Content --}}
    <article class="py-8 md:py-12 bg-gray-50">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">
                {{-- Main Content --}}
                <div class="lg:col-span-8">
                    {{-- Article Card --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                        {{-- Article Body --}}
                        <div class="p-6 md:p-8">
                            <div class="article-content" role="main">
                                {!! $articleContent !!}
                            </div>
                        </div>

                        {{-- Tags Footer --}}
                        @if($article->tags->count() > 0)
                            <div class="px-6 md:px-8 py-5 bg-gray-50 border-t border-gray-100">
                                <div class="flex flex-wrap items-center gap-2">
                                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                    </svg>
                                    @foreach($article->tags as $tag)
                                        <a href="{{ route('articles.tag', $tag->slug) }}" class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-600 hover:bg-primary-100 hover:text-primary-700 transition-colors">
                                            #{{ $tag->name }}
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

                    {{-- Share Section (Compact) --}}
                    <div class="mt-6 p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-700">{{ __('Share this article') }}</span>
                            <div class="flex items-center gap-2">
                                <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode($canonicalUrl) }}" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                                   aria-label="{{ __('Share on Facebook') }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                                </a>
                                <a href="https://twitter.com/intent/tweet?url={{ urlencode($canonicalUrl) }}&text={{ urlencode($articleTitle) }}" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-black text-white hover:bg-gray-800 transition-colors"
                                   aria-label="{{ __('Share on Twitter') }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                                </a>
                                <a href="https://wa.me/?text={{ urlencode($articleTitle . ' - ' . $canonicalUrl) }}" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-green-500 text-white hover:bg-green-600 transition-colors"
                                   aria-label="{{ __('Share on WhatsApp') }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                                </a>
                                <a href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode($canonicalUrl) }}&title={{ urlencode($articleTitle) }}" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-700 text-white hover:bg-blue-800 transition-colors"
                                   aria-label="{{ __('Share on LinkedIn') }}">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                                </a>
                                <button onclick="navigator.clipboard.writeText('{{ $canonicalUrl }}'); this.classList.add('bg-green-500'); setTimeout(() => this.classList.remove('bg-green-500'), 1500)" 
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors"
                                        aria-label="{{ __('Copy link') }}">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    {{-- Author Box (Compact) --}}
                    @if($article->author)
                        <div class="mt-6 p-5 bg-white rounded-xl border border-gray-100 shadow-sm">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                                    {{ substr($article->author->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <div class="text-xs text-gray-500 uppercase tracking-wider">{{ __('Written by') }}</div>
                                    <h4 class="font-semibold text-gray-900">{{ $article->author->name }}</h4>
                                    <span class="text-sm text-gray-500">{{ \App\Models\Article::where('author_id', $article->author->id)->where('status', 'published')->count() }} {{ __('Articles') }}</span>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-4">
                    <div class="sticky top-24 space-y-6">
                        {{-- Reading Progress Card --}}
                        <div class="bg-gradient-to-br from-white to-gray-50 rounded-2xl p-6 border border-gray-200 shadow-lg shadow-gray-100/50">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-primary-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                {{ __('Article Info') }}
                            </h3>
                            <div class="space-y-4">
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-gray-500 text-sm">{{ __('Reading time') }}</span>
                                    <span class="font-semibold text-gray-900">{{ $readingTime }} {{ __('min') }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3 border-b border-gray-100">
                                    <span class="text-gray-500 text-sm">{{ __('Views') }}</span>
                                    <span class="font-semibold text-gray-900">{{ number_format($article->view_count) }}</span>
                                </div>
                                <div class="flex items-center justify-between py-3">
                                    <span class="text-gray-500 text-sm">{{ __('Published') }}</span>
                                    <span class="font-semibold text-gray-900">{{ $article->published_at?->diffForHumans() }}</span>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Quick Navigation --}}
                        <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg shadow-gray-100/50">
                            <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                <div class="w-8 h-8 rounded-lg bg-indigo-100 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                </div>
                                {{ __('Quick Navigation') }}
                            </h3>
                            <div class="space-y-2">
                                <button type="button" onclick="window.scrollTo({top: 0, behavior: 'smooth'});" class="w-full flex items-center gap-3 text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all py-2.5 px-3 rounded-xl group text-left">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"/>
                                    </svg>
                                    <span>{{ __('Back to Top') }}</span>
                                </button>
                                <a href="{{ route('articles.index') }}" class="flex items-center gap-3 text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all py-2.5 px-3 rounded-xl group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                    </svg>
                                    <span>{{ __('All Articles') }}</span>
                                </a>
                                <a href="{{ route('contact') }}" class="flex items-center gap-3 text-gray-600 hover:text-primary-600 hover:bg-primary-50 transition-all py-2.5 px-3 rounded-xl group">
                                    <svg class="w-5 h-5 text-gray-400 group-hover:text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                    <span>{{ __('Contact Us') }}</span>
                                </a>
                            </div>
                        </div>

                        {{-- Related Articles in Sidebar --}}
                        @if($relatedArticles->count() > 0)
                            <div class="bg-white rounded-2xl p-6 border border-gray-200 shadow-lg shadow-gray-100/50">
                                <h3 class="font-bold text-gray-900 mb-4 flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-amber-100 flex items-center justify-center">
                                        <svg class="w-4 h-4 text-amber-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                        </svg>
                                    </div>
                                    {{ __('Related Articles') }}
                                </h3>
                                <div class="space-y-4">
                                    @foreach($relatedArticles as $related)
                                        <a href="{{ route('articles.show', $related->slug) }}" class="block group">
                                            <div class="flex gap-4 p-3 -m-3 rounded-xl hover:bg-gray-50 transition-all">
                                                @if($related->featured_image)
                                                    <div class="w-20 h-20 rounded-xl overflow-hidden flex-shrink-0 shadow-md">
                                                        <img src="{{ asset('storage/' . $related->featured_image) }}" 
                                                             alt="{{ e($related->getTranslation('title', $locale)) }}" 
                                                             class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                                             loading="lazy"
                                                             decoding="async">
                                                    </div>
                                                @else
                                                    <div class="w-20 h-20 rounded-xl bg-gradient-to-br from-gray-100 to-gray-50 flex items-center justify-center flex-shrink-0" aria-hidden="true">
                                                        <svg class="w-8 h-8 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                                <div class="flex-1 min-w-0">
                                                    <h4 class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2 text-sm leading-snug mb-1">
                                                        {{ $related->getTranslation('title', $locale) }}
                                                    </h4>
                                                    <time datetime="{{ $related->published_at?->toISOString() }}" class="text-xs text-gray-500 flex items-center gap-1">
                                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        {{ $related->published_at?->format('d M Y') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </a>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        {{-- CTA Box --}}
                        <div class="bg-gradient-to-br from-primary-600 via-primary-700 to-indigo-700 rounded-2xl p-6 text-white relative overflow-hidden">
                            {{-- Decorative elements --}}
                            <div class="absolute top-0 right-0 w-24 h-24 bg-white/10 rounded-full -translate-y-1/2 translate-x-1/2"></div>
                            <div class="absolute bottom-0 left-0 w-16 h-16 bg-white/10 rounded-full translate-y-1/2 -translate-x-1/2"></div>
                            
                            <div class="relative">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center mb-4 backdrop-blur-sm">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                </div>
                                <h3 class="font-bold text-xl mb-2">{{ __('Need Steel Products?') }}</h3>
                                <p class="text-primary-100 text-sm mb-5 leading-relaxed">{{ __('Get the best price and quality for your construction needs') }}</p>
                                <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 bg-white text-primary-600 px-5 py-3 rounded-xl font-bold text-sm hover:bg-primary-50 transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                                    {{ __('Request Quote') }}
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </article>

    {{-- Related Articles Full Section --}}
    @if($relatedArticles->count() > 0)
        <section class="py-10 md:py-12 bg-white border-t border-gray-100">
            <div class="container">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl md:text-2xl font-bold font-heading text-gray-900">{{ __('Related Articles') }}</h2>
                    <a href="{{ route('articles.index') }}" class="text-primary-600 hover:text-primary-700 font-medium inline-flex items-center gap-1 text-sm">
                        {{ __('View All') }}
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($relatedArticles as $related)
                        @include('components.article-card', ['article' => $related])
                    @endforeach
                </div>
            </div>
        </section>
    @endif
@endsection

