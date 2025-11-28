@extends('front.layout')

@section('title', isset($category) ? $category->meta_title ?: $category->name . ' - Laffaro.com' : (isset($giftType) ? $giftType->meta_title ?: $giftType->name . ' - Laffaro.com' : (isset($searchTerm) && $searchTerm ? 'Search Results for "' . $searchTerm . '" - Laffaro.com' : 'Laffaro | The Best Gag Gifts Online | Funny, Weird')))

@if(isset($category))
    @section('meta_description', $category->meta_description ?: 'Browse our collection of ' . $category->name)
    @section('meta_keywords', $category->meta_keywords ?: $category->name . ', gifts')
@elseif(isset($giftType))
    @section('meta_description', $giftType->meta_description ?: 'Find the perfect ' . $giftType->name)
    @section('meta_keywords', $giftType->meta_keywords ?: $giftType->name . ', gift ideas')
@elseif(isset($searchTerm) && $searchTerm)
    @section('meta_description', 'Search results for "' . $searchTerm . '" in our gift collection')
    @section('meta_keywords', $searchTerm . ', gifts, search')
@endif

@section('content')
<!-- Hero Section -->
<div class="relative bg-gradient-to-br from-indigo-600 via-purple-600 to-pink-600 text-white py-12 md:py-16 overflow-hidden">
    <!-- Background elements -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 left-10 w-72 h-72 bg-white rounded-full filter blur-3xl"></div>
        <div class="absolute bottom-10 right-10 w-96 h-96 bg-pink-300 rounded-full filter blur-3xl"></div>
    </div>
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="text-center">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tight">
                @if(isset($category))
                    {{ $category->name }}
                @elseif(isset($giftType))
                    {{ $giftType->name }}
                @elseif(isset($searchTerm) && $searchTerm)
                    Search Results
                @else
                    Find the Perfect Gift ✨
                @endif
            </h1>
            <p class="text-lg md:text-xl text-white/90 max-w-2xl mx-auto mb-6 leading-relaxed">
                @if(isset($category))
                    {{ $category->description ?: 'Browse our collection of ' . $category->name }}
                @elseif(isset($giftType))
                    {{ $giftType->description ?: 'Find the perfect ' . $giftType->name }}
                @elseif(isset($searchTerm) && $searchTerm)
                    Showing results for "{{ $searchTerm }}"
                @else
                    Discover unique and thoughtful gifts for everyone on your list
                @endif
            </p>
            
            @if(!isset($category) && !isset($giftType) && !isset($searchTerm))
            <div class="flex flex-wrap justify-center gap-3">
                <a href="#products" class="px-6 py-3 bg-white text-indigo-600 font-bold rounded-2xl shadow-2xl hover:shadow-3xl transition-shadow duration-200 flex items-center space-x-2">
                    <span>Browse Gifts</span>
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <a href="{{ route('articles.index') }}" class="px-6 py-3 bg-white/10 backdrop-blur-sm text-white font-bold rounded-2xl border-2 border-white/30 hover:bg-white/20 transition-colors duration-200 flex items-center space-x-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    <span>Read Articles</span>
                </a>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Products Grid -->
<div id="products" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
    <div id="initial-products" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
        @foreach($products as $product)
            <a href="{{ route('product.show', $product->slug) }}" class="group">
                <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition-shadow duration-300 overflow-hidden border border-gray-100">
                    <!-- Product Image -->
                    <div class="relative aspect-square overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100">
                        @if($product->image_medium_url)
                            <img 
                                src="{{ $product->image_medium_url }}" 
                                alt="{{ $product->title }}"
                                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                            >
                        @else
                            <div class="w-full h-full flex items-center justify-center">
                                <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                            </div>
                        @endif
                    </div>

                    <!-- Product Info -->
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors duration-300">
                            {{ $product->title }}
                        </h3>
                        
                        @if($product->short_description)
                            <p class="text-sm text-gray-600 line-clamp-2 leading-relaxed">
                                {{ $product->short_description }}
                            </p>
                        @endif
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Infinite Scroll Component -->
    <div id="infinite-scroll-container">
        <product-infinite-scroll
            :initial-count="{{ $products->count() }}"
            @if(isset($category))
                category-slug="{{ $category->slug }}"
            @elseif(isset($giftType))
                gift-type-slug="{{ $giftType->slug }}"
            @elseif(isset($searchTerm) && $searchTerm)
                search-term="{{ $searchTerm }}"
            @endif
        ></product-infinite-scroll>
    </div>
</div>
@endsection
