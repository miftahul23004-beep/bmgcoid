@extends('layouts.app')

@section('title', $tag->name . ' - ' . __('Articles') . ' - ' . config('app.name'))

@section('content')
    {{-- Page Header --}}
    <section class="bg-gradient-to-r from-primary-900 to-primary-700 text-white py-16 md:py-20">
        <div class="container">
            <nav class="text-sm mb-4" aria-label="Breadcrumb">
                <ol class="flex items-center gap-2">
                    <li><a href="{{ route('home') }}" class="text-primary-200 hover:text-white">{{ __('Home') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li><a href="{{ route('articles.index') }}" class="text-primary-200 hover:text-white">{{ __('Articles') }}</a></li>
                    <li><span class="text-primary-400">/</span></li>
                    <li class="text-white">{{ $tag->name }}</li>
                </ol>
            </nav>
            <h1 class="text-3xl md:text-4xl lg:text-5xl font-bold font-heading">{{ $tag->name }}</h1>
            <p class="text-lg text-primary-200 mt-4">Artikel dengan tag "{{ $tag->name }}"</p>
        </div>
    </section>

    <section class="py-12 md:py-16">
        <div class="container">
            <div class="flex flex-col lg:flex-row gap-8">
                {{-- Sidebar --}}
                <aside class="w-full lg:w-64 flex-shrink-0">
                    <div class="card sticky top-24">
                        <h3 class="font-semibold text-lg mb-4">{{ __('Tags') }}</h3>
                        <div class="flex flex-wrap gap-2">
                            @foreach($tags as $t)
                                <a href="{{ route('articles.tag', $t->slug) }}" class="badge {{ $t->slug === $tag->slug ? 'badge-primary' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                                    {{ $t->name }} ({{ $t->articles_count }})
                                </a>
                            @endforeach
                        </div>
                        <a href="{{ route('articles.index') }}" class="btn btn-outline w-full mt-4">{{ __('View All') }} {{ __('Articles') }}</a>
                    </div>
                </aside>

                {{-- Main Content --}}
                <div class="flex-1">
                    @if($articles->count() > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($articles as $article)
                                @include('components.article-card', ['article' => $article])
                            @endforeach
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-12">
                            {{ $articles->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <h3 class="text-lg font-semibold text-gray-600 mb-2">Belum Ada Artikel</h3>
                            <p class="text-gray-500">Tag ini belum memiliki artikel.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>
@endsection

