@extends('layouts.app')

@section('title', __('Contact Us') . ' - ' . config('app.name'))

@section('meta')
    <meta name="description" content="{{ __('Contact BMG for quality steel products. Get in touch with our team for inquiries, quotes, and support.') }}">
    <meta name="keywords" content="contact BMG, steel inquiry, steel quote, steel supplier contact">
    <link rel="canonical" href="{{ route('contact') }}">
    <meta property="og:type" content="website">
    <meta property="og:title" content="{{ __('Contact Us') }} - {{ config('app.name') }}">
    <meta property="og:description" content="{{ __('Contact BMG for quality steel products. Get in touch with our team.') }}">
    <meta property="og:url" content="{{ route('contact') }}">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="{{ __('Contact Us') }} - {{ config('app.name') }}">
    <meta name="twitter:description" content="{{ __('Contact BMG for quality steel products. Get in touch with our team.') }}">
@endsection

@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
    $socialLinks = $settingService->getSocialLinks();
@endphp

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
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ __('Contact Us') }}</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 4.435a1 1 0 01-.54 1.06l-1.548.773a11.037 11.037 0 006.105 6.105l.774-1.548a1 1 0 011.059-.54l4.435.74a1 1 0 01.836.986V17a1 1 0 01-1 1h-2C7.82 18 2 12.18 2 5V3z"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Get In Touch @else Hubungi Kami @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Let's <span class="text-secondary-400">Connect</span> With Us
                    @else
                        Mari <span class="text-secondary-400">Terhubung</span> dengan Kami
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Have questions about our products? Need a quote? Our team is ready to assist you with all your steel requirements.
                    @else
                        Punya pertanyaan tentang produk kami? Butuh penawaran? Tim kami siap membantu semua kebutuhan besi baja Anda.
                    @endif
                </p>
                
                {{-- Quick Contact Buttons --}}
                <div class="flex flex-wrap gap-4 mt-8">
                    @if(!empty($socialLinks['whatsapp']))
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp']) }}" target="_blank" 
                        class="inline-flex items-center gap-2 bg-green-500 text-white px-6 py-3 rounded-xl font-semibold hover:bg-green-600 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                    @endif
                    @if(!empty($companyInfo['phone']))
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" 
                        class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-6 py-3 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $companyInfo['phone'] }}
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Contact Cards --}}
    <section class="py-12 md:py-16 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {{-- Phone --}}
                <div class="group bg-gradient-to-br from-primary-50 to-white rounded-2xl p-6 border border-primary-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-primary-200">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">@if(app()->getLocale() === 'en') Call Us @else Telepon Kami @endif</h3>
                    <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone'] ?? '') }}" class="text-primary-600 hover:text-primary-700 font-medium">
                        {{ $companyInfo['phone'] ?? '+62 21 1234 5678' }}
                    </a>
                </div>
                
                {{-- WhatsApp --}}
                <div class="group bg-gradient-to-br from-green-50 to-white rounded-2xl p-6 border border-green-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-green-200">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">WhatsApp</h3>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp'] ?? $companyInfo['whatsapp'] ?? '') }}" target="_blank" class="text-green-600 hover:text-green-700 font-medium">
                        @if(app()->getLocale() === 'en') Chat Now @else Chat Sekarang @endif
                    </a>
                </div>
                
                {{-- Email --}}
                <div class="group bg-gradient-to-br from-secondary-50 to-white rounded-2xl p-6 border border-secondary-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary-500 to-secondary-600 text-white rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-secondary-200">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">Email</h3>
                    <x-protected-email 
                        :email="$companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'" 
                        class="text-secondary-600 hover:text-secondary-700 font-medium"
                    />
                </div>
                
                {{-- Location --}}
                <div class="group bg-gradient-to-br from-accent-50 to-white rounded-2xl p-6 border border-accent-100 hover:shadow-xl hover:-translate-y-1 transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-accent-500 to-accent-600 text-white rounded-2xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform shadow-lg shadow-accent-200">
                        <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="font-bold text-lg text-gray-900 mb-2">@if(app()->getLocale() === 'en') Visit Us @else Kunjungi Kami @endif</h3>
                    <p class="text-accent-600 font-medium text-sm">@if(app()->getLocale() === 'en') See on Map @else Lihat di Peta @endif ↓</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Contact Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-5 gap-8 lg:gap-12">
                {{-- Contact Info Sidebar --}}
                <div class="lg:col-span-2">
                    <div class="sticky top-24">
                        <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            @if(app()->getLocale() === 'en') Contact Information @else Informasi Kontak @endif
                        </div>
                        
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-heading">
                            @if(app()->getLocale() === 'en')
                                Ready to <span class="text-primary-600">Help</span> You
                            @else
                                Siap <span class="text-primary-600">Membantu</span> Anda
                            @endif
                        </h2>
                        
                        <p class="text-gray-600 mb-8 leading-relaxed">
                            @if(app()->getLocale() === 'en')
                                Our team is available during business hours to answer your questions and provide expert consultation.
                            @else
                                Tim kami tersedia selama jam kerja untuk menjawab pertanyaan Anda dan memberikan konsultasi ahli.
                            @endif
                        </p>
                        
                        <div class="space-y-6">
                            {{-- Address --}}
                            <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Address') }}</h3>
                                    <p class="text-gray-600 text-sm leading-relaxed">{{ $companyInfo['address'] ?? 'Jl. Raya Industri No. 123, Jakarta, Indonesia' }}</p>
                                </div>
                            </div>

                            {{-- Phone --}}
                            <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Phone Number') }}</h3>
                                    <p class="text-gray-600 text-sm">
                                        <a href="tel:{{ $companyInfo['phone'] ?? '' }}" class="hover:text-primary-600 transition-colors">{{ $companyInfo['phone'] ?? '+62 21 1234 5678' }}</a>
                                    </p>
                                    @if(!empty($socialLinks['whatsapp']))
                                    <p class="text-gray-600 text-sm mt-1">
                                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp']) }}" target="_blank" class="hover:text-green-600 transition-colors flex items-center gap-1">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/></svg>
                                            WhatsApp
                                        </a>
                                    </p>
                                    @endif
                                </div>
                            </div>

                            {{-- Email --}}
                            <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Email Address') }}</h3>
                                    <x-protected-email 
                                        :email="$companyInfo['email'] ?? 'info@berkahmandiriglobalindo.com'" 
                                        class="text-gray-600 text-sm hover:text-primary-600 transition-colors"
                                    />
                                </div>
                            </div>

                            {{-- Working Hours --}}
                            <div class="flex items-start gap-4 p-4 bg-white rounded-xl border border-gray-100 hover:shadow-md transition-shadow">
                                <div class="w-12 h-12 bg-accent-100 text-accent-600 rounded-xl flex items-center justify-center flex-shrink-0">
                                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="font-semibold text-gray-900 mb-1">{{ __('Working Hours') }}</h3>
                                    @if(!empty($companyInfo['business_hours_weekday']) || !empty($companyInfo['business_hours_weekend']) || !empty($companyInfo['business_hours_sunday']))
                                        @if(!empty($companyInfo['business_hours_weekday']))
                                            <p class="text-gray-600 text-sm">{{ $companyInfo['business_hours_weekday'] }}</p>
                                        @endif
                                        @if(!empty($companyInfo['business_hours_weekend']))
                                            <p class="text-gray-600 text-sm">{{ $companyInfo['business_hours_weekend'] }}</p>
                                        @endif
                                        @if(!empty($companyInfo['business_hours_sunday']))
                                            <p class="text-gray-600 text-sm">{{ $companyInfo['business_hours_sunday'] }}</p>
                                        @endif
                                    @else
                                        <p class="text-gray-600 text-sm">
                                            {{ app()->getLocale() === 'id' ? 'Sen - Jum: 08:00 - 17:00' : 'Mon - Fri: 08:00 - 17:00' }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Social Links --}}
                        <div class="mt-8 p-6 bg-white rounded-2xl border border-gray-100">
                            <h3 class="font-semibold text-gray-900 mb-4">{{ __('Follow Us') }}</h3>
                            <div class="flex flex-wrap gap-3">
                                @foreach(['facebook', 'instagram', 'youtube', 'tiktok', 'twitter', 'linkedin'] as $platform)
                                    @if(!empty($socialLinks[$platform]) && (!empty($socialLinks[$platform . '_active']) && $socialLinks[$platform . '_active'] == '1'))
                                    <a href="{{ $socialLinks[$platform] }}" target="_blank" rel="noopener noreferrer" 
                                       class="w-11 h-11 flex items-center justify-center rounded-xl 
                                       @if($platform === 'instagram') bg-gradient-to-br from-purple-600 via-pink-500 to-orange-400 
                                       @elseif($platform === 'facebook') bg-blue-600 hover:bg-blue-700 
                                       @elseif($platform === 'youtube') bg-red-600 hover:bg-red-700 
                                       @elseif($platform === 'twitter') bg-black hover:bg-gray-800 
                                       @elseif($platform === 'linkedin') bg-blue-700 hover:bg-blue-800 
                                       @else bg-black hover:bg-gray-800 
                                       @endif text-white transition-all hover:scale-110"
                                       aria-label="{{ ucfirst($platform) }}">
                                        <x-dynamic-component :component="'icons.' . $platform" class="w-5 h-5" />
                                    </a>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Contact Form --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 lg:p-10">
                        <div class="mb-8">
                            <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-full text-sm font-semibold mb-4">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                                    <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                                </svg>
                                @if(app()->getLocale() === 'en') Send Message @else Kirim Pesan @endif
                            </div>
                            <h2 class="text-2xl md:text-3xl font-bold text-gray-900 font-heading">
                                @if(app()->getLocale() === 'en')
                                    Get In Touch With Us
                                @else
                                    Hubungi Kami Sekarang
                                @endif
                            </h2>
                            <p class="text-gray-600 mt-2">
                                @if(app()->getLocale() === 'en')
                                    Fill out the form below and we'll get back to you as soon as possible.
                                @else
                                    Isi formulir di bawah dan kami akan segera menghubungi Anda.
                                @endif
                            </p>
                        </div>
                        
                        @livewire('contact-form')
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Map Section --}}
    <section class="relative">
        {{-- Map Header --}}
        <div class="bg-gradient-to-r from-primary-900 to-primary-700 text-white py-8">
            <div class="container">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl md:text-3xl font-bold font-heading">
                            @if(app()->getLocale() === 'en') Find Us on Map @else Temukan Kami di Peta @endif
                        </h2>
                        <p class="text-primary-200 mt-1">{{ $companyInfo['address'] ?? 'Jl. Raya Industri No. 123, Jakarta' }}</p>
                    </div>
                    <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($companyInfo['address'] ?? 'PT Berkah Mandiri Globalindo Jakarta') }}" 
                       target="_blank" 
                       class="inline-flex items-center gap-2 bg-white text-primary-900 px-6 py-3 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Open in Google Maps @else Buka di Google Maps @endif
                    </a>
                </div>
            </div>
        </div>
        
        {{-- Map Embed --}}
        @if(!empty($companyInfo['google_maps_embed']) || !empty($companyInfo['maps_embed']))
        @php
            $mapEmbed = $companyInfo['google_maps_embed'] ?? $companyInfo['maps_embed'];
            // Sanitize: only allow iframe from google.com/maps
            $isValidEmbed = preg_match('/<iframe[^>]+src=["\']https:\/\/www\.google\.com\/maps\/embed[^"\'>]+["\'][^>]*>/i', $mapEmbed);
        @endphp
        @if($isValidEmbed)
        <div class="w-full h-[400px] md:h-[500px] [&>iframe]:w-full [&>iframe]:h-full [&>iframe]:border-0">
            {!! $mapEmbed !!}
        </div>
        @else
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid" class="w-full h-[400px] md:h-[500px] border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
        @else
        <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.0!2d106.8!3d-6.2!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zNsKwMTInMDAuMCJTIDEwNsKwNDgnMDAuMCJF!5e0!3m2!1sen!2sid!4v1600000000000!5m2!1sen!2sid" class="w-full h-[400px] md:h-[500px] border-0" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        @endif
    </section>
@endsection

