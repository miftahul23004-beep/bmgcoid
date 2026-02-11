@extends('layouts.app')

@php
    use Illuminate\Support\Facades\Storage;
@endphp

@section('title', __('About Us') . ' - ' . config('app.name'))

@section('meta_description', __('Learn about BMG - Your trusted partner for quality steel products since 2011. Distributor & supplier of steel for industry, manufacturing, and construction.'))

@php
    $canonicalUrl = route('about.company');
@endphp

@push('meta')
    <meta name="keywords" content="about BMG, steel distributor, steel supplier, trusted steel partner, construction materials">
@endpush

@section('content')
    {{-- Hero Section with Overlay --}}
    <section class="relative bg-gradient-to-br from-primary-900 via-primary-800 to-primary-700 text-white py-16 md:py-20 overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse"></div>
            <div class="absolute bottom-0 left-0 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl animate-pulse [animation-delay:1s]" ></div>
        </div>
        
        <div class="container relative z-10">
            <nav class="text-sm mb-6" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white transition-colors">{{ __('Home Page') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ __('About Us') }}</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Trusted Since 2011 @else Terpercaya Sejak 2011 @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        About PT. Berkah Mandiri Globalindo
                    @else
                        Tentang PT. Berkah Mandiri Globalindo
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed">
                    @if(app()->getLocale() === 'en')
                        PT. Berkah Mandiri Globalindo has been a leading steel distributor serving construction and industrial needs across Indonesia with commitment to quality, service, and competitive pricing.
                    @else
                        PT. Berkah Mandiri Globalindo merupakan distributor besi baja terkemuka yang melayani kebutuhan konstruksi dan industri di seluruh Indonesia dengan komitmen pada kualitas, pelayanan, dan harga kompetitif.
                    @endif
                </p>
                
                <div class="flex flex-wrap gap-4 mt-8">
                    <a href="{{ route('products.index') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                        @if(app()->getLocale() === 'en') View Products @else Lihat Produk @endif
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white/30 px-8 py-4 rounded-xl font-semibold hover:bg-white/20 transition-all">
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                </div>
            </div>
        </div>
    </section>

    {{-- Stats Section --}}
    <section class="py-12 md:py-16 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">500+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Satisfied Clients @else Klien Puas @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">1000+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Projects Completed @else Proyek Selesai @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">300+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Product Types @else Jenis Produk @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">34</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Provinces Served @else Provinsi Terjangkau @endif</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Company Story Section --}}
    <section class="py-16 md:py-24 bg-gray-50 overflow-hidden">
        <div class="container">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                <div class="relative order-2 lg:order-1">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        @php
                            $storyImage = !empty($staticPageImages['about_story_image']) 
                                ? Storage::disk('public')->url($staticPageImages['about_story_image']) 
                                : 'https://placehold.co/800x600/1E40AF/ffffff?text=PT.+Berkah+Mandiri+Globalindo';
                        @endphp
                        <img src="{{ $storyImage }}" alt="PT. Berkah Mandiri Globalindo" class="w-full h-full object-cover" width="800" height="600" loading="lazy" decoding="async" onerror="this.src='https://placehold.co/800x600/1E40AF/ffffff?text=PT.+Berkah+Mandiri+Globalindo'">
                    </div>
                    <div class="absolute -bottom-8 -right-8 bg-secondary-600 text-white p-8 rounded-2xl shadow-2xl">
                        <div class="text-5xl font-bold">14+</div>
                        <div class="text-sm mt-2 font-medium">@if(app()->getLocale() === 'en') Years of Excellence @else Tahun Berpengalaman @endif</div>
                    </div>
                </div>
                
                <div class="order-1 lg:order-2">
                    <div class="inline-block bg-primary-100 text-primary-600 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        @if(app()->getLocale() === 'en') Our Story @else Cerita Kami @endif
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6 font-heading">
                        @if(app()->getLocale() === 'en')
                            Building Indonesia with Quality Steel
                        @else
                            Membangun Indonesia dengan Besi Baja Berkualitas
                        @endif
                    </h2>
                    
                    <div class="space-y-4 text-gray-600 leading-relaxed">
                        @if(app()->getLocale() === 'en')
                            <p class="text-lg">Since <strong class="text-gray-900">2011</strong>, PT. Berkah Mandiri Globalindo has been at the forefront of Indonesia's steel distribution industry, serving thousands of construction projects across the archipelago.</p>
                            <p>We specialize in providing high-quality steel products including rebar, hollow sections, pipes, and various profiles, all certified to meet SNI standards and international quality benchmarks.</p>
                            <p>Our commitment goes beyond just supplying materials – we partner with our clients to understand their needs and provide expert consultation, ensuring they get the right products at the right price with exceptional service.</p>
                        @else
                            <p class="text-lg">Sejak <strong class="text-gray-900">2011</strong>, PT. Berkah Mandiri Globalindo telah menjadi garda terdepan industri distribusi besi baja Indonesia, melayani ribuan proyek konstruksi di seluruh nusantara.</p>
                            <p>Kami mengkhususkan diri dalam menyediakan produk besi baja berkualitas tinggi termasuk besi beton, hollow, pipa, dan berbagai profil, semuanya bersertifikat SNI dan memenuhi standar kualitas internasional.</p>
                            <p>Komitmen kami melampaui sekadar memasok material – kami bermitra dengan klien untuk memahami kebutuhan mereka dan memberikan konsultasi ahli, memastikan mereka mendapatkan produk yang tepat dengan harga yang tepat dan layanan yang luar biasa.</p>
                        @endif
                    </div>
                    
                    <div class="flex items-center gap-4 mt-8">
                        <div class="flex -space-x-4">
                            <img src="https://ui-avatars.com/api/?name=Client+1&background=1E40AF&color=fff" alt="Client" class="w-12 h-12 rounded-full border-4 border-white" width="48" height="48" loading="lazy" decoding="async">
                            <img src="https://ui-avatars.com/api/?name=Client+2&background=DC2626&color=fff" alt="Client" class="w-12 h-12 rounded-full border-4 border-white" width="48" height="48" loading="lazy" decoding="async">
                            <img src="https://ui-avatars.com/api/?name=Client+3&background=059669&color=fff" alt="Client" class="w-12 h-12 rounded-full border-4 border-white" width="48" height="48" loading="lazy" decoding="async">
                            <img src="https://ui-avatars.com/api/?name=Client+4&background=EA580C&color=fff" alt="Client" class="w-12 h-12 rounded-full border-4 border-white" width="48" height="48" loading="lazy" decoding="async">
                        </div>
                        <div>
                            <div class="font-semibold text-gray-900">500+ @if(app()->getLocale() === 'en') Happy Clients @else Klien Bahagia @endif</div>
                            <div class="text-sm text-gray-500">@if(app()->getLocale() === 'en') Trust us for their steel needs @else Mempercayai kami untuk kebutuhan besi baja @endif</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Why Choose Us --}}
    <section class="py-16 md:py-24">
        <div class="container">
            <div class="text-center mb-16">
                <div class="inline-block bg-primary-100 text-primary-600 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    @if(app()->getLocale() === 'en') Why Choose Us @else Mengapa Memilih Kami @endif
                </div>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-4 font-heading">{{ __('Why Choose Us') }}</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Six compelling reasons that make us the preferred choice for businesses across Indonesia.
                    @else
                        Enam alasan kuat yang membuat kami menjadi pilihan utama untuk bisnis di seluruh Indonesia.
                    @endif
                </p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                {{-- Advantage 1 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-primary-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Guaranteed Quality @else Kualitas Terjamin @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') All products are SNI certified and undergo rigorous quality control to ensure compliance with national and international standards. @else Semua produk bersertifikasi SNI dan melalui quality control ketat untuk memastikan kepatuhan terhadap standar nasional dan internasional. @endif</p>
                    </div>
                </div>

                {{-- Advantage 2 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-secondary-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-secondary-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Fast Delivery @else Pengiriman Cepat @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Own logistics fleet ensures fast, reliable, and on-time delivery to construction sites across Indonesia. @else Armada logistik sendiri memastikan pengiriman cepat, andal, dan tepat waktu ke lokasi konstruksi di seluruh Indonesia. @endif</p>
                    </div>
                </div>

                {{-- Advantage 3 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-accent-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-accent-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-accent-500 to-accent-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Competitive Prices @else Harga Kompetitif @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Direct sourcing from authorized manufacturers means better prices for you without compromising on quality. @else Sumber langsung dari produsen resmi berarti harga yang lebih baik untuk Anda tanpa mengorbankan kualitas. @endif</p>
                    </div>
                </div>

                {{-- Advantage 4 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-green-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-green-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-green-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Professional Team @else Tim Profesional @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Experienced sales and customer service team ready to provide expert consultation and responsive support. @else Tim sales dan customer service berpengalaman siap memberikan konsultasi ahli dan dukungan responsif. @endif</p>
                    </div>
                </div>

                {{-- Advantage 5 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-purple-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-purple-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-purple-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') Complete Documentation @else Dokumen Lengkap @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Material certificates, test reports, and all supporting documents provided for complete project compliance. @else Sertifikat material, laporan uji, dan semua dokumen pendukung disediakan untuk kepatuhan proyek yang lengkap. @endif</p>
                    </div>
                </div>

                {{-- Advantage 6 --}}
                <div class="group relative bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-orange-200 hover:-translate-y-2">
                    <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-orange-100 to-transparent rounded-bl-full opacity-50"></div>
                    <div class="relative">
                        <div class="w-16 h-16 bg-gradient-to-br from-orange-500 to-orange-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h3 class="font-bold text-xl mb-3 text-gray-900">@if(app()->getLocale() === 'en') 24/7 Support @else Support 24/7 @endif</h3>
                        <p class="text-gray-600 leading-relaxed">@if(app()->getLocale() === 'en') Our customer service team is always ready to assist you anytime, anywhere for your convenience. @else Tim customer service kami selalu siap membantu Anda kapanpun, dimanapun untuk kemudahan Anda. @endif</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-20 md:py-32 bg-gradient-to-br from-primary-900 via-primary-800 to-secondary-900 text-white relative overflow-hidden">
        {{-- Decorative elements --}}
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-1/2 left-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
            <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-white rounded-full mix-blend-overlay filter blur-3xl"></div>
        </div>
        
        <div class="container relative z-10">
            <div class="max-w-4xl mx-auto text-center">
                <div class="inline-block bg-white/10 backdrop-blur-sm px-6 py-3 rounded-full text-sm font-semibold mb-8">
                    @if(app()->getLocale() === 'en') Let's Work Together @else Mari Bekerja Sama @endif
                </div>
                
                <h2 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Ready to Build Your Next Project?
                    @else
                        Siap Membangun Proyek Anda Berikutnya?
                    @endif
                </h2>
                
                <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto leading-relaxed">
                    @if(app()->getLocale() === 'en')
                        Get in touch with our team for expert consultation and competitive pricing on premium steel products.
                    @else
                        Hubungi tim kami untuk konsultasi ahli dan harga kompetitif untuk produk besi baja premium.
                    @endif
                </p>
                
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-10 py-5 rounded-xl font-bold text-lg hover:bg-primary-50 transition-all hover:shadow-2xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        {{ __('Contact Us') }}
                    </a>
                    <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 bg-secondary-600 text-white px-10 py-5 rounded-xl font-bold text-lg hover:bg-secondary-700 transition-all hover:shadow-2xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        {{ __('Get Quote') }}
                    </a>
                </div>
                
                <div class="mt-12 flex items-center justify-center gap-8 text-sm text-primary-200">
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Fast Response @else Respon Cepat @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Free Consultation @else Konsultasi Gratis @endif
                    </div>
                    <div class="flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Best Prices @else Harga Terbaik @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

