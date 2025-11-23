@extends('front.layout')

@section('title', $product->meta_title ?: $product->title . ' - Gift Store')
@section('meta_description', $product->meta_description ?: $product->short_description)
@section('meta_keywords', $product->meta_keywords)

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
    <!-- Breadcrumb -->
    <nav class="mb-8">
        <ol class="flex items-center space-x-2 text-sm">
            <li>
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-indigo-600 transition-colors flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Home
                </a>
            </li>
            <li><span class="text-gray-400">/</span></li>
            <li class="text-gray-900 font-semibold truncate">{{ $product->title }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="bg-white rounded-3xl shadow-2xl overflow-hidden border border-gray-100">
        <!-- Product Image and Info Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 p-8 lg:p-12">
            <!-- Left Side - Product Image -->
            <div class="space-y-6">
                <div class="aspect-square rounded-2xl overflow-hidden bg-gradient-to-br from-gray-50 to-gray-100 shadow-xl">
                    @if($product->image_url)
                        <img 
                            src="{{ $product->image_url }}" 
                            alt="{{ $product->title }}"
                            class="w-full h-full object-cover"
                        >
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <svg class="w-32 h-32 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                            </svg>
                        </div>
                    @endif
                </div>

                <!-- Categories & Gift Types -->
                @if($product->categories->count() > 0 || $product->giftTypes->count() > 0)
                    <div class="flex flex-wrap gap-3">
                        @foreach($product->categories as $category)
                            <a href="{{ route('category.show', $category->slug) }}" class="inline-flex items-center px-4 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl text-sm font-semibold transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                                </svg>
                                {{ $category->name }}
                            </a>
                        @endforeach
                        @foreach($product->giftTypes as $giftType)
                            <a href="{{ route('gift-type.show', $giftType->slug) }}" class="inline-flex items-center px-4 py-2.5 bg-gradient-to-r from-indigo-100 to-purple-100 hover:from-indigo-200 hover:to-purple-200 text-indigo-700 rounded-xl text-sm font-semibold transition-colors duration-200">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7" />
                                </svg>
                                {{ $giftType->name }}
                            </a>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Right Side - Product Info -->
            <div class="space-y-8">
                <div>
                    <h1 class="text-3xl md:text-3xl font-extrabold text-gray-900 mb-6 leading-tight">
                        {{ $product->title }}
                    </h1>

                    <!-- Short Description -->
                    @if($product->short_description)
                        <div class="prose prose-lg max-w-none">
                            <p class="text-xl text-gray-600 leading-relaxed">
                                {{ $product->short_description }}
                            </p>
                        </div>
                    @endif
                </div>

                <!-- Affiliate Link Button -->
                @if($product->aff_link)
                    <div class="space-y-4 pt-6">
                        <a 
                            href="{{ $product->aff_link }}" 
                            target="_blank"
                            rel="nofollow noopener"
                            class="block w-full text-center px-10 py-5 bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 hover:from-indigo-700 hover:via-purple-700 hover:to-pink-700 text-white font-bold text-lg rounded-2xl shadow-2xl hover:shadow-3xl transition-all duration-200"
                        >
                            <span class="flex items-center justify-center">
                                <svg class="w-6 h-6 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                                </svg>
                                <span>Buy Now</span>
                                <svg class="w-5 h-5 ml-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                </svg>
                            </span>
                        </a>
                        
                    </div>
                @endif

                
            </div>
        </div>

        <!-- Product Description Section -->
        @if($description)
            <div class="border-t border-gray-200 p-6 lg:p-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">Product Description</h2>
                <div class="prose prose-lg max-w-none">
                    {!! $description !!}
                </div>
            </div>
        @endif
    </div>

    <!-- Back to Products Button -->
    <div class="mt-8">
        <a 
            href="{{ route('home') }}" 
            class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium"
        >
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Back to Products
        </a>
    </div>
</div>
@endsection
