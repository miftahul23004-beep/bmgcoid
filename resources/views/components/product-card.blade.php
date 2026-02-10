@props(['product', 'dark' => false, 'listView' => false, 'headingTag' => 'h3'])

@php
    use Illuminate\Support\Facades\Storage;
    
    $productName = $product->getTranslation('name', app()->getLocale());
    $productSlug = $product->slug;
    $categoryName = $product->category ? $product->category->getTranslation('name', app()->getLocale()) : null;
    $shortDesc = $product->short_description ? $product->getTranslation('short_description', app()->getLocale()) : null;
    
    // Get product image - try featured_image first, then productMedia
    $productImage = null;
    if ($product->featured_image) {
        $productImage = Storage::disk('public')->url($product->featured_image);
    } elseif ($product->productMedia && $product->productMedia->first()) {
        $media = $product->productMedia->first();
        if ($media->file_path) {
            $productImage = Storage::disk('public')->url($media->file_path);
        }
    }
@endphp

<article class="product-card group bg-white rounded-xl border border-gray-100 shadow-sm hover:shadow-lg hover:-translate-y-1 transition-all duration-300 overflow-hidden {{ $dark ? 'bg-gray-800 border-gray-700' : '' }} {{ $listView ? 'flex flex-row' : '' }}" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
    <a href="{{ route('products.show', $productSlug) }}" class="block {{ $listView ? 'flex flex-row w-full' : '' }}" title="{{ $productName }}">
        {{-- Image Container --}}
        <div class="product-image bg-gray-100 overflow-hidden relative {{ $listView ? 'w-48 md:w-56 flex-shrink-0' : 'aspect-[4/3]' }}">
            @if($productImage)
                <img src="{{ $productImage }}" 
                     alt="{{ $productName }}" 
                     class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500 {{ $listView ? 'aspect-square md:aspect-[4/3]' : '' }}" 
                     width="{{ $listView ? '224' : '400' }}"
                     height="{{ $listView ? '224' : '300' }}"
                     loading="lazy"
                     decoding="async">
            @else
                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-50 to-gray-100 {{ $listView ? 'aspect-square md:aspect-[4/3]' : '' }}">
                    <svg class="w-16 h-16 text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                </div>
            @endif
            
            {{-- Badges --}}
            <div class="absolute top-3 left-3 flex flex-wrap gap-1.5">
                @if($product->is_featured)
                    <span class="bg-primary-600 text-white text-xs font-semibold px-2.5 py-1 rounded-full shadow-sm">{{ __('Featured') }}</span>
                @endif
                @if($product->is_new)
                    <span class="bg-green-700 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg border border-green-600">{{ __('New') }}</span>
                @endif
                @if($product->is_bestseller)
                    <span class="bg-amber-700 text-white text-xs font-bold px-2.5 py-1 rounded-full shadow-lg border border-amber-800">{{ __('Best Seller') }}</span>
                @endif
            </div>

            {{-- Quick View Overlay (hide in list view) --}}
            @unless($listView)
            <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center">
                <span class="bg-white text-gray-900 px-4 py-2 rounded-lg font-medium text-sm transform translate-y-4 group-hover:translate-y-0 transition-transform duration-300">
                    {{ __('View Details') }}
                </span>
            </div>
            @endunless
        </div>
        
        {{-- Content --}}
        <div class="product-content p-4 {{ $dark ? 'text-white' : '' }} {{ $listView ? 'flex-1 flex flex-col justify-center' : '' }}">
            @if($categoryName)
                <p class="text-xs {{ $dark ? 'text-primary-300' : 'text-primary-600' }} font-semibold mb-1.5 uppercase tracking-wide">{{ $categoryName }}</p>
            @endif
            <{{ $headingTag }} class="font-bold {{ $listView ? 'text-lg' : 'text-base' }} {{ $dark ? 'text-white group-hover:text-primary-300' : 'text-gray-900 group-hover:text-primary-600' }} transition-colors line-clamp-2 mb-2 leading-snug">
                {{ $productName }}
            </{{ $headingTag }}>
            
            {{-- Show description in list view --}}
            @if($listView && $shortDesc)
                <p class="text-sm text-gray-600 line-clamp-2 mb-3">{{ $shortDesc }}</p>
            @endif
            
            @if($product->sku)
                <p class="text-xs {{ $dark ? 'text-gray-300' : 'text-gray-700' }}"><span>{{ $product->sku }}</span></p>
            @endif
            
            {{-- Hidden brand --}}
            <meta name="brand" content="{{ config('app.name') }}">
            
            {{-- Marketplace links in list view --}}
            @if($listView && $product->marketplaceLinks && $product->marketplaceLinks->count() > 0)
                <div class="mt-3 pt-3 border-t {{ $dark ? 'border-gray-700' : 'border-gray-100' }}">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="text-xs {{ $dark ? 'text-gray-300' : 'text-gray-700' }}">{{ __('Buy on') }}:</span>
                        @foreach($product->marketplaceLinks->take(3) as $link)
                            <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 font-medium">
                                {{ ucfirst($link->platform) }}
                            </span>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </a>
    
    {{-- Marketplace links (grid view only) --}}
    @if(!$listView && $product->marketplaceLinks && $product->marketplaceLinks->count() > 0)
        <div class="px-4 pb-4 pt-0">
            <div class="pt-3 border-t {{ $dark ? 'border-gray-700' : 'border-gray-100' }}">
                <p class="text-xs {{ $dark ? 'text-gray-400' : 'text-gray-500' }} mb-2">{{ __('Buy on') }}:</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($product->marketplaceLinks->take(3) as $link)
                        <a href="{{ route('marketplace.redirect', ['platform' => e($link->platform), 'productId' => $product->id]) }}" 
                           target="_blank" 
                           rel="noopener nofollow" 
                           class="text-xs px-2.5 py-1 rounded-full bg-gray-100 hover:bg-primary-100 hover:text-primary-700 text-gray-600 transition-colors font-medium" 
                           title="{{ __('Buy on') }} {{ ucfirst($link->platform) }}">
                            {{ ucfirst($link->platform) }}
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</article>

