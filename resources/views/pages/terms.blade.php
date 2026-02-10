@extends('layouts.app')

@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
@endphp

@section('title')
    @if(app()->getLocale() === 'en')
        Terms & Conditions - {{ config('app.name') }}
    @else
        Syarat & Ketentuan - {{ config('app.name') }}
    @endif
@endsection

@section('meta_description')
    @if(app()->getLocale() === 'en')
        PT. Berkah Mandiri Globalindo terms and conditions regarding the use of our services and website.
    @else
        Syarat dan ketentuan PT. Berkah Mandiri Globalindo mengenai penggunaan layanan dan website kami.
    @endif
@endsection

@php
    $canonicalUrl = route('terms');
@endphp

@section('content')
    {{-- Hero Section with Overlay --}}
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white py-16 md:py-20 overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse [animation-delay:1s]" ></div>
        </div>
        
        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 opacity-5 bg-pattern-cross"></div>

        {{-- Document decorative icon --}}
        <div class="absolute top-1/2 right-10 -translate-y-1/2 opacity-5 hidden xl:block">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8l-6-6zm4 18H6V4h7v5h5v11zM9 13h6v2H9v-2zm0 4h6v2H9v-2z"/>
            </svg>
        </div>
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home Page') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">
                        @if(app()->getLocale() === 'en')
                            Terms & Conditions
                        @else
                            Syarat & Ketentuan
                        @endif
                    </li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    @if(app()->getLocale() === 'en')
                        Last Updated: {{ now()->format('F d, Y') }}
                    @else
                        Terakhir Diperbarui: {{ now()->format('d F Y') }}
                    @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Terms & <span class="text-secondary-400">Conditions</span>
                    @else
                        Syarat & <span class="text-secondary-400">Ketentuan</span>
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Please read these terms carefully before using our services. By using our website and services, you agree to these terms.
                    @else
                        Harap membaca syarat dan ketentuan ini dengan seksama sebelum menggunakan layanan kami. Dengan menggunakan website dan layanan kami, Anda menyetujui ketentuan ini.
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
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Legally Binding @else Mengikat Secara Hukum @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Clear Terms @else Ketentuan Jelas @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Fair Practice @else Praktik Adil @endif</h2>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-orange-100 text-orange-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Indonesian Law @else Hukum Indonesia @endif</h2>
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
                                <a href="#general" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">1</span>
                                    General
                                </a>
                                <a href="#products" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">2</span>
                                    Products
                                </a>
                                <a href="#orders" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">3</span>
                                    Orders
                                </a>
                                <a href="#delivery" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">4</span>
                                    Delivery
                                </a>
                                <a href="#warranty" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">6</span>
                                    Warranty
                                </a>
                                <a href="#contact" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">11</span>
                                    Contact
                                </a>
                            @else
                                <a href="#umum" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">1</span>
                                    Umum
                                </a>
                                <a href="#produk" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">2</span>
                                    Produk
                                </a>
                                <a href="#pemesanan" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">3</span>
                                    Pemesanan
                                </a>
                                <a href="#pengiriman" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">4</span>
                                    Pengiriman
                                </a>
                                <a href="#garansi" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">6</span>
                                    Garansi
                                </a>
                                <a href="#kontak" class="flex items-center gap-3 text-sm text-gray-600 hover:text-primary-600 hover:bg-primary-50 px-3 py-2 rounded-lg transition-all group">
                                    <span class="w-6 h-6 bg-primary-100 text-primary-600 rounded-md flex items-center justify-center text-xs font-bold group-hover:bg-primary-600 group-hover:text-white transition-colors">11</span>
                                    Kontak
                                </a>
                            @endif
                        </nav>
                        
                        {{-- Download Button --}}
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <a href="{{ route('terms.pdf') }}" class="w-full flex items-center justify-center gap-2 bg-gray-100 hover:bg-primary-100 text-gray-700 hover:text-primary-700 px-4 py-3 rounded-xl text-sm font-medium transition-all">
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

                    {{-- Content --}}
                    <div class="px-8 py-10 md:px-12 md:py-14 prose prose-lg max-w-none">
                        @if(app()->getLocale() === 'en')
                            {{-- English Content --}}
                            <div id="general" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">1</span>
                                    General Terms
                                </h2>
                                <p class="text-gray-600 leading-relaxed">By accessing and using the PT. Berkah Mandiri Globalindo website, you agree to be bound by the following terms and conditions.</p>
                            </div>

                            <div id="products" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">2</span>
                                    Product Information
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">All product information on this website is for informational purposes</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Specifications, prices, and availability are subject to change without notice</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Product images may differ from actual products</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">For accurate information, please contact our sales team</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="orders" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">3</span>
                                    Orders and Payment
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Official orders are made through Purchase Order (PO)</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Applicable prices are those at the time of order confirmation</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Payment terms will be informed in the price quotation</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Payments can be made via bank transfer</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="delivery" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">4</span>
                                    Delivery
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Delivery schedule will be confirmed after payment</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Shipping costs are borne by the buyer unless otherwise agreed</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Risk of damage transfers after goods are handed over to courier</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">5</span>
                                    Claims and Returns
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Damage claims must be reported within 1x24 hours after receipt</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Claims must be accompanied by clear photo/video documentation</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Returns are only accepted for factory-defective products</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="warranty" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">6</span>
                                    Warranty
                                </h2>
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-4">
                                    <div class="flex gap-4">
                                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="space-y-2">
                                            <p class="text-gray-700">Products are guaranteed to comply with applicable SNI standards</p>
                                            <p class="text-gray-700">Material certificates are available for each shipment</p>
                                            <p class="text-gray-700">Warranty does not cover damage due to improper storage</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">7</span>
                                    Intellectual Property Rights
                                </h2>
                                <p class="text-gray-600 leading-relaxed">All content on this website including text, images, logos, and designs are owned by PT. Berkah Mandiri Globalindo and protected by copyright law.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">8</span>
                                    Limitation of Liability
                                </h2>
                                <p class="text-gray-600 leading-relaxed">We are not responsible for indirect, incidental, or consequential losses arising from the use of our website or products.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">9</span>
                                    Applicable Law
                                </h2>
                                <p class="text-gray-600 leading-relaxed">These terms and conditions are governed by and construed in accordance with the laws of the Republic of Indonesia. Any disputes will be resolved through mediation or courts in the Surabaya jurisdiction.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">10</span>
                                    Changes to Terms
                                </h2>
                                <p class="text-gray-600 leading-relaxed">We reserve the right to change these terms and conditions at any time. Changes will take effect after being published on the website.</p>
                            </div>

                            <div id="contact" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">11</span>
                                    Contact
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">For questions about these terms and conditions, please contact:</p>
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <div class="space-y-3">
                                        @if(!empty($companyInfo['email']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <a href="mailto:{{ $companyInfo['email'] }}" class="text-gray-700 hover:text-primary-600 transition-colors">{{ $companyInfo['email'] }}</a>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['phone']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" class="text-gray-700 hover:text-primary-600 transition-colors">{{ $companyInfo['phone'] }}</a>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['address']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $companyInfo['address'] }}</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        @else
                            {{-- Indonesian Content --}}
                            <div id="umum" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">1</span>
                                    Ketentuan Umum
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Dengan mengakses dan menggunakan website PT. Berkah Mandiri Globalindo, Anda menyetujui untuk terikat dengan syarat dan ketentuan berikut ini.</p>
                            </div>

                            <div id="produk" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">2</span>
                                    Informasi Produk
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Semua informasi produk di website ini bersifat informatif</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Spesifikasi, harga, dan ketersediaan dapat berubah tanpa pemberitahuan</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Gambar produk mungkin berbeda dengan produk aktual</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Untuk informasi akurat, silakan hubungi tim sales kami</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="pemesanan" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">3</span>
                                    Pemesanan dan Pembayaran
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Pemesanan resmi dilakukan melalui Purchase Order (PO)</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Harga yang berlaku adalah harga pada saat konfirmasi pesanan</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Syarat pembayaran akan diinformasikan pada penawaran harga</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Pembayaran dapat dilakukan melalui transfer bank</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="pengiriman" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">4</span>
                                    Pengiriman
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Jadwal pengiriman akan dikonfirmasi setelah pembayaran</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Biaya pengiriman ditanggung oleh pembeli kecuali disepakati lain</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Risiko kerusakan beralih setelah barang diserahkan ke ekspedisi</span>
                                    </li>
                                </ul>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">5</span>
                                    Klaim dan Retur
                                </h2>
                                <ul class="space-y-2">
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Klaim kerusakan harus dilaporkan dalam 1x24 jam setelah penerimaan</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Klaim harus disertai dokumentasi foto/video yang jelas</span>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <svg class="w-5 h-5 text-primary-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                        </svg>
                                        <span class="text-gray-600">Retur hanya diterima untuk produk yang cacat dari pabrik</span>
                                    </li>
                                </ul>
                            </div>

                            <div id="garansi" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">6</span>
                                    Garansi
                                </h2>
                                <div class="bg-green-50 border border-green-200 rounded-xl p-6 mb-4">
                                    <div class="flex gap-4">
                                        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                        <div class="space-y-2">
                                            <p class="text-gray-700">Produk dijamin sesuai dengan standar SNI yang berlaku</p>
                                            <p class="text-gray-700">Sertifikat material tersedia untuk setiap pengiriman</p>
                                            <p class="text-gray-700">Garansi tidak berlaku untuk kerusakan akibat penyimpanan yang tidak tepat</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">7</span>
                                    Hak Kekayaan Intelektual
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Seluruh konten di website ini termasuk teks, gambar, logo, dan desain adalah milik PT. Berkah Mandiri Globalindo dan dilindungi oleh hukum hak cipta.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">8</span>
                                    Batasan Tanggung Jawab
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Kami tidak bertanggung jawab atas kerugian tidak langsung, insidental, atau konsekuensial yang timbul dari penggunaan website atau produk kami.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">9</span>
                                    Hukum yang Berlaku
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Syarat dan ketentuan ini diatur oleh dan ditafsirkan sesuai dengan hukum Republik Indonesia. Setiap sengketa akan diselesaikan melalui mediasi atau pengadilan di wilayah hukum Surabaya.</p>
                            </div>

                            <div class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">10</span>
                                    Perubahan Ketentuan
                                </h2>
                                <p class="text-gray-600 leading-relaxed">Kami berhak untuk mengubah syarat dan ketentuan ini kapan saja. Perubahan akan berlaku efektif setelah dipublikasikan di website.</p>
                            </div>

                            <div id="kontak" class="scroll-mt-20">
                                <h2 class="flex items-center gap-3 text-2xl font-bold text-gray-900 mb-4 mt-12">
                                    <span class="flex items-center justify-center w-10 h-10 bg-primary-100 text-primary-600 rounded-lg text-lg font-bold">11</span>
                                    Kontak
                                </h2>
                                <p class="text-gray-600 leading-relaxed mb-4">Untuk pertanyaan tentang syarat dan ketentuan ini, silakan hubungi:</p>
                                <div class="bg-gray-50 rounded-xl p-6 border border-gray-200">
                                    <div class="space-y-3">
                                        @if(!empty($companyInfo['email']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                            </svg>
                                            <a href="mailto:{{ $companyInfo['email'] }}" class="text-gray-700 hover:text-primary-600 transition-colors">{{ $companyInfo['email'] }}</a>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['phone']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                            </svg>
                                            <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" class="text-gray-700 hover:text-primary-600 transition-colors">{{ $companyInfo['phone'] }}</a>
                                        </div>
                                        @endif
                                        @if(!empty($companyInfo['address']))
                                        <div class="flex items-center gap-3">
                                            <svg class="w-5 h-5 text-primary-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            </svg>
                                            <span class="text-gray-700">{{ $companyInfo['address'] }}</span>
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
                    @if(app()->getLocale() === 'en') Need Clarification? @else Butuh Penjelasan? @endif
                </div>
                
                <h2 class="text-3xl md:text-4xl font-bold text-white mb-6 font-heading">
                    @if(app()->getLocale() === 'en')
                        Have <span class="text-secondary-400">Questions?</span>
                    @else
                        Ada <span class="text-secondary-400">Pertanyaan?</span>
                    @endif
                </h2>
                
                <p class="text-lg text-primary-100 mb-8 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        If you need clarification about our terms and conditions, feel free to contact our team for assistance.
                    @else
                        Jika Anda memerlukan penjelasan tentang syarat dan ketentuan kami, jangan ragu untuk menghubungi tim kami.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:scale-105 shadow-lg">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                    <a href="{{ route('privacy') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Privacy Policy @else Kebijakan Privasi @endif
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

