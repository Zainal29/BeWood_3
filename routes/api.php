<?php

use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\WishlistController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;

// Route produk (publik)
Route::get('products', [ProductController::class, 'index']);
Route::get('products/featured', [ProductController::class, 'featured']);
Route::get('products/bestseller', [ProductController::class, 'bestseller']);
Route::get('products/{slug}', [ProductController::class, 'show']);

// Route pencarian (publik)
// Route::get('search', [SearchController::class, 'search']);  // URL akan menjadi /api/search

// Route dengan autentikasi
Route::middleware('auth:sanctum')->group(function () {
    Route::get('cart', [CartController::class, 'index']);
    Route::post('cart/add', [CartController::class, 'add']);
    Route::put('cart/update/{itemId}', [CartController::class, 'update']);
    Route::delete('cart/remove/{itemId}', [CartController::class, 'remove']);
    Route::delete('cart/clear', [CartController::class, 'clear']);
    Route::get('wishlist', [WishlistController::class, 'index']);
    Route::post('wishlist/toggle', [WishlistController::class, 'toggle']);
});

Route::get('/ping', function() {
    return 'pong';
});
