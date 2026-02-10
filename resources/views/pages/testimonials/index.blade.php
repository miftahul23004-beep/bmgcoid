@extends('layouts.app')

@section('title', __('Client Testimonials') . ' - ' . config('app.name'))
@section('meta_description', __('Read testimonials from our satisfied clients. Trusted by 500+ customers for quality steel products and reliable service across Indonesia.'))

@section('content')
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-900 to-primary-700 text-white py-16 md:py-20">
        <div class="container">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white">{{ __('Home Page') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ __('Testimonials') }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading">{{ __('Client Testimonials') }}</h1>
            <p class="text-lg text-primary-200 mt-4 max-w-2xl">
                {{ __('testimonials_page_description') }}
            </p>
        </div>
    </section>

    {{-- Testimonials Grid --}}
    <section class="py-12 md:py-16">
        <div class="container">
            @if($testimonials->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($testimonials as $testimonial)
                        <div class="group relative bg-white rounded-2xl border border-gray-100 p-6 shadow-sm hover:shadow-xl transition-all duration-300">
                            {{-- Quote icon --}}
                            <div class="absolute top-6 right-6 opacity-10 group-hover:opacity-20 transition-opacity">
                                <svg class="w-12 h-12 text-primary-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/>
                                </svg>
                            </div>
                            
                            <div class="flex items-center gap-4 mb-4">
                                @if($testimonial->author_photo)
                                    <img src="{{ Storage::url($testimonial->author_photo) }}" alt="{{ $testimonial->author_name }}" class="w-16 h-16 rounded-full object-cover ring-2 ring-primary-100" width="64" height="64" loading="lazy">
                                @else
                                    <div class="w-16 h-16 rounded-full bg-gradient-to-br from-primary-500 to-primary-600 text-white flex items-center justify-center font-bold text-2xl ring-2 ring-primary-100">
                                        {{ substr($testimonial->author_name, 0, 1) }}
                                    </div>
                                @endif
                                <div>
                                    <h2 class="font-bold text-gray-900 text-lg">{{ $testimonial->author_name }}</h2>
                                    <p class="text-sm text-gray-500">{{ $testimonial->getTranslation('author_position', app()->getLocale()) }}@if($testimonial->author_company), {{ $testimonial->author_company }}@endif</p>
                                    @if($testimonial->getTranslation('project_name', app()->getLocale()))
                                        <p class="text-xs text-gray-600 mt-0.5">{{ __('Project') }}: {{ $testimonial->getTranslation('project_name', app()->getLocale()) }}</p>
                                    @endif
                                </div>
                            </div>
                            
                            <div class="flex gap-1 mb-4">
                                @for($i = 0; $i < 5; $i++)
                                    <svg class="w-5 h-5 {{ $i < $testimonial->rating ? 'text-yellow-400' : 'text-gray-400' }}" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                    </svg>
                                @endfor
                            </div>
                            
                            <p class="text-gray-600 italic leading-relaxed">"{{ $testimonial->getTranslation('content', app()->getLocale()) }}"</p>

                            @if($testimonial->is_featured)
                                <div class="absolute top-4 left-4">
                                    <span class="inline-flex items-center gap-1 bg-yellow-100 text-yellow-700 text-xs font-medium px-2 py-1 rounded-full">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                        </svg>
                                        {{ __('Featured') }}
                                    </span>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>

                {{-- Pagination --}}
                <div class="mt-12">
                    {{ $testimonials->links() }}
                </div>
            @else
                <div class="text-center py-16">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/>
                    </svg>
                    <h3 class="text-xl font-semibold text-gray-600 mb-2">{{ __('No testimonials yet') }}</h3>
                    <p class="text-gray-500">{{ __('Check back later for client testimonials.') }}</p>
                </div>
            @endif
        </div>
    </section>

    {{-- CTA Section --}}
    <section class="py-12 md:py-16 bg-gradient-to-r from-primary-600 to-primary-700">
        <div class="container text-center">
            <h2 class="text-2xl md:text-3xl font-bold text-white mb-4">{{ __('Ready to Work With Us?') }}</h2>
            <p class="text-primary-100 mb-8 max-w-2xl mx-auto">{{ __('Join our satisfied customers and experience quality steel products and excellent service.') }}</p>
            <div class="flex flex-wrap justify-center gap-4">
                <a href="{{ route('quote') }}" class="inline-flex items-center gap-2 bg-white text-primary-700 px-6 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    {{ __('Request Quote') }}
                </a>
                <a href="{{ route('contact') }}" class="inline-flex items-center gap-2 bg-primary-800 text-white px-6 py-3 rounded-lg font-semibold hover:bg-primary-900 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    {{ __('Contact Us') }}
                </a>
            </div>
        </div>
    </section>
@endsection
