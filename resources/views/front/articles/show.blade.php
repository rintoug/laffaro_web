@extends('front.layout')

@section('title', $article->meta_title ?: $article->title . ' - Gift Guide')
@section('meta_description', $article->meta_description)
@section('meta_keywords', $article->meta_keywords)

@section('content')
<div class="bg-gray-50 py-12">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-purple-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('articles.index') }}" class="hover:text-purple-600">Articles</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium truncate">{{ $article->title }}</li>
            </ol>
        </nav>

        <!-- Article Header Section -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
            <!-- Article Banner Image -->
            @if($article->image_url)
                <div class="w-full aspect-[21/9] overflow-hidden bg-gradient-to-br from-purple-100 to-pink-100">
                    <img 
                        src="{{ $article->image_url }}" 
                        alt="{{ $article->title }}"
                        class="w-full h-full object-cover"
                    >
                </div>
            @endif

            <!-- Article Details -->
            <div class="p-8 lg:p-12">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-6">
                    {{ $article->title }}
                </h1>

                <!-- Article Description -->
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $description !!}
                </div>
            </div>
        </div>

        <!-- Products Section -->
        @if($article->products->count() > 0)
            <div class="space-y-6">
                
                @foreach($article->products as $index => $product)
                    <div class="bg-white rounded-lg shadow-sm hover:shadow-md transition-shadow overflow-hidden">
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <!-- Product Image (Left Side) -->
                            <div class="md:col-span-1">
                                <div class="aspect-square overflow-hidden bg-gray-100">
                                    @if($product->image_url)
                                        <img 
                                            src="{{ $product->image_url }}" 
                                            alt="{{ $product->title }}"
                                            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
                                        >
                                    @else
                                        <div class="w-full h-full flex items-center justify-center">
                                            <svg class="w-20 h-20 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                            </svg>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Product Details (Right Side) -->
                            <div class="md:col-span-2 p-6 flex flex-col justify-between">
                                <div class="flex-1">
                                    <!-- Product Number Badge -->
                                    <div class="inline-block px-3 py-1 bg-purple-100 text-purple-700 text-sm font-semibold rounded-full mb-4">
                                        #{{ $index + 1 }}
                                    </div>

                                    <!-- Product Title -->
                                    <h3 class="text-2xl font-bold text-gray-900 mb-4">
                                        {{ $product->title }}
                                    </h3>

                                    <!-- Product Description -->
                                    <div class="text-gray-700 mb-6 leading-relaxed">
                                        {!! nl2br(e($product->description)) !!}
                                    </div>
                                </div>

                                <!-- Affiliate Link Button -->
                                <div>
                                    <a 
                                        href="{{ $product->aff_link }}" 
                                        target="_blank"
                                        rel="noopener noreferrer nofollow"
                                        class="inline-flex items-center justify-center w-full md:w-auto px-8 py-4 bg-gradient-to-r from-purple-600 to-pink-600 text-white font-bold rounded-lg hover:from-purple-700 hover:to-pink-700 transform hover:scale-105 transition-all duration-200 shadow-lg hover:shadow-xl"
                                    >
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                        </svg>
                                        Buy Now
                                        <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                <svg class="mx-auto h-16 w-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                </svg>
                <h3 class="text-xl font-semibold text-gray-900 mb-2">No products available</h3>
                <p class="text-gray-600">Products will be added to this guide soon.</p>
            </div>
        @endif

        <!-- Back to Articles Button -->
        <div class="mt-12">
            <a 
                href="{{ route('articles.index') }}" 
                class="inline-flex items-center text-purple-600 hover:text-purple-700 font-medium"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Articles
            </a>
        </div>
    </div>
</div>
@endsection
