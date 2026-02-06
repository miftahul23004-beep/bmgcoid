{{-- Footer --}}
{{-- Data provided by LayoutComposer with caching --}}
@php
    $showSocialFooter = !empty($socialLinks['show_social_footer']) && $socialLinks['show_social_footer'] == '1';
    $logoWhitePath = !empty($companyInfo['logo_white']) ? Storage::url($companyInfo['logo_white']) : asset('images/logo-white.png');
    $companyYear = $companyInfo['company_year'] ?? '2011';
@endphp
<footer class="bg-gray-900 text-gray-300">
    {{-- Main Footer --}}
    <div class="container mx-auto px-4 py-12">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
            {{-- Company Info --}}
            <div class="lg:col-span-1">
                {{-- Logo with Badges --}}
                <div class="flex items-start gap-3 mb-4">
                    {{-- Logo Container --}}
                    <div class="relative flex-shrink-0">
                        <div class="bg-gradient-to-br from-gray-800 to-gray-700 p-2.5 rounded-xl border border-gray-600/50 shadow-lg">
                            <img src="{{ $logoWhitePath }}" alt="{{ $companyInfo['company_name'] ?? config('app.name') }}" class="h-10 w-auto" width="40" height="40" loading="lazy">
                        </div>
                        {{-- Trust Badge on Logo --}}
                        <div class="absolute -bottom-1.5 -right-1.5 bg-gradient-to-r from-amber-500 to-amber-600 text-white rounded-full p-1 shadow-lg">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    {{-- Company Name & Badges --}}
                    <div class="flex-1 min-w-0">
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <h3 class="text-white font-bold text-lg leading-tight">{{ $companyInfo['company_name'] ?? config('app.name') }}</h3>
                        </div>
                        {{-- Badge Row --}}
                        <div class="flex flex-wrap items-center gap-1.5">
                            {{-- Verified Badge --}}
                            <span class="inline-flex items-center gap-1 bg-gradient-to-r from-primary-500 to-primary-600 text-white text-[10px] font-semibold px-2 py-0.5 rounded-full">
                                <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                Terpercaya
                            </span>
                            {{-- Year Badge --}}
                            <span class="inline-flex items-center gap-1 bg-gray-700/80 text-gray-300 text-[10px] font-medium px-2 py-0.5 rounded-full border border-gray-600/50">
                                <svg class="w-2.5 h-2.5 text-amber-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                Sejak {{ $companyYear }}
                            </span>
                        </div>
                    </div>
                </div>
                <p class="text-sm text-gray-400 mb-4">
                    {{ $companyInfo['company_tagline'] ?? 'Distributor dan supplier besi berkualitas untuk industri, manufaktur, konstruksi bangunan dan pekerjaan sipil.' }}
                </p>
                {{-- Social Media --}}
                @if($showSocialFooter)
                <div class="flex space-x-4">
                    @foreach(['facebook', 'instagram', 'youtube', 'tiktok', 'twitter', 'linkedin', 'whatsapp'] as $platform)
                        @if(!empty($socialLinks[$platform]) && (!empty($socialLinks[$platform . '_active']) && $socialLinks[$platform . '_active'] == '1'))
                        <a 
                            href="{{ $platform === 'whatsapp' ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialLinks[$platform]) : $socialLinks[$platform] }}" 
                            target="_blank" 
                            rel="noopener noreferrer" 
                            class="w-10 h-10 rounded-full bg-gray-800 flex items-center justify-center hover:bg-primary-600 transition-colors"
                            aria-label="{{ ucfirst($platform) }}"
                        >
                            <x-dynamic-component :component="'icons.' . $platform" class="w-5 h-5" />
                        </a>
                        @endif
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Quick Links --}}
            <div>
                <h4 class="text-white font-semibold text-lg mb-4">{{ __('Quick Links') }}</h4>
                <ul class="space-y-2">
                    <li><a href="{{ route('home') }}" class="hover:text-primary-400 transition-colors">{{ __('Home') }}</a></li>
                    <li><a href="{{ route('about.company') }}" class="hover:text-primary-400 transition-colors">{{ __('About Us') }}</a></li>
                    <li><a href="{{ route('products.index') }}" class="hover:text-primary-400 transition-colors">{{ __('Products') }}</a></li>
                    <li><a href="{{ route('articles.index') }}" class="hover:text-primary-400 transition-colors">{{ __('Articles') }}</a></li>
                    <li><a href="{{ route('contact') }}" class="hover:text-primary-400 transition-colors">{{ __('Contact') }}</a></li>
                    <li><a href="{{ route('quote') }}" class="hover:text-primary-400 transition-colors">{{ __('Request Quote') }}</a></li>
                </ul>
            </div>

            {{-- Product Categories --}}
            <div>
                <h4 class="text-white font-semibold text-lg mb-4">{{ __('Product Categories') }}</h4>
                <ul class="space-y-2">
                    @foreach(\App\Models\Category::active()->roots()->ordered()->take(6)->get() as $category)
                    <li>
                        <a href="{{ route('products.category', $category->slug) }}" class="hover:text-primary-400 transition-colors">
                            {{ $category->getTranslation('name', app()->getLocale()) }}
                        </a>
                    </li>
                    @endforeach
                </ul>
            </div>

            {{-- Contact Info --}}
            <div>
                <h4 class="text-white font-semibold text-lg mb-4">{{ __('Contact Us') }}</h4>
                <ul class="space-y-4">
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 mt-0.5 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span class="text-sm">{{ $companyInfo['address'] ?? 'Jl. Industri Raya No. 123, Jakarta 12345' }}</span>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <a href="tel:{{ $companyInfo['phone'] ?? '+62-21-12345678' }}" class="text-sm hover:text-primary-400 transition-colors">
                            {{ $companyInfo['phone'] ?? '+62-21-12345678' }}
                        </a>
                    </li>
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <x-protected-email 
                            :email="$companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'" 
                            class="text-sm hover:text-primary-400 transition-colors"
                        />
                    </li>
                    @if(!empty($socialLinks['whatsapp']))
                    <li class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp']) }}" target="_blank" rel="noopener noreferrer" class="text-sm hover:text-primary-400 transition-colors">
                            WA: {{ $socialLinks['whatsapp'] }}
                        </a>
                    </li>
                    @endif
                    <li class="flex items-start">
                        <svg class="w-5 h-5 mr-3 text-primary-400 flex-shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-sm">
                            @if(!empty($companyInfo['business_hours_weekday']) || !empty($companyInfo['business_hours_weekend']) || !empty($companyInfo['business_hours_sunday']))
                                @if(!empty($companyInfo['business_hours_weekday']))
                                    {{ $companyInfo['business_hours_weekday'] }}
                                @endif
                                @if(!empty($companyInfo['business_hours_weekend']))
                                    <br>{{ $companyInfo['business_hours_weekend'] }}
                                @endif
                                @if(!empty($companyInfo['business_hours_sunday']))
                                    <br>{{ $companyInfo['business_hours_sunday'] }}
                                @endif
                            @else
                                {{ app()->getLocale() === 'id' ? 'Sen - Jum: 08:00 - 17:00' : 'Mon - Fri: 08:00 - 17:00' }}
                            @endif
                        </span>
                    </li>
                </ul>
            </div>
        </div>
        
        {{-- Service Areas - Local & National SEO --}}
        <div class="border-t border-gray-800 mt-8 pt-8">
            <div class="text-center">
                <h4 class="text-white font-semibold text-sm mb-3">
                    @if(app()->getLocale() === 'en') 
                        Steel Supplier Serving All of Indonesia
                    @else 
                        Supplier Besi Baja Melayani Seluruh Indonesia
                    @endif
                </h4>
                <p class="text-xs text-gray-500 max-w-4xl mx-auto leading-relaxed mb-2">
                    @if(app()->getLocale() === 'en') 
                        <span class="text-primary-400">Wholesale & Retail</span> • 
                        <span class="text-gray-400">Large Projects</span> • 
                        <span class="text-gray-400">Individual Orders</span> • 
                        <span class="text-gray-400">Industrial</span> • 
                        <span class="text-gray-400">Construction</span>
                    @else 
                        <span class="text-primary-400">Partai Besar & Eceran</span> • 
                        <span class="text-gray-400">Proyek Besar</span> • 
                        <span class="text-gray-400">Pembelian Satuan</span> • 
                        <span class="text-gray-400">Industri</span> • 
                        <span class="text-gray-400">Konstruksi</span>
                    @endif
                </p>
                <p class="text-xs text-gray-500 max-w-4xl mx-auto leading-relaxed">
                    @if(app()->getLocale() === 'en') 
                        <span class="text-gray-400 font-medium">Primary:</span> Surabaya • Sidoarjo • Gresik • Mojokerto • Jombang • East Java |
                        <span class="text-gray-400 font-medium">National:</span> Java • Sumatra • Kalimantan • Sulawesi • Bali • Papua • All Indonesia
                    @else 
                        <span class="text-gray-400 font-medium">Utama:</span> Surabaya • Sidoarjo • Gresik • Mojokerto • Jombang • Jawa Timur |
                        <span class="text-gray-400 font-medium">Nasional:</span> Jawa • Sumatera • Kalimantan • Sulawesi • Bali • Papua • Seluruh Indonesia
                    @endif
                </p>
            </div>
        </div>
    </div>

    {{-- Bottom Footer --}}
    <div class="border-t border-gray-800">
        <div class="container mx-auto px-4 py-6">
            <div class="flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
                <p class="text-sm text-gray-400">
                    © {{ date('Y') }} {{ $companyInfo['company_name'] ?? config('app.name') }}. {{ __('All rights reserved.') }}
                </p>
                <div class="flex space-x-6 text-sm">
                    <a href="{{ route('privacy') }}" class="hover:text-primary-400 transition-colors">{{ __('Privacy Policy') }}</a>
                    <a href="{{ route('terms') }}" class="hover:text-primary-400 transition-colors">{{ __('Terms of Service') }}</a>
                    <a href="{{ route('sitemap') }}" class="hover:text-primary-400 transition-colors">{{ __('Sitemap') }}</a>
                </div>
            </div>
        </div>
    </div>
</footer>

