<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\GiftTypeController;
use App\Http\Controllers\Admin\GiftArticleController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\ImageUploadController;
use App\Http\Controllers\Front\ProductApiController;
use App\Http\Controllers\Front\BlogApiController;
use App\Http\Controllers\Front\ArticleApiController;

// Public API routes for front-end
Route::get('products', [ProductApiController::class, 'index']);
Route::get('blogs', [BlogApiController::class, 'index']);
Route::get('articles', [ArticleApiController::class, 'index']);

// Public admin login (uses Passport to create personal access token)
Route::post('admin/login', [AuthController::class, 'login']);

// Grouped admin API routes protected by Passport and admin middleware
Route::middleware(['auth:api', 'admin'])->prefix('admin')->group(function () {
    // Auth
    Route::post('logout', [AuthController::class, 'logout']);
    Route::get('me', [AuthController::class, 'user']);

    // Image uploads (common for all modules)
    Route::post('upload-image', [ImageUploadController::class, 'upload']);
    Route::post('editor-upload-image', [ImageUploadController::class, 'uploadForEditor']);

    // Products
    Route::get('products', [ProductController::class, 'index']);
    Route::post('products', [ProductController::class, 'store']);
    Route::get('products/{id}', [ProductController::class, 'show']);
    Route::put('products/{id}', [ProductController::class, 'update']);
    Route::delete('products/{id}', [ProductController::class, 'destroy']);

    // Categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::post('categories', [CategoryController::class, 'store']);
    Route::get('categories/{id}', [CategoryController::class, 'show']);
    Route::put('categories/{id}', [CategoryController::class, 'update']);
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);
    Route::get('categories-all', [CategoryController::class, 'all']);

    // Gift types
    Route::get('gift-types', [GiftTypeController::class, 'index']);
    Route::post('gift-types', [GiftTypeController::class, 'store']);
    Route::get('gift-types/{id}', [GiftTypeController::class, 'show']);
    Route::put('gift-types/{id}', [GiftTypeController::class, 'update']);
    Route::delete('gift-types/{id}', [GiftTypeController::class, 'destroy']);
    Route::get('gift-types-all', [GiftTypeController::class, 'all']);

    // Gift articles
    Route::get('gift-articles', [GiftArticleController::class, 'index']);
    Route::post('gift-articles', [GiftArticleController::class, 'store']);
    Route::get('gift-articles/{id}', [GiftArticleController::class, 'show']);
    Route::put('gift-articles/{id}', [GiftArticleController::class, 'update']);
    Route::delete('gift-articles/{id}', [GiftArticleController::class, 'destroy']);

    // Blogs
    Route::get('blogs', [BlogController::class, 'index']);
    Route::post('blogs', [BlogController::class, 'store']);
    Route::get('blogs/{id}', [BlogController::class, 'show']);
    Route::put('blogs/{id}', [BlogController::class, 'update']);
    Route::delete('blogs/{id}', [BlogController::class, 'destroy']);
});
