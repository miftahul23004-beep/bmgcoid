@extends('layouts.app')

@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
@endphp

@section('title')
    @if(app()->getLocale() === 'en')
        Privacy Policy - {{ config('app.name') }}
    @else
        Kebijakan Privasi - {{ config('app.name') }}
    @endif
@endsection

@section('meta_description')
    @if(app()->getLocale() === 'en')
        PT. Berkah Mandiri Globalindo privacy policy regarding the collection, use, and protection of your personal data.
    @else
        Kebijakan privasi PT. Berkah Mandiri Globalindo mengenai pengumpulan, penggunaan, dan perlindungan data pribadi Anda.
    @endif
@endsection

@php
    $canonicalUrl = route('privacy');
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

        {{-- Shield decorative icon --}}
        <div class="absolute top-1/2 right-10 -translate-y-1/2 opacity-5 hidden xl:block">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M12 1L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-4zm0 10.99h7c-.53 4.12-3.28 7.79-7 8.94V12H5V6.3l7-3.11v8.8z"/>
            </svg>
        </div>
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">
                        @if(app()->getLocale() === 'en')
                            Privacy Policy
                        @else
                            Kebijakan Privasi
                        @endif
                    </li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    @if(app()->getLocale() === 'en')
                        Last Updated: {{ now()->format('F d, Y') }}
                    @else
                        Terakhir Diperbarui: {{ now()->format('d F Y') }}
                    @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Privacy <span class="text-secondary-400">Policy</span>
                    @else
                        Kebijakan <span class="text-secondary-400">Privasi</span>
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        We value your privacy and are committed to protecting your personal data with the highest security standards
                    @else
                        Kami menghargai privasi Anda dan berkomitmen melindungi data pribadi Anda dengan standar keamanan tertinggi
                    @endif
                </p>
            </div>
        </div>
    </section>
    
    {{-- Trust Indicators --}}
    <section class="py-8 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') SSL Secured @else SSL Terenkripsi @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Data Protected @else Data Terlindungi @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Compliant @else Sesuai Regulasi @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') 24/7 Monitoring @else Monitoring 24/7 @endif</h2>
                </div>
            </div>
        </div>
    </section>

    {{-- Content Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Sidebar Navigation --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                        <h3 class="text-sm font-bold text-gray-900 mb-4 uppercase tracking-wider">
                            @if(app()->getLocale() === 'en') Quick Navigation @else Navigasi Cepat @endif
                        </h3>
                        <nav class="space-y-2">
                            @if(app()->getLocale() === 'en')
                                <a href="#introduction" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">1</span>
                                    Introduction
                                </a>
                                <a href="#information" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">2</span>
                                    Information
                                </a>
                                <a href="#usage" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">3</span>
                                    Usage
                                </a>
                                <a href="#security" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">4</span>
                                    Security
                                </a>
                                <a href="#rights" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">7</span>
                                    Your Rights
                                </a>
                                <a href="#contact" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">9</span>
                                    Contact
                                </a>
                            @else
                                <a href="#pendahuluan" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">1</span>
                                    Pendahuluan
                                </a>
                                <a href="#informasi" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">2</span>
                                    Informasi
                                </a>
                                <a href="#penggunaan" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">3</span>
                                    Penggunaan
                                </a>
                                <a href="#keamanan" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">4</span>
                                    Keamanan
                                </a>
                                <a href="#hak" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">7</span>
                                    Hak Anda
                                </a>
                                <a href="#kontak" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">9</span>
                                    Kontak
                                </a>
                            @endif
                        </nav>
                        
                        {{-- Download Button --}}
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('privacy.pdf') }}" class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-700 px-4 py-3 rounded-xl text-sm font-medium transition-all">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                @if(app()->getLocale() === 'en') Download PDF @else Unduh PDF @endif
                            </a>
                        </div>
                    </div>
                </div>
                
                {{-- Main Content --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
                    <div class="px-8 py-10 md:px-12 md:py-14 prose prose-lg max-w-none">
                        @if(app()->getLocale() === 'en')
                            {{-- English Content --}}
                            <div id="introduction" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">1</span>
                                    Introduction
                                </h2>
                                <p class="text-gray-600 leading-relaxed">PT. Berkah Mandiri Globalindo ("BMG", "we", "us") values the privacy of our website visitors. This privacy policy explains how we collect, use, and protect your personal information.</p>
                            </div>

                            <div id="information" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">2</span>
                                    Information We Collect
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">We may collect the following information:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Name and contact information (email, phone number)</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Company name and job title</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Information about your project or needs</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Website usage data (cookies, log files)</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="usage" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">3</span>
                                    Use of Information
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">The information collected is used to:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Respond to your inquiries and requests</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Provide price quotes and product information</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Improve our services and user experience</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Send promotional information (with consent)</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="security" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">4</span>
                                    Data Security
                                </h2>
                                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                    <div class="flex gap-4">
                                        <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                        </svg>
                                        <p class="text-gray-700 leading-relaxed">We implement appropriate security measures to protect your personal information from unauthorized access, alteration, disclosure, or destruction.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">5</span>
                                    Cookies
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Our website uses cookies to enhance user experience. You can set your browser to reject cookies, but some features may not function optimally.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">6</span>
                                    Information Sharing
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">We do not sell or rent your personal information to third parties. Information is only shared with:</p>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Business partners who assist our operations</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Authorities when required by law</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="rights" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">7</span>
                                    Your Rights
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">You have the right to:</p>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Access the personal data we store</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Request correction of inaccurate data</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Request deletion of your personal data</span>
                                        </div>
                                    </div>
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex items-start gap-3">
                                            <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                            </svg>
                                            <span class="text-sm text-gray-700">Opt out of marketing communications</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">8</span>
                                    Policy Changes
                                </h2>
                                <p class="text-gray-600 leading-relaxed">We may update this privacy policy from time to time. Changes will be announced on this page with the latest update date.</p>
                            </div>

                            <div id="contact" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">9</span>
                                    Contact Us
                                </h2>
                                <div class="bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-xl p-6 border border-primary-200">
                                    <p class="text-gray-700 mb-4">If you have questions about this privacy policy, please contact us at:</p>
                                    <div class="space-y-3">
                                        @if(!empty($companyInfo['email']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Email</div>
                                                <a href="mailto:{{ $companyInfo['email'] }}" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">{{ $companyInfo['email'] }}</a>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['phone']))
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Phone</div>
                                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">{{ $companyInfo['phone'] }}</a>
                                            </div>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['address']))
                                        <div class="flex items-start gap-3">
                                            <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                                                <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                </svg>
                                            </div>
                                            <div>
                                                <div class="text-xs text-gray-500">Address</div>
                                                <span class="font-medium text-gray-900">{{ $companyInfo['address'] }}</span>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @else
                            {{-- Indonesian Content --}}
                        <div id="pendahuluan" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">1</span>
                                Pendahuluan
                            </h2>
                            <p class="text-gray-600 leading-relaxed">PT. Berkah Mandiri Globalindo ("BMG", "kami", "kita") menghargai privasi pengunjung website kami. Kebijakan privasi ini menjelaskan bagaimana kami mengumpulkan, menggunakan, dan melindungi informasi pribadi Anda.</p>
                        </div>

                        <div id="informasi" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">2</span>
                                Informasi yang Kami Kumpulkan
                            </h2>
                            <p class="text-gray-600 leading-relaxed mb-4">Kami dapat mengumpulkan informasi berikut:</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Nama dan informasi kontak (email, nomor telepon)</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Nama perusahaan dan jabatan</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Informasi tentang proyek atau kebutuhan Anda</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Data penggunaan website (cookies, log files)</span>
                                </li>
                            </ul>
                        </div>

                        <div id="penggunaan" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">3</span>
                                Penggunaan Informasi
                            </h2>
                            <p class="text-gray-600 leading-relaxed mb-4">Informasi yang dikumpulkan digunakan untuk:</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Merespons pertanyaan dan permintaan Anda</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Memberikan penawaran harga dan informasi produk</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Meningkatkan layanan dan pengalaman pengguna</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Mengirimkan informasi promosi (dengan persetujuan)</span>
                                </li>
                            </ul>
                        </div>

                        <div id="keamanan" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">4</span>
                                Keamanan Data
                            </h2>
                            <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                                <div class="flex gap-4">
                                    <svg class="w-6 h-6 text-blue-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                    </svg>
                                    <p class="text-gray-700 leading-relaxed">Kami mengimplementasikan langkah-langkah keamanan yang sesuai untuk melindungi informasi pribadi Anda dari akses tidak sah, perubahan, pengungkapan, atau penghancuran.</p>
                                </div>
                            </div>
                        </div>

                        <div class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">5</span>
                                Cookies
                            </h2>
                            <p class="text-gray-600 leading-relaxed">Website kami menggunakan cookies untuk meningkatkan pengalaman pengguna. Anda dapat mengatur browser untuk menolak cookies, namun beberapa fitur mungkin tidak berfungsi optimal.</p>
                        </div>

                        <div class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">6</span>
                                Berbagi Informasi
                            </h2>
                            <p class="text-gray-600 leading-relaxed mb-4">Kami tidak menjual atau menyewakan informasi pribadi Anda kepada pihak ketiga. Informasi hanya dibagikan kepada:</p>
                            <ul class="space-y-2">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Mitra bisnis yang membantu operasional kami</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-gray-600">Pihak berwenang jika diwajibkan oleh hukum</span>
                                </li>
                            </ul>
                        </div>

                        <div id="hak" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">7</span>
                                Hak Anda
                            </h2>
                            <p class="text-gray-600 leading-relaxed mb-4">Anda berhak untuk:</p>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">Mengakses data pribadi yang kami simpan</span>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">Meminta koreksi data yang tidak akurat</span>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">Meminta penghapusan data pribadi Anda</span>
                                    </div>
                                </div>
                                <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                    <div class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                        </svg>
                                        <span class="text-sm text-gray-700">Menolak penggunaan data untuk pemasaran</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">8</span>
                                Perubahan Kebijakan
                            </h2>
                            <p class="text-gray-600 leading-relaxed">Kami dapat memperbarui kebijakan privasi ini sewaktu-waktu. Perubahan akan diumumkan di halaman ini dengan tanggal pembaruan terbaru.</p>
                        </div>

                        <div id="kontak" class="scroll-mt-20">
                            <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">9</span>
                                Hubungi Kami
                            </h2>
                            <div class="bg-gradient-to-br from-primary-50 to-primary-100/50 rounded-xl p-6 border border-primary-200">
                                <p class="text-gray-700 mb-4">Jika Anda memiliki pertanyaan tentang kebijakan privasi ini, silakan hubungi kami melalui:</p>
                                <div class="space-y-3">
                                    @if(!empty($companyInfo['email']))
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Email</div>
                                            <a href="mailto:{{ $companyInfo['email'] }}" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">{{ $companyInfo['email'] }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty($companyInfo['phone']))
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Telepon</div>
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" class="font-medium text-gray-900 hover:text-primary-600 transition-colors">{{ $companyInfo['phone'] }}</a>
                                        </div>
                                    </div>
                                    @endif
                                    @if(!empty($companyInfo['address']))
                                    <div class="flex items-start gap-3">
                                        <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center flex-shrink-0">
                                            <svg class="w-5 h-5 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                        </div>
                                        <div>
                                            <div class="text-xs text-gray-500">Alamat</div>
                                            <span class="font-medium text-gray-900">{{ $companyInfo['address'] }}</span>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endif
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
                    @if(app()->getLocale() === 'en') Need Assistance? @else Butuh Bantuan? @endif
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 font-heading">
                    @if(app()->getLocale() === 'en')
                        Still Have <span class="text-secondary-400">Questions?</span>
                    @else
                        Masih Ada <span class="text-secondary-400">Pertanyaan?</span>
                    @endif
                </h2>
                
                <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        If you have any questions about our privacy policy or how we handle your data, our team is here to help.
                    @else
                        Jika Anda memiliki pertanyaan tentang kebijakan privasi kami atau bagaimana kami menangani data Anda, tim kami siap membantu.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                    <a href="{{ route('terms') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Terms of Service @else Syarat & Ketentuan @endif
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

