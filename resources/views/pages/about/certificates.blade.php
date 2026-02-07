@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', __('Certificates & Legality') . ' - ' . config('app.name'))
@section('meta_description', __('Certificates and legality documents of PT. Berkah Mandiri Globalindo'))

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
                    <li><a href="{{ route('about.company') }}" class="text-primary-200 hover:text-white transition-colors">@if(app()->getLocale() === 'en') About @else Tentang @endif</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">@if(app()->getLocale() === 'en') Certificates & Legality @else Sertifikat & Legalitas @endif</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Verified & Trusted @else Terverifikasi & Terpercaya @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Certificates & <span class="text-secondary-400">Legality</span>
                    @else
                        Sertifikat & <span class="text-secondary-400">Legalitas</span>
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Complete documentation ensuring quality, trust, and compliance with national and international standards.
                    @else
                        Dokumentasi lengkap yang menjamin kualitas, kepercayaan, dan kepatuhan terhadap standar nasional dan internasional.
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- Trust Indicators --}}
    <section class="py-12 md:py-16 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">6+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Certifications @else Sertifikasi @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">100%</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Compliant @else Patuh Regulasi @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">SNI</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Certified Products @else Produk Bersertifikat @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">ISO</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') International Standard @else Standar Internasional @endif</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Main Certificates Section --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Our Certifications @else Sertifikasi Kami @endif
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-heading">
                    @if(app()->getLocale() === 'en')
                        Company Legality & Certifications
                    @else
                        Legalitas & Sertifikasi Perusahaan
                    @endif
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Complete documentation that guarantees quality, trust, and compliance with regulations.
                    @else
                        Dokumentasi lengkap yang menjamin kualitas, kepercayaan, dan kepatuhan regulasi.
                    @endif
                </p>
            </div>

            @php
                $certificates = [
                    [
                        'name' => app()->getLocale() === 'en' ? 'SNI Certificate' : 'Sertifikat SNI',
                        'desc' => app()->getLocale() === 'en' ? 'Indonesian National Standard certification for all steel products ensuring quality and safety compliance.' : 'Sertifikasi Standar Nasional Indonesia untuk semua produk besi baja yang menjamin kepatuhan kualitas dan keamanan.',
                        'icon' => 'shield-check',
                        'color' => 'primary',
                        'badge' => app()->getLocale() === 'en' ? 'Quality Assured' : 'Kualitas Terjamin'
                    ],
                    [
                        'name' => 'ISO 9001:2015',
                        'desc' => app()->getLocale() === 'en' ? 'International Quality Management System certification demonstrating our commitment to consistent quality.' : 'Sertifikasi Sistem Manajemen Mutu Internasional yang menunjukkan komitmen kami terhadap kualitas yang konsisten.',
                        'icon' => 'globe',
                        'color' => 'secondary',
                        'badge' => app()->getLocale() === 'en' ? 'International' : 'Internasional'
                    ],
                    [
                        'name' => 'SIUP & NIB',
                        'desc' => app()->getLocale() === 'en' ? 'Trading Business License & Business Identification Number for legal trading operations in Indonesia.' : 'Surat Izin Usaha Perdagangan & Nomor Induk Berusaha untuk operasi perdagangan legal di Indonesia.',
                        'icon' => 'document-text',
                        'color' => 'accent',
                        'badge' => app()->getLocale() === 'en' ? 'Legal' : 'Legal'
                    ],
                    [
                        'name' => app()->getLocale() === 'en' ? 'Authorized Distributor' : 'Distributor Resmi',
                        'desc' => app()->getLocale() === 'en' ? 'Official distributor certificates from leading steel manufacturers including Krakatau Steel and others.' : 'Sertifikat distributor resmi dari pabrik besi baja terkemuka termasuk Krakatau Steel dan lainnya.',
                        'icon' => 'badge-check',
                        'color' => 'green',
                        'badge' => app()->getLocale() === 'en' ? 'Authorized' : 'Resmi'
                    ],
                    [
                        'name' => app()->getLocale() === 'en' ? 'Company Tax Number' : 'NPWP Perusahaan',
                        'desc' => app()->getLocale() === 'en' ? 'Valid Tax Identification Number ensuring all transactions are legally documented and compliant.' : 'Nomor Pokok Wajib Pajak yang valid memastikan semua transaksi terdokumentasi dan patuh hukum.',
                        'icon' => 'receipt-tax',
                        'color' => 'purple',
                        'badge' => app()->getLocale() === 'en' ? 'Tax Compliant' : 'Patuh Pajak'
                    ],
                    [
                        'name' => 'TDP',
                        'desc' => app()->getLocale() === 'en' ? 'Valid Company Registration Certificate confirming our legal status as a registered business entity.' : 'Tanda Daftar Perusahaan yang masih berlaku mengkonfirmasi status legal kami sebagai badan usaha terdaftar.',
                        'icon' => 'office-building',
                        'color' => 'rose',
                        'badge' => app()->getLocale() === 'en' ? 'Registered' : 'Terdaftar'
                    ],
                ];
                
                $iconPaths = [
                    'shield-check' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z',
                    'globe' => 'M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9',
                    'document-text' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z',
                    'badge-check' => 'M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z',
                    'receipt-tax' => 'M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2zM10 8.5a.5.5 0 11-1 0 .5.5 0 011 0zm5 5a.5.5 0 11-1 0 .5.5 0 011 0z',
                    'office-building' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4',
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($certificates as $index => $cert)
                <div class="group relative" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="bg-white rounded-2xl p-8 shadow-sm hover:shadow-2xl transition-all duration-300 border border-gray-100 hover:border-{{ $cert['color'] }}-200 hover:-translate-y-2 h-full">
                        {{-- Corner decoration --}}
                        <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-{{ $cert['color'] }}-100 to-transparent rounded-bl-full opacity-50 group-hover:opacity-100 transition-opacity"></div>
                        
                        <div class="relative">
                            {{-- Badge --}}
                            <div class="absolute -top-2 -right-2">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-{{ $cert['color'] }}-100 text-{{ $cert['color'] }}-700">
                                    {{ $cert['badge'] }}
                                </span>
                            </div>
                            
                            {{-- Icon --}}
                            <div class="w-16 h-16 bg-gradient-to-br from-{{ $cert['color'] }}-500 to-{{ $cert['color'] }}-600 text-white rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform shadow-lg shadow-{{ $cert['color'] }}-200">
                                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPaths[$cert['icon']] }}"/>
                                </svg>
                            </div>
                            
                            {{-- Content --}}
                            <h3 class="font-heading font-bold text-xl text-gray-900 mb-3">{{ $cert['name'] }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $cert['desc'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why It Matters Section --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="container">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Image Side --}}
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        @php
                            $certImage = !empty($staticPageImages['certificates_quality_image']) 
                                ? Storage::disk('public')->url($staticPageImages['certificates_quality_image']) 
                                : asset('storage/about/certificates.webp');
                        @endphp
                        <img src="{{ $certImage }}" alt="Certificates" class="w-full h-full object-cover" width="800" height="600" onerror="this.src='https://placehold.co/800x600/1E40AF/ffffff?text=Certified+Quality'">
                    </div>
                    {{-- Floating Badge --}}
                    <div class="absolute -bottom-6 -right-6 bg-gradient-to-br from-green-500 to-green-600 text-white p-6 rounded-2xl shadow-2xl">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-2xl font-bold">100%</div>
                                <div class="text-sm text-green-100">@if(app()->getLocale() === 'en') Verified @else Terverifikasi @endif</div>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative Element --}}
                    <div class="absolute -top-4 -left-4 w-24 h-24 bg-primary-100 rounded-2xl -z-10"></div>
                </div>
                
                {{-- Content Side --}}
                <div>
                    <div class="inline-flex items-center gap-2 bg-accent-100 text-accent-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Why It Matters @else Mengapa Penting @endif
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-heading leading-tight">
                        @if(app()->getLocale() === 'en')
                            Your <span class="text-primary-600">Assurance</span> of Quality & Trust
                        @else
                            <span class="text-primary-600">Jaminan</span> Kualitas & Kepercayaan Anda
                        @endif
                    </h2>
                    
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        @if(app()->getLocale() === 'en')
                            Our certifications and legal documents are not just formalities – they represent our commitment to delivering quality products and maintaining the highest standards of business ethics.
                        @else
                            Sertifikasi dan dokumen legal kami bukan hanya formalitas – melainkan representasi komitmen kami untuk memberikan produk berkualitas dan menjaga standar tertinggi etika bisnis.
                        @endif
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Quality Guarantee @else Jaminan Kualitas @endif</h4>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') Every product meets SNI and international quality standards @else Setiap produk memenuhi standar kualitas SNI dan internasional @endif</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Legal Transactions @else Transaksi Legal @endif</h4>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') All transactions are documented and tax-compliant @else Semua transaksi terdokumentasi dan patuh pajak @endif</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <div>
                                <h4 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Tender Ready @else Siap Tender @endif</h4>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') Complete documentation for government and private tenders @else Dokumentasi lengkap untuk tender pemerintah dan swasta @endif</p>
                            </div>
                        </div>
                    </div>
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
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Documentation Request @else Permintaan Dokumentasi @endif
                </div>
                
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading mb-6">
                    @if(app()->getLocale() === 'en')
                        Need Certificate Copies?
                    @else
                        Butuh Salinan Sertifikat?
                    @endif
                </h2>
                <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        We provide copies of certificates and company legal documents for tender or business collaboration needs.
                    @else
                        Kami menyediakan salinan sertifikat dan dokumen legalitas perusahaan untuk kebutuhan tender atau kerjasama bisnis.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Request Documents @else Minta Dokumen @endif
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('social.whatsapp', '6281234567890')) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-500 text-white px-8 py-4 rounded-xl font-semibold hover:bg-green-600 transition-all hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        WhatsApp
                    </a>
                </div>
            </div>
        </div>
    </section>
@endsection

