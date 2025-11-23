@extends('front.layout')

@section('title', 'Blog - Gift Store')

@section('content')
<!-- Hero Section -->
<div class="bg-gradient-to-r from-indigo-600 to-purple-600 text-white py-16">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <h1 class="text-4xl md:text-5xl font-bold mb-4">Blog</h1>
        <p class="text-xl text-indigo-100">Gift ideas, tips, and inspiration</p>
    </div>
</div>

<!-- Search Section -->
<div class="bg-white border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <form action="{{ route('blog.index') }}" method="GET" class="max-w-2xl mx-auto">
            <div class="relative">
                <input 
                    type="text" 
                    name="search" 
                    value="{{ request('search') }}"
                    placeholder="Search articles..." 
                    class="w-full px-6 py-3 pr-12 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                >
                <button type="submit" class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 hover:text-indigo-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                </button>
            </div>
        </form>
        
        @if(request('search'))
            <div class="text-center mt-4">
                <span class="text-sm text-gray-600">{{ $blogs->count() }} {{ Str::plural('article', $blogs->count()) }} found for "{{ request('search') }}"</span>
                <a href="{{ route('blog.index') }}" class="ml-4 text-sm text-indigo-600 hover:text-indigo-700">Clear search</a>
            </div>
        @endif
    </div>
</div>

<!-- Blog Grid -->
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    @if($blogs->count() > 0)
        <div id="initial-blogs" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @foreach($blogs as $blog)
                <article class="group">
                    <a href="{{ route('blog.show', $blog->slug) }}" class="block bg-white rounded-lg shadow-sm hover:shadow-lg transition-all duration-300 overflow-hidden">
                        <!-- Blog Image -->
                        <div class="aspect-video overflow-hidden bg-gray-100">
                            @if($blog->image_url)
                                <img 
                                    src="{{ $blog->image_url }}" 
                                    alt="{{ $blog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
                                    </svg>
                                </div>
                            @endif
                        </div>

                        <!-- Blog Content -->
                        <div class="p-6">
                            <!-- Meta Info -->
                            <div class="flex items-center text-sm text-gray-500 mb-3">
                                @if($blog->author)
                                    <span class="flex items-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ $blog->author }}
                                    </span>
                                    <span class="mx-2">•</span>
                                @endif
                                <span class="flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $blog->created_at->format('M d, Y') }}
                                </span>
                            </div>

                            <!-- Title -->
                            <h2 class="text-xl font-bold text-gray-900 mb-3 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                {{ $blog->title }}
                            </h2>
                            
                            <!-- Excerpt -->
                            @if($blog->short_description)
                                <p class="text-gray-600 mb-4 line-clamp-3">
                                    {{ $blog->short_description }}
                                </p>
                            @endif

                            <!-- Read More -->
                            <span class="inline-flex items-center text-indigo-600 font-medium group-hover:translate-x-1 transition-transform">
                                Read More
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
            <blog-infinite-scroll 
                :initial-count="{{ $blogs->count() }}"
                :search-term="{{ json_encode($searchTerm) }}"
            ></blog-infinite-scroll>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-16">
            <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z" />
            </svg>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No articles found</h3>
            <p class="text-gray-600 mb-6">
                @if(request('search'))
                    Try adjusting your search terms
                @else
                    Check back soon for new articles
                @endif
            </p>
            @if(request('search'))
                <a href="{{ route('blog.index') }}" class="inline-block px-6 py-3 bg-indigo-600 text-white font-medium rounded-lg hover:bg-indigo-700 transition-colors">
                    View All Articles
                </a>
            @endif
        </div>
    @endif
</div>
@endsection
