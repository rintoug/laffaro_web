@extends('front.layout')

@section('title', 'Gift Articles - Gift Store')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-purple-600 to-pink-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Gift Articles</h1>
        <p class="text-xl text-purple-100">Curated gift guides for every occasion</p>
    </div>
</div>

<!-- Search Section -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form action="{{ route('articles.index') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search gift guides..." 
                    class="w-full px-6 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent"
                >
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-purple-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        
        @if(request('search'))
            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">{{ $articles->count() }} {{ Str::plural('guide', $articles->count()) }} found for "{{ request('search') }}"</span>
                <a href="{{ route('articles.index') }}" class="ml-4 text-sm text-purple-600 hover:text-purple-700">Clear search</a>
            </div>
        @endif
    </div>
</div>

<!-- Articles Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($articles->count() > 0)
        <div id="initial-articles" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach($articles as $article)
                <article class="group">
                    <a href="{{ route('articles.show', $article->slug) }}" class="block bg-white rounded-lg shadow-sm hover:shadow-xl transition-all duration-300 overflow-hidden h-full">
                        <!-- Article Image -->
                        <div class="aspect-square overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100">
                            @if($article->image_url)
                                <img 
                                    src="{{ $article->image_url }}" 
                                    alt="{{ $article->title }}"
                                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-20 h-20 text-purple-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Article Content -->
                        <div class="p-5">
                            <h2 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-purple-600 transition-colors">
                                {{ $article->title }}
                            </h2>
                            
                            <!-- View Guide Button -->
                            <span class="inline-flex items-center text-purple-600 font-medium text-sm group-hover:translate-x-1 transition-transform mt-2">
                                View Guide
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </span>
                        </div>
                    </a>
                </article>
            @endforeach
        </div>

        <!-- Infinite Scroll Container -->
        <div id="infinite-scroll-container">
            <article-infinite-scroll 
                :initial-count="{{ $articles->count() }}"
                :search-term="{{ json_encode($searchTerm) }}"
            ></article-infinite-scroll>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No gift guides found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search'))
                    Try adjusting your search terms
                @else
                    Check back soon for new gift guides
                @endif
            </p>
            @if(request('search'))
                <a href="{{ route('articles.index') }}" class="inline-block px-6 py-3 bg-purple-600 text-white font-medium rounded-lg hover:bg-purple-700 transition-colors">
                    View All Guides
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
