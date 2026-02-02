{{-- Top Bar - Contact Info & Language Switcher --}}
{{-- Data provided by LayoutComposer with caching --}}
@php
    $showSocialTopbar = !empty($socialLinks['show_social_topbar']) && $socialLinks['show_social_topbar'] == '1';
@endphp
<div class="bg-primary-900 text-white text-sm hidden md:block">
    <div class="container mx-auto px-4">
        <div class="flex justify-between items-center py-2">
            {{-- Contact Info --}}
            <div class="flex items-center space-x-6">
                <a href="tel:{{ $companyInfo['phone'] ?? '+62-21-12345678' }}" class="flex items-center hover:text-primary-200 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                    </svg>
                    <span>{{ $companyInfo['phone'] ?? '+62-21-12345678' }}</span>
                </a>
                <x-protected-email 
                    :email="$companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'" 
                    class="flex items-center hover:text-primary-200 transition-colors"
                    :showIcon="true"
                    iconClass="w-4 h-4 mr-2"
                />
            </div>

            {{-- Social Media Links & Language Switcher --}}
            <div class="flex items-center space-x-4">
                {{-- Social Icons --}}
                @if($showSocialTopbar)
                <div class="flex items-center space-x-3">
                    @foreach(['facebook', 'instagram', 'youtube', 'tiktok', 'twitter', 'linkedin', 'whatsapp'] as $platform)
                        @if(!empty($socialLinks[$platform]) && (!empty($socialLinks[$platform . '_active']) && $socialLinks[$platform . '_active'] == '1'))
                        <a href="{{ $platform === 'whatsapp' ? 'https://wa.me/' . preg_replace('/[^0-9]/', '', $socialLinks[$platform]) : $socialLinks[$platform] }}" target="_blank" rel="noopener noreferrer" class="hover:text-primary-200 transition-colors" aria-label="{{ ucfirst($platform) }}">
                            <x-dynamic-component :component="'icons.' . $platform" class="w-4 h-4" />
                        </a>
                        @endif
                    @endforeach
                </div>
                
                {{-- Divider --}}
                <div class="w-px h-4 bg-primary-700"></div>
                @endif
                
                {{-- Language Switcher --}}
                @include('layouts.partials.language-switcher-topbar')
            </div>
        </div>
    </div>
</div>

