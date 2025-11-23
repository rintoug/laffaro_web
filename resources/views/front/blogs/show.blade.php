@extends('front.layout')

@section('title', $blog->meta_title ?: $blog->title . ' - Blog')
@section('meta_description', $blog->meta_description ?: $blog->short_description)
@section('meta_keywords', $blog->meta_keywords)

@section('content')
<div class="bg-gray-50 py-12">
    <article class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="mb-8">
            <ol class="flex items-center space-x-2 text-sm text-gray-600">
                <li><a href="{{ route('home') }}" class="hover:text-indigo-600">Home</a></li>
                <li><span class="mx-2">/</span></li>
                <li><a href="{{ route('blog.index') }}" class="hover:text-indigo-600">Blog</a></li>
                <li><span class="mx-2">/</span></li>
                <li class="text-gray-900 font-medium truncate">{{ $blog->title }}</li>
            </ol>
        </nav>

        <!-- Article Header -->
        <header class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="mb-6">
                <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
                    {{ $blog->title }}
                </h1>

                <!-- Meta Info -->
                <div class="flex flex-wrap items-center gap-4 text-gray-600">
                    @if($blog->author)
                        <div class="flex items-center">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-purple-600 flex items-center justify-center text-white font-semibold mr-3">
                                {{ substr($blog->author, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-900">{{ $blog->author }}</p>
                                <p class="text-xs text-gray-500">Author</p>
                            </div>
                        </div>
                    @endif

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        {{ $blog->created_at->format('F d, Y') }}
                    </div>

                    <div class="flex items-center text-sm">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ ceil(str_word_count(strip_tags($description)) / 200) }} min read
                    </div>
                </div>
            </div>

            <!-- Short Description -->
            @if($blog->short_description)
                <p class="text-xl text-gray-700 leading-relaxed border-l-4 border-indigo-500 pl-4 italic">
                    {{ $blog->short_description }}
                </p>
            @endif
        </header>

        <!-- Featured Image -->
        @if($blog->image_url)
            <div class="bg-white rounded-lg shadow-sm overflow-hidden mb-8">
                <img 
                    src="{{ $blog->image_url }}" 
                    alt="{{ $blog->title }}"
                    class="w-full h-auto"
                >
            </div>
        @endif

        <!-- Article Content -->
        <div class="bg-white rounded-lg shadow-sm p-8 mb-8">
            <div class="prose prose-lg max-w-none">
                {!! $description !!}
            </div>
        </div>

        <!-- Share Section -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <div class="flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900">Share this article</h3>
                <div class="flex space-x-3">
                    <a 
                        href="https://twitter.com/intent/tweet?url={{ urlencode(route('blog.show', $blog->slug)) }}&text={{ urlencode($blog->title) }}" 
                        target="_blank"
                        class="p-2 rounded-full bg-blue-100 text-blue-600 hover:bg-blue-200 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2c9 5 20 0 20-11.5a4.5 4.5 0 00-.08-.83A7.72 7.72 0 0023 3z" />
                        </svg>
                    </a>
                    <a 
                        href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('blog.show', $blog->slug)) }}" 
                        target="_blank"
                        class="p-2 rounded-full bg-indigo-100 text-indigo-600 hover:bg-indigo-200 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M18 2h-3a5 5 0 00-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 011-1h3z" />
                        </svg>
                    </a>
                    <a 
                        href="https://www.linkedin.com/shareArticle?mini=true&url={{ urlencode(route('blog.show', $blog->slug)) }}&title={{ urlencode($blog->title) }}" 
                        target="_blank"
                        class="p-2 rounded-full bg-blue-100 text-blue-700 hover:bg-blue-200 transition-colors"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M16 8a6 6 0 016 6v7h-4v-7a2 2 0 00-2-2 2 2 0 00-2 2v7h-4v-7a6 6 0 016-6zM2 9h4v12H2z" />
                            <circle cx="4" cy="4" r="2" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Back to Blog Button -->
        <div class="mb-8">
            <a 
                href="{{ route('blog.index') }}" 
                class="inline-flex items-center text-indigo-600 hover:text-indigo-700 font-medium"
            >
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Back to Blog
            </a>
        </div>
    </article>

    <!-- Related Articles -->
    @if($relatedBlogs->count() > 0)
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mt-16">
            <h2 class="text-2xl font-bold text-gray-900 mb-6">Related Articles</h2>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($relatedBlogs as $relatedBlog)
                    <a href="{{ route('blog.show', $relatedBlog->slug) }}" class="group block bg-white rounded-lg shadow-sm hover:shadow-lg transition-all overflow-hidden">
                        @if($relatedBlog->image_url)
                            <div class="aspect-video overflow-hidden bg-gray-100">
                                <img 
                                    src="{{ $relatedBlog->image_url }}" 
                                    alt="{{ $relatedBlog->title }}"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                >
                            </div>
                        @endif
                        <div class="p-4">
                            <h3 class="font-semibold text-gray-900 mb-2 line-clamp-2 group-hover:text-indigo-600 transition-colors">
                                {{ $relatedBlog->title }}
                            </h3>
                            <p class="text-sm text-gray-500">
                                {{ $relatedBlog->created_at->format('M d, Y') }}
                            </p>
                        </div>
                    </a>
                @endforeach
            </div>
        </div>
    @endif
</div>
@endsection
