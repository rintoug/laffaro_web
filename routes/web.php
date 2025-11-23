<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\ProductController as FrontProductController;
use App\Http\Controllers\Front\BlogController;
use App\Http\Controllers\Front\ArticleController;
use App\Http\Controllers\Front\PageController;

// Front-end routes
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/search', [HomeController::class, 'search'])->name('search');
Route::get('/category/{slug}', [HomeController::class, 'category'])->name('category.show');
Route::get('/gift/{slug}', [HomeController::class, 'giftType'])->name('gift-type.show');
Route::get('/product/{slug}', [FrontProductController::class, 'show'])->name('product.show');
Route::get('/articles', [ArticleController::class, 'index'])->name('articles.index');
Route::get('/articles/{slug}', [ArticleController::class, 'show'])->name('articles.show');
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::get('/privacy', [PageController::class, 'privacy'])->name('privacy');

// Admin SPA routes
Route::get('/admin/{any?}', function () {
    return view('admin');
})->where('any', '.*');
