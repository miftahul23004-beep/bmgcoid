@php
    use Illuminate\Support\Facades\Storage;
@endphp

@extends('layouts.app')

@section('title', __('Our Team') . ' - ' . config('app.name'))
@section('meta_description', __('Meet the expert team behind PT. Berkah Mandiri Globalindo'))

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
                    <li class="text-white">@if(app()->getLocale() === 'en') Our Team @else Tim Kami @endif</li>
                </ol>
            </nav>
            
            <div class="max-w-4xl">
                <div class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-sm text-white px-4 py-2 rounded-full text-sm font-medium mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Professional Team @else Tim Profesional @endif
                </div>
                
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold font-heading mb-6 leading-tight">
                    @if(app()->getLocale() === 'en')
                        Meet Our <span class="text-secondary-400">Expert Team</span>
                    @else
                        Kenali <span class="text-secondary-400">Tim Ahli</span> Kami
                    @endif
                </h1>
                
                <p class="text-xl text-primary-100 leading-relaxed max-w-3xl">
                    @if(app()->getLocale() === 'en')
                        Our dedicated professionals bring years of industry experience to serve you better with commitment and excellence.
                    @else
                        Profesional kami yang berdedikasi membawa pengalaman industri bertahun-tahun untuk melayani Anda lebih baik dengan komitmen dan keunggulan.
                    @endif
                </p>
            </div>
        </div>
    </section>

    {{-- Team Stats --}}
    <section class="py-12 md:py-16 bg-white border-b">
        <div class="container">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-primary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-primary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">25+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Team Members @else Anggota Tim @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-secondary-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-secondary-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">14+</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Years Experience @else Tahun Pengalaman @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-accent-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-accent-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">100%</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Certified Staff @else Staf Bersertifikat @endif</div>
                </div>
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-2xl mb-4">
                        <svg class="w-8 h-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="text-4xl font-bold text-gray-900 mb-2">98%</div>
                    <div class="text-gray-600 text-sm">@if(app()->getLocale() === 'en') Client Satisfaction @else Kepuasan Klien @endif</div>
                </div>
            </div>
        </div>
    </section>

    {{-- Leadership Team --}}
    <section class="py-16 md:py-24 bg-gradient-to-b from-gray-50 to-white">
        <div class="container">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 bg-primary-100 text-primary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Leadership Team @else Tim Kepemimpinan @endif
                </div>
                <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-4 font-heading">
                    @if(app()->getLocale() === 'en')
                        Meet Our Leaders
                    @else
                        Kenali Para Pemimpin Kami
                    @endif
                </h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Experienced professionals leading our company towards excellence and innovation.
                    @else
                        Profesional berpengalaman yang memimpin perusahaan kami menuju keunggulan dan inovasi.
                    @endif
                </p>
            </div>

            @php
                $leader1Name = $staticPageImages['team_direktur_utama_name'] ?? 'H. Ahmad Sulaiman';
                $leader1Position = $staticPageImages['team_direktur_utama_position'] ?? (app()->getLocale() === 'en' ? 'CEO / Founder' : 'Direktur Utama');
                $leader2Name = $staticPageImages['team_direktur_operasional_name'] ?? 'Ir. Budi Santoso';
                $leader2Position = $staticPageImages['team_direktur_operasional_position'] ?? (app()->getLocale() === 'en' ? 'Operations Director' : 'Direktur Operasional');
                
                $leaders = [
                    [
                        'name' => $leader1Name,
                        'position' => $leader1Position,
                        'description' => app()->getLocale() === 'en' 
                            ? 'With over 20 years of experience in steel industry, leading the company vision and strategic direction.'
                            : 'Dengan pengalaman lebih dari 20 tahun di industri besi baja, memimpin visi dan arah strategis perusahaan.',
                        'image' => !empty($staticPageImages['team_direktur_utama']) 
                            ? Storage::disk('public')->url($staticPageImages['team_direktur_utama'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($leader1Name) . '&size=400&background=1E40AF&color=fff&bold=true',
                        'color' => 'primary'
                    ],
                    [
                        'name' => $leader2Name,
                        'position' => $leader2Position,
                        'description' => app()->getLocale() === 'en'
                            ? 'Overseeing all operational activities ensuring efficiency and quality in every process.'
                            : 'Mengawasi seluruh aktivitas operasional memastikan efisiensi dan kualitas dalam setiap proses.',
                        'image' => !empty($staticPageImages['team_direktur_operasional']) 
                            ? Storage::disk('public')->url($staticPageImages['team_direktur_operasional'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($leader2Name) . '&size=400&background=DC2626&color=fff&bold=true',
                        'color' => 'secondary'
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-2 gap-8 lg:gap-12 max-w-5xl mx-auto mb-16">
                @foreach ($leaders as $index => $leader)
                <div class="group relative" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="bg-white rounded-3xl shadow-sm hover:shadow-2xl transition-all duration-500 overflow-hidden border border-gray-100 hover:border-{{ $leader['color'] }}-200">
                        {{-- Image Section --}}
                        <div class="relative aspect-[4/3] overflow-hidden bg-gradient-to-br from-{{ $leader['color'] }}-100 to-{{ $leader['color'] }}-50">
                            <img src="{{ $leader['image'] }}" alt="{{ $leader['name'] }}" 
                                class="w-full h-full object-cover transform group-hover:scale-105 transition-transform duration-500" 
                                width="400" height="300" loading="lazy">
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
                            {{-- Position Badge --}}
                            <div class="absolute bottom-4 left-4 right-4">
                                <span class="inline-flex items-center gap-2 bg-white/90 backdrop-blur-sm text-{{ $leader['color'] }}-700 px-4 py-2 rounded-full text-sm font-semibold">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                    </svg>
                                    {{ $leader['position'] }}
                                </span>
                            </div>
                        </div>
                        
                        {{-- Content Section --}}
                        <div class="p-6 lg:p-8">
                            <h3 class="font-heading font-bold text-2xl text-gray-900 mb-3">{{ $leader['name'] }}</h3>
                            <p class="text-gray-600 leading-relaxed">{{ $leader['description'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Management Team --}}
            <div class="text-center mb-12">
                <div class="inline-flex items-center gap-2 bg-secondary-100 text-secondary-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
                    </svg>
                    @if(app()->getLocale() === 'en') Management Team @else Tim Manajemen @endif
                </div>
                <h3 class="text-2xl md:text-3xl font-bold text-gray-900 font-heading">
                    @if(app()->getLocale() === 'en')
                        Department Managers
                    @else
                        Manajer Departemen
                    @endif
                </h3>
            </div>

            @php
                $mgr1Name = $staticPageImages['team_management_1_name'] ?? 'Hendra Wijaya';
                $mgr1Position = $staticPageImages['team_management_1_position'] ?? (app()->getLocale() === 'en' ? 'Sales Manager' : 'Manager Penjualan');
                $mgr2Name = $staticPageImages['team_management_2_name'] ?? 'Dewi Lestari';
                $mgr2Position = $staticPageImages['team_management_2_position'] ?? (app()->getLocale() === 'en' ? 'Finance Manager' : 'Manager Keuangan');
                $mgr3Name = $staticPageImages['team_management_3_name'] ?? 'Rudi Hartono';
                $mgr3Position = $staticPageImages['team_management_3_position'] ?? (app()->getLocale() === 'en' ? 'Warehouse Manager' : 'Manager Gudang');
                $mgr4Name = $staticPageImages['team_management_4_name'] ?? 'Sri Mulyani';
                $mgr4Position = $staticPageImages['team_management_4_position'] ?? (app()->getLocale() === 'en' ? 'HR Manager' : 'Manager HRD');
                
                $managers = [
                    [
                        'name' => $mgr1Name,
                        'position' => $mgr1Position,
                        'image' => !empty($staticPageImages['team_management_1']) 
                            ? Storage::disk('public')->url($staticPageImages['team_management_1'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($mgr1Name) . '&size=400&background=3B82F6&color=fff&bold=true',
                        'color' => 'blue'
                    ],
                    [
                        'name' => $mgr2Name,
                        'position' => $mgr2Position,
                        'image' => !empty($staticPageImages['team_management_2']) 
                            ? Storage::disk('public')->url($staticPageImages['team_management_2'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($mgr2Name) . '&size=400&background=8B5CF6&color=fff&bold=true',
                        'color' => 'purple'
                    ],
                    [
                        'name' => $mgr3Name,
                        'position' => $mgr3Position,
                        'image' => !empty($staticPageImages['team_management_3']) 
                            ? Storage::disk('public')->url($staticPageImages['team_management_3'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($mgr3Name) . '&size=400&background=059669&color=fff&bold=true',
                        'color' => 'green'
                    ],
                    [
                        'name' => $mgr4Name,
                        'position' => $mgr4Position,
                        'image' => !empty($staticPageImages['team_management_4']) 
                            ? Storage::disk('public')->url($staticPageImages['team_management_4'])
                            : 'https://ui-avatars.com/api/?name=' . urlencode($mgr4Name) . '&size=400&background=F59E0B&color=fff&bold=true',
                        'color' => 'amber'
                    ],
                ];
            @endphp

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 lg:gap-8">
                @foreach ($managers as $index => $manager)
                <div class="group" x-data x-intersect.once="$el.classList.add('animate-fade-in-up')" style="animation-delay: {{ $index * 100 }}ms">
                    <div class="bg-white rounded-2xl shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:border-{{ $manager['color'] }}-200 hover:-translate-y-2">
                        {{-- Image --}}
                        <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-{{ $manager['color'] }}-100 to-{{ $manager['color'] }}-50">
                            <img src="{{ $manager['image'] }}" alt="{{ $manager['name'] }}" 
                                class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-500" 
                                width="400" height="400" loading="lazy">
                        </div>
                        
                        {{-- Content --}}
                        <div class="p-5 text-center">
                            <h4 class="font-heading font-bold text-lg text-gray-900 mb-1">{{ $manager['name'] }}</h4>
                            <p class="text-{{ $manager['color'] }}-600 font-medium text-sm">{{ $manager['position'] }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Why Our Team Section --}}
    <section class="py-16 md:py-24 bg-white">
        <div class="container">
            <div class="grid lg:grid-cols-2 gap-12 lg:gap-16 items-center">
                {{-- Content Side --}}
                <div>
                    <div class="inline-flex items-center gap-2 bg-accent-100 text-accent-700 px-4 py-2 rounded-full text-sm font-semibold mb-6">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Why Our Team @else Mengapa Tim Kami @endif
                    </div>
                    
                    <h2 class="text-3xl md:text-4xl lg:text-5xl font-bold text-gray-900 mb-6 font-heading leading-tight">
                        @if(app()->getLocale() === 'en')
                            What Makes Our Team <span class="text-primary-600">Stand Out</span>
                        @else
                            Apa yang Membuat Tim Kami <span class="text-primary-600">Unggul</span>
                        @endif
                    </h2>
                    
                    <p class="text-lg text-gray-600 mb-8 leading-relaxed">
                        @if(app()->getLocale() === 'en')
                            Our team combines deep industry knowledge with a genuine passion for customer success. Every member is committed to delivering exceptional service.
                        @else
                            Tim kami menggabungkan pengetahuan industri yang mendalam dengan passion tulus untuk kesuksesan pelanggan. Setiap anggota berkomitmen memberikan layanan luar biasa.
                        @endif
                    </p>
                    
                    <div class="space-y-4">
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-primary-100 text-primary-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Industry Expertise @else Keahlian Industri @endif</h3>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') Deep knowledge of steel products, standards, and applications @else Pengetahuan mendalam tentang produk besi baja, standar, dan aplikasinya @endif</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-secondary-100 text-secondary-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Fast Response @else Respon Cepat @endif</h3>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') Quick turnaround on inquiries and orders @else Penanganan cepat untuk pertanyaan dan pesanan @endif</p>
                            </div>
                        </div>
                        
                        <div class="flex items-start gap-4">
                            <div class="w-12 h-12 bg-green-100 text-green-600 rounded-xl flex items-center justify-center shrink-0">
                                <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-lg text-gray-900 mb-1">@if(app()->getLocale() === 'en') Customer Focus @else Fokus Pelanggan @endif</h3>
                                <p class="text-gray-600">@if(app()->getLocale() === 'en') Dedicated to understanding and meeting your needs @else Berdedikasi memahami dan memenuhi kebutuhan Anda @endif</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Image Side --}}
                <div class="relative">
                    <div class="aspect-w-4 aspect-h-3 rounded-2xl overflow-hidden shadow-2xl">
                        @php
                            $teamWorkImage = !empty($staticPageImages['team_work_image']) 
                                ? Storage::disk('public')->url($staticPageImages['team_work_image']) 
                                : asset('storage/about/team.webp');
                        @endphp
                        <img src="{{ $teamWorkImage }}" alt="Team Work" class="w-full h-full object-cover" width="800" height="600" onerror="this.src='https://placehold.co/800x600/1E40AF/ffffff?text=Team+Work'">
                    </div>
                    {{-- Floating Stats --}}
                    <div class="absolute -bottom-8 -left-8 bg-white p-6 rounded-2xl shadow-2xl">
                        <div class="flex items-center gap-4">
                            <div class="w-14 h-14 bg-gradient-to-br from-primary-500 to-primary-600 rounded-xl flex items-center justify-center text-white">
                                <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"/>
                                </svg>
                            </div>
                            <div>
                                <div class="text-3xl font-bold text-gray-900">500+</div>
                                <div class="text-sm text-gray-600">@if(app()->getLocale() === 'en') Happy Clients @else Klien Puas @endif</div>
                            </div>
                        </div>
                    </div>
                    {{-- Decorative Element --}}
                    <div class="absolute -top-4 -right-4 w-24 h-24 bg-secondary-100 rounded-2xl -z-10"></div>
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
                        Ready to Work with Our Team?
                    @else
                        Siap Bekerja dengan Tim Kami?
                    @endif
                </h2>
                <p class="text-xl text-primary-100 mb-10 max-w-2xl mx-auto">
                    @if(app()->getLocale() === 'en')
                        Get in touch with our professional team today. We're ready to help with your steel needs.
                    @else
                        Hubungi tim profesional kami hari ini. Kami siap membantu kebutuhan besi baja Anda.
                    @endif
                </p>
                
                <div class="flex flex-wrap justify-center gap-4">
                    <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-white text-primary-900 px-8 py-4 rounded-xl font-semibold hover:bg-primary-50 transition-all hover:shadow-xl hover:scale-105">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        @if(app()->getLocale() === 'en') Contact Us @else Hubungi Kami @endif
                    </a>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', config('social.whatsapp', '6281234567890')) }}" target="_blank" class="inline-flex items-center gap-2 bg-green-700 text-white px-8 py-4 rounded-xl font-semibold hover:bg-green-800 transition-all hover:shadow-xl hover:scale-105">
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

