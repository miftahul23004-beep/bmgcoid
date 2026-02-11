@extends('layouts.app')

@section('title', __('Request Quote') . ' - ' . config('app.name'))
@section('meta_description', __('Request a free quote for steel products. Get competitive prices for rebar, hollow sections, pipes, plates, and more from PT. Berkah Mandiri Globalindo.'))

@php
    $settingService = app(\App\Services\SettingService::class);
    $companyInfo = $settingService->getCompanyInfo();
    $socialLinks = $settingService->getSocialLinks();
@endphp

@section('content')
    {{-- Hero Section with Overlay --}}
    <section class="relative bg-gradient-to-br from-secondary-600 via-secondary-500 to-secondary-400 text-white py-16 md:py-20 overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse [animation-delay:1s]" ></div>
        </div>
        
        {{-- Grid pattern overlay --}}
        <div class="absolute inset-0 opacity-5 bg-pattern-cross"></div>

        {{-- Calculator decorative icon --}}
        <div class="absolute top-1/2 right-10 -translate-y-1/2 opacity-5 hidden xl:block">
            <svg class="w-96 h-96" fill="currentColor" viewBox="0 0 24 24">
                <path d="M19 3H5c-1.1 0-2 .9-2 2v14c0 1.1.9 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2zm-5 14h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zm4 8h-2v-2h2v2zm0-4h-2v-2h2v2zm0-4h-2V7h2v2zM6 9h5V7H6v2zm0 4h5v-2H6v2zm0 4h5v-2H6v2z"/>
            </svg>
        </div>
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-secondary-100 hover:text-white transition-colors">{{ __('Home Page') }}</a></li>
                    <li><span class="text-secondary-200">/</span></li>
                    <li class="text-white">{{ __('Request Quote') }}</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 00-2 2v4a2 2 0 002 2V6h10a2 2 0 00-2-2H4zm2 6a2 2 0 012-2h8a2 2 0 012 2v4a2 2 0 01-2 2H8a2 2 0 01-2-2v-4zm6 4a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Get Best Price @else Dapatkan Harga Terbaik @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Request a Quote
                    @else
                        Minta Penawaran
                    @endif
                </h1>
                
                <p class="text-xl text-secondary-50 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Fill out the form below to get the best price quote from us. Our team will respond within 1-2 business days.
                    @else
                        Isi formulir di bawah untuk mendapatkan penawaran harga terbaik dari kami. Tim kami akan merespons dalam 1-2 hari kerja.
                    @endif
                </p>
            </div>
        </div>
    </section>
    
    {{-- Quick Stats --}}
    <section class="py-8 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                <div class="text-center">
                    <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Quick Response @else Respon Cepat @endif</h2>
                    <p class="text-xs text-gray-500">1-2 @if(app()->getLocale() === 'en') Business Days @else Hari Kerja @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Best Price @else Harga Terbaik @endif</h2>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Competitive @else Kompetitif @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Detailed Quote @else Penawaran Detail @endif</h2>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Complete Specs @else Spesifikasi Lengkap @endif</p>
                </div>
                <div class="text-center">
                    <div class="w-12 h-12 bg-purple-100 text-purple-600 rounded-xl flex items-center justify-center mx-auto mb-3">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h2 class="font-semibold text-gray-900 text-sm">@if(app()->getLocale() === 'en') Expert Consultation @else Konsultasi Ahli @endif</h2>
                    <p class="text-xs text-gray-500">@if(app()->getLocale() === 'en') Free @else Gratis @endif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Form Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
                {{-- Sidebar --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-24 space-y-6">
                        {{-- Why Request Quote --}}
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                            <h3 class="font-bold text-gray-900 mb-4">
                                @if(app()->getLocale() === 'en') Why Request Quote? @else Mengapa Minta Penawaran? @endif
                            </h3>
                            <ul class="space-y-3">
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">@if(app()->getLocale() === 'en') Special bulk pricing @else Harga khusus untuk jumlah besar @endif</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">@if(app()->getLocale() === 'en') Custom specifications @else Spesifikasi custom @endif</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">@if(app()->getLocale() === 'en') Delivery cost included @else Termasuk ongkos kirim @endif</span>
                                </li>
                                <li class="flex items-start gap-3">
                                    <svg class="w-5 h-5 text-green-500 mt-0.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    <span class="text-sm text-gray-600">@if(app()->getLocale() === 'en') Payment flexibility @else Fleksibilitas pembayaran @endif</span>
                                </li>
                            </ul>
                        </div>
                        
                        {{-- Contact Info --}}
                        <div class="bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl shadow-sm p-6 text-white">
                            <h3 class="font-bold mb-4">
                                @if(app()->getLocale() === 'en') Need Help? @else Butuh Bantuan? @endif
                            </h3>
                            <p class="text-sm text-secondary-100 mb-4">
                                @if(app()->getLocale() === 'en') Contact us directly for immediate assistance. @else Hubungi kami langsung untuk bantuan segera. @endif
                            </p>
                            <div class="space-y-3">
                                @if(!empty($socialLinks['whatsapp']))
                                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $socialLinks['whatsapp']) }}?text={{ urlencode(__('Hello, I would like to ask about your products.')) }}" target="_blank" class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl hover:bg-white/20 transition-all">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                        <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                    </svg>
                                    <span class="text-sm font-medium">WhatsApp</span>
                                </a>
                                @endif
                                @if(!empty($companyInfo['phone']))
                                <a href="tel:{{ preg_replace('/[^0-9+]/', '', $companyInfo['phone']) }}" class="flex items-center gap-3 bg-white/10 backdrop-blur-sm px-4 py-3 rounded-xl hover:bg-white/20 transition-all">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                    </svg>
                                    <span class="text-sm font-medium">{{ $companyInfo['phone'] }}</span>
                                </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Form --}}
                <div class="lg:col-span-3">
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 lg:p-10">
                        @if(session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-700 px-6 py-4 rounded-xl mb-8 flex items-center gap-3">
                                <svg class="w-6 h-6 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>{{ session('success') }}</span>
                            </div>
                        @endif

                        <form action="{{ route('quote.submit') }}" method="POST" x-data="quoteForm()">
                            @csrf
                            
                            {{-- Contact Info --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900">@if(app()->getLocale() === 'en') Contact Information @else Informasi Kontak @endif</h2>
                                </div>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Name') }} <span class="text-red-600">*</span></label>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all @error('name') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Your full name @else Nama lengkap Anda @endif">
                                        @error('name')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Email') }} <span class="text-red-600">*</span></label>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all @error('email') border-red-500 @enderror" placeholder="email@example.com">
                                        @error('email')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Phone') }} <span class="text-red-600">*</span></label>
                                        <input type="text" name="phone" id="phone" value="{{ old('phone') }}" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all @error('phone') border-red-500 @enderror" placeholder="08xxxxxxxxxx">
                                        @error('phone')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div>
                                        <label for="company" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Company') }}</label>
                                        <input type="text" name="company" id="company" value="{{ old('company') }}" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all @error('company') border-red-500 @enderror" placeholder="@if(app()->getLocale() === 'en') Company name (optional) @else Nama perusahaan (opsional) @endif">
                                        @error('company')
                                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Product Selection --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-blue-100 text-blue-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                        </svg>
                                    </div>
                                    <div>
                                        <h2 class="text-xl font-bold text-gray-900">{{ __('Select Products') }}</h2>
                                        <p class="text-sm text-gray-500">@if(app()->getLocale() === 'en') Choose products you need @else Pilih produk yang Anda butuhkan @endif</p>
                                    </div>
                                </div>
                                
                                <div class="space-y-4">
                                    @foreach($products->groupBy('category_id') as $categoryId => $categoryProducts)
                                        @php $category = $categoryProducts->first()->category; @endphp
                                        <div class="border border-gray-200 rounded-2xl overflow-hidden" x-data="{ open: {{ collect($categoryProducts)->contains(fn($p) => $p->id == request('product')) ? 'true' : 'false' }} }">
                                            <button type="button" @click="open = !open" class="w-full flex items-center justify-between p-5 text-left bg-gray-50 hover:bg-gray-100 transition-colors">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-8 h-8 bg-white rounded-lg flex items-center justify-center shadow-sm">
                                                        <svg class="w-4 h-4 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                                                        </svg>
                                                    </div>
                                                    <span class="font-semibold text-gray-900">{{ $category?->getTranslation('name', app()->getLocale()) ?? 'Uncategorized' }}</span>
                                                    <span class="text-xs bg-gray-200 text-gray-600 px-2 py-1 rounded-full">{{ $categoryProducts->count() }} @if(app()->getLocale() === 'en') products @else produk @endif</span>
                                                </div>
                                                <svg class="w-5 h-5 text-gray-500 transform transition-transform" :class="open ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                                </svg>
                                            </button>
                                            <div x-show="open" x-collapse class="p-5 space-y-3 bg-white">
                                                @foreach($categoryProducts as $product)
                                                    <div class="flex items-start gap-4 p-3 rounded-xl hover:bg-gray-50 transition-colors">
                                                        <input type="checkbox" name="products[]" value="{{ $product->id }}" id="product_{{ $product->id }}" class="mt-1 w-5 h-5 text-secondary-600 border-gray-300 rounded focus:ring-secondary-500" x-model="selectedProducts" @change="updateProducts">
                                                        <div class="flex-1">
                                                            <label for="product_{{ $product->id }}" class="font-medium text-gray-900 cursor-pointer">{{ $product->getTranslation('name', app()->getLocale()) }}</label>
                                                            @if($product->sku)
                                                                <span class="text-sm text-gray-500 ml-2">({{ $product->sku }})</span>
                                                            @endif
                                                        </div>
                                                        <div x-show="selectedProducts.includes('{{ $product->id }}')" class="w-28">
                                                            <input type="text" name="quantities[{{ $product->id }}]" placeholder="@if(app()->getLocale() === 'en') Qty @else Jumlah @endif" class="w-full text-sm py-2 px-3 border border-gray-200 rounded-lg focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500">
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                
                                @error('products')
                                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                                @enderror

                                <div class="mt-4 flex items-center gap-2 text-sm">
                                    <span class="bg-secondary-100 text-secondary-700 px-3 py-1 rounded-full font-medium" x-text="selectedProducts.length + ' @if(app()->getLocale() === 'en') products selected @else produk dipilih @endif'"></span>
                                </div>
                            </div>

                            {{-- Additional Info --}}
                            <div class="mb-10">
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="w-10 h-10 bg-green-100 text-green-600 rounded-xl flex items-center justify-center">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                        </svg>
                                    </div>
                                    <h2 class="text-xl font-bold text-gray-900">@if(app()->getLocale() === 'en') Additional Information @else Informasi Tambahan @endif</h2>
                                </div>
                                <div class="space-y-6">
                                    <div>
                                        <label for="specifications" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Specifications') }}</label>
                                        <textarea name="specifications" id="specifications" rows="3" placeholder="@if(app()->getLocale() === 'en') Special specifications needed (optional) @else Spesifikasi khusus yang dibutuhkan (opsional) @endif" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all">{{ old('specifications') }}</textarea>
                                    </div>
                                    <div>
                                        <label for="delivery_location" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Delivery Location') }}</label>
                                        <input type="text" name="delivery_location" id="delivery_location" value="{{ old('delivery_location') }}" placeholder="@if(app()->getLocale() === 'en') Delivery address/city @else Alamat/kota tujuan pengiriman @endif" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all">
                                    </div>
                                    <div>
                                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">{{ __('Additional Notes') }}</label>
                                        <textarea name="notes" id="notes" rows="3" placeholder="@if(app()->getLocale() === 'en') Additional notes (optional) @else Catatan tambahan (opsional) @endif" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-secondary-500 focus:border-secondary-500 transition-all">{{ old('notes') }}</textarea>
                                    </div>
                                </div>
                            </div>

                            {{-- Submit --}}
                            <div class="flex flex-col sm:flex-row items-start sm:items-center gap-4 pt-6 border-t border-gray-100">
                                <button type="submit" class="inline-flex items-center gap-2 bg-gradient-to-r from-secondary-600 to-secondary-500 text-white px-8 py-4 rounded-xl font-semibold hover:from-secondary-700 hover:to-secondary-600 transition-all hover:scale-105 shadow-lg disabled:opacity-50 disabled:cursor-not-allowed" :disabled="selectedProducts.length === 0">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                    </svg>
                                    {{ __('Submit') }}
                                </button>
                                <span class="text-sm text-gray-500">
                                    <svg class="w-4 h-4 inline-block mr-1 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    @if(app()->getLocale() === 'en') Our team will contact you within 1-2 business days. @else Tim kami akan menghubungi Anda dalam 1-2 hari kerja. @endif
                                </span>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
        function quoteForm() {
            return {
                selectedProducts: @json(old('products', request('product') ? [strval(request('product'))] : [])),
                updateProducts() {
                    // Auto-update UI when products are selected/deselected
                }
            }
        }
    </script>
@endsection

