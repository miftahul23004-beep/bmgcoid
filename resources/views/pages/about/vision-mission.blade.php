@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', __('Vision & Mission') . ' - ' . config('app.name'))
@section('meta_description', __('Vision and mission of PT. Berkah Mandiri Globalindo. Committed to being the leading steel distributor in Indonesia with quality products and excellent service.'))

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
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home Page') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li><a href="{{ route('about.company') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('About Us') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ __('Vision & Mission') }}</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Our Compass @else Kompas Kami @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Vision & <span class="text-secondary-400">Mission</span>
                    @else
                        Visi & <span class="text-secondary-400">Misi</span>
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Our guiding principles and commitment in serving Indonesia's construction and industrial sectors with excellence and integrity.
                    @else
                        Panduan dan komitmen kami dalam melayani sektor konstruksi dan industri Indonesia dengan keunggulan dan integritas.
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- Vision Section --}}
    <section class="py-16 md:py-24 bg-white overflow-hidden">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Image Side --}}
                <div class="relative order-2 lg:order-1">
                    <div class="relative">
                        {{-- Main Image --}}
                        <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                            @php
                                $visionImage = $staticPageImages['vision_image'] ?? null;
                                $visionImageUrl = $visionImage ? Storage::disk('public')->url($visionImage) : asset('storage/about/vision.webp');
                            @endphp
                            <img src="{{ $visionImageUrl }}" alt="Visi Perusahaan" class="w-full h-full object-cover" width="800" height="600" loading="lazy" decoding="async" onerror="this.src='https://placehold.co/800x600/1E40AF/ffffff?text=Our+Vision'">
                        </div>
                        
                        {{-- Floating Badge --}}
                        <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-primary-600 to-primary-700 text-white p-6 rounded-2xl shadow-2xl">
                            <div class="flex items-center gap-3">
                                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                                    </svg>
                                </div>
                                <div>
                                    <div class="text-2xl font-bold">2030</div>
                                    <div class="text-sm text-primary-200">@if(app()->getLocale() === 'en') Target Year @else Target Tahun @endif</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- Decorative Element --}}
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-secondary-100 rounded-2xl -z-10"></div>
                </div>
                
                {{-- Content Side --}}
                <div class="order-1 lg:order-2">
                    <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                        </svg>
                        {{ __('Our Vision') }}
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-heading leading-tight">
                        @if(app()->getLocale() === 'en')
                            Becoming Indonesia's <span class="text-primary-600">Leading</span> Steel Distributor
                        @else
                            Menjadi Distributor Besi Baja <span class="text-primary-600">Terdepan</span> di Indonesia
                        @endif
                    </h2>
                    
                    <div class="prose prose-lg text-gray-600 mb-8">
                        @if(app()->getLocale() === 'en')
                            <p>To become Indonesia's leading and trusted steel distributor providing the <strong class="text-gray-900">best solutions</strong> for construction and industrial needs with international quality standards, excellent service, and competitive prices.</p>
                        @else
                            <p>Menjadi perusahaan distributor besi baja terdepan dan terpercaya di Indonesia yang memberikan <strong class="text-gray-900">solusi terbaik</strong> untuk kebutuhan konstruksi dan industri dengan standar kualitas internasional, pelayanan prima, dan harga yang kompetitif.</p>
                        @endif
                    </div>
                    
                    {{-- Vision Highlights --}}
                    <div class="grid grid-cols-2 gap-4">
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                            <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900 text-sm">@if(app()->getLocale() === 'en') Market Leader @else Pemimpin Pasar @endif</span>
                        </div>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                            <div class="w-10 h-10 bg-secondary-100 text-secondary-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900 text-sm">@if(app()->getLocale() === 'en') Trusted Partner @else Mitra Terpercaya @endif</span>
                        </div>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                            <div class="w-10 h-10 bg-accent-100 text-accent-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900 text-sm">@if(app()->getLocale() === 'en') Quality Standard @else Standar Kualitas @endif</span>
                        </div>
                        <div class="flex items-center gap-3 bg-gray-50 rounded-xl p-4">
                            <div class="w-10 h-10 bg-green-100 text-green-600 rounded-lg flex items-center justify-center shrink-0">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <span class="font-medium text-gray-900 text-sm">@if(app()->getLocale() === 'en') Best Price @else Harga Terbaik @endif</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Mission Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3 6a3 3 0 013-3h10a1 1 0 01.8 1.6L14.25 8l2.55 3.4A1 1 0 0116 13H6a1 1 0 00-1 1v3a1 1 0 11-2 0V6z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Our Mission') }}
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-heading">
                    @if(app()->getLocale() === 'en') Our Mission @else Misi Kami @endif
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Six strategic pillars that drive our commitment to excellence and customer satisfaction.
                    @else
                        Enam pilar strategis yang mendorong komitmen kami pada keunggulan dan kepuasan pelanggan.
                    @endif
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Mission 1 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200 hover:-translate-y-2" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-primary-200">
                            <span class="text-2xl font-bold">1</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Product Quality @else Kualitas Produk @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Providing high-quality steel products that meet SNI and international standards with rigorous quality control. @else Menyediakan produk besi baja berkualitas tinggi yang memenuhi standar SNI dan internasional dengan quality control ketat. @endif</p>
                    </div>
                </div>

                {{-- Mission 2 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-secondary-200 hover:-translate-y-2 [animation-delay:100ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-secondary-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-secondary-200">
                            <span class="text-2xl font-bold">2</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Excellent Service @else Pelayanan Prima @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Providing the best service with fast response and appropriate solutions for every customer need. @else Memberikan pelayanan terbaik dengan respon cepat dan solusi yang tepat untuk setiap kebutuhan pelanggan. @endif</p>
                    </div>
                </div>

                {{-- Mission 3 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-accent-200 hover:-translate-y-2 [animation-delay:200ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent-500 to-accent-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-accent-200">
                            <span class="text-2xl font-bold">3</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Competitive Prices @else Harga Kompetitif @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Offering competitive prices without compromising product quality and service standards. @else Menawarkan harga yang kompetitif tanpa mengorbankan kualitas produk dan standar pelayanan. @endif</p>
                    </div>
                </div>

                {{-- Mission 4 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-green-200 hover:-translate-y-2 [animation-delay:300ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-green-200">
                            <span class="text-2xl font-bold">4</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') On-Time Delivery @else Pengiriman Tepat Waktu @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Ensuring on-time delivery throughout Indonesia with reliable logistics fleet. @else Menjamin pengiriman tepat waktu ke seluruh wilayah Indonesia dengan armada logistik yang handal. @endif</p>
                    </div>
                </div>

                {{-- Mission 5 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-purple-200 hover:-translate-y-2 [animation-delay:400ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-purple-200">
                            <span class="text-2xl font-bold">5</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Long-term Partnership @else Kemitraan Jangka Panjang @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Building mutually beneficial partnership relationships with customers and suppliers. @else Membangun hubungan kemitraan yang saling menguntungkan dengan pelanggan dan supplier. @endif</p>
                    </div>
                </div>

                {{-- Mission 6 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-rose-200 hover:-translate-y-2 [animation-delay:500ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-rose-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-rose-500 to-rose-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-rose-200">
                            <span class="text-2xl font-bold">6</span>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Continuous Innovation @else Inovasi Berkelanjutan @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Continuously innovating in products and services to meet evolving industry needs. @else Terus berinovasi dalam produk dan layanan untuk memenuhi perkembangan kebutuhan industri. @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Core Values Section --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="container">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 bg-accent-100 text-accent-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                    </svg>
                    {{ __('Our Values') }}
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-heading">
                    @if(app()->getLocale() === 'en') Core Values @else Nilai-Nilai Inti @endif
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        The fundamental principles that guide our actions and decisions every day.
                    @else
                        Prinsip-prinsip fundamental yang memandu tindakan dan keputusan kami setiap hari.
                    @endif
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                {{-- Value 1: Integrity --}}
                <div class="group text-center" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')">
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-primary-500 to-primary-700 text-white rounded-2xl flex items-center justify-center mx-auto transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-primary-200">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">1</div>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Integrity @else Integritas @endif</h3>
                    <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Honest, transparent, and responsible in every action and decision we make. @else Jujur, transparan, dan bertanggung jawab dalam setiap tindakan dan keputusan yang kami buat. @endif</p>
                </div>

                {{-- Value 2: Professionalism --}}
                <div class="group text-center [animation-delay:100ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-secondary-500 to-secondary-700 text-white rounded-2xl flex items-center justify-center mx-auto transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-secondary-200">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">2</div>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Professionalism @else Profesionalisme @endif</h3>
                    <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Working with high standards, full dedication, and continuous improvement. @else Bekerja dengan standar tinggi, dedikasi penuh, dan perbaikan berkelanjutan. @endif</p>
                </div>

                {{-- Value 3: Innovation --}}
                <div class="group text-center [animation-delay:200ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-accent-500 to-accent-700 text-white rounded-2xl flex items-center justify-center mx-auto transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-accent-200">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-secondary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">3</div>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Innovation @else Inovasi @endif</h3>
                    <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Continuously developing and adapting to industry changes and customer needs. @else Terus berkembang dan beradaptasi dengan perubahan industri dan kebutuhan pelanggan. @endif</p>
                </div>

                {{-- Value 4: Care --}}
                <div class="group text-center [animation-delay:300ms]" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" >
                    <div class="relative inline-block mb-6">
                        <div class="w-24 h-24 bg-gradient-to-br from-green-500 to-green-700 text-white rounded-2xl flex items-center justify-center mx-auto transform group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-xl shadow-green-200">
                            <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                        </div>
                        <div class="absolute -bottom-2 -right-2 w-8 h-8 bg-primary-500 rounded-lg flex items-center justify-center text-white text-sm font-bold">4</div>
                    </div>
                    <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Care @else Kepedulian @endif</h3>
                    <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Prioritizing customer satisfaction and team welfare in everything we do. @else Mengutamakan kepuasan pelanggan dan kesejahteraan tim dalam setiap hal yang kami lakukan. @endif</p>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-16 md:py-20 bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 left-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading mb-6">
                    @if(app()->getLocale() === 'en')
                        Ready to Partner with Us?
                    @else
                        Siap Bermitra dengan Kami?
                    @endif
                </h2>
                <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Let's discuss how we can help meet your steel needs with quality products and excellent service.
                    @else
                        Mari diskusikan bagaimana kami dapat membantu memenuhi kebutuhan besi baja Anda dengan produk berkualitas dan pelayanan prima.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        @if(app()->getLocale() === 'en') View Products @else Lihat Produk @endif
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

