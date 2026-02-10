@props(['article', 'headingTag' => 'h3'])

<article class="card group hover:shadow-lg transition-all duration-300" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
    <a href="{{ route('articles.show', $article->slug) }}" class="block">
        <div class="aspect-video bg-gray-100 rounded-lg mb-4 overflow-hidden">
            @if($article->featured_image)
                <img src="{{ asset('storage/' . $article->featured_image) }}" 
                     alt="{{ e($article->getTranslation('title', app()->getLocale())) }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500" 
                     width="400"
                     height="225"
                     loading="lazy"
                     decoding="async">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                    </svg>
                </div>
            @endif
        </div>
        
        <div class="flex flex-wrap gap-2 mb-3">
            @foreach($article->tags->take(2) as $tag)
                <span class="badge badge-primary text-xs">{{ $tag->name }}</span>
            @endforeach
        </div>
        
        <{{ $headingTag }} class="font-semibold text-gray-900 group-hover:text-primary-600 transition-colors line-clamp-2 mb-2">
            {{ e($article->getTranslation('title', app()->getLocale())) }}
        </{{ $headingTag }}>
        
        @if($article->excerpt)
            <p class="text-gray-600 text-sm line-clamp-2 mb-4">{{ e($article->getTranslation('excerpt', app()->getLocale())) }}</p>
        @endif
        
        <div class="flex items-center justify-between text-sm text-gray-500">
            <div class="flex items-center gap-2">
                @if($article->author)
                    <span>{{ e($article->author->name) }}</span>
                @endif
            </div>
            <time datetime="{{ $article->published_at?->toISOString() }}">
                {{ $article->published_at?->format('d M Y') }}
            </time>
        </div>
    </a>
</article>

