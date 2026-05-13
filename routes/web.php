<?php

use App\Http\Controllers\LandingController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\WhyUsController;
use App\Http\Controllers\Admin\InstagramController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\ProductController as FrontProductController;
use App\Http\Controllers\Api\SearchController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\MarqueeController;

Route::get('/', [LandingController::class, 'index'])->name('landing');
Route::get('/product/{slug}', [FrontProductController::class, 'show'])->name('product.show');
Route::get('/api/search', [SearchController::class, 'search'])->name('api.search');

Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Dashboard
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    // Why Us Section (Mengapa BeWood?)
    Route::get('why-us', [WhyUsController::class, 'index'])->name('why-us.index');
    Route::put('why-us/settings', [WhyUsController::class, 'updateSettings'])->name('why-us.settings.update');
    Route::put('why-us/items/{item}', [WhyUsController::class, 'updateItem'])->name('why-us.items.update');
    Route::put('why-us/stats/{stat}', [WhyUsController::class, 'updateStat'])->name('why-us.stats.update');

    // Hero Section Settings
    Route::get('settings', [SettingsController::class, 'edit'])->name('settings.edit');
    Route::put('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Products
    Route::resource('products', ProductController::class)->names([
        'index'   => 'products.index',
        'create'  => 'products.create',
        'store'   => 'products.store',
        'show'    => 'products.show',
        'edit'    => 'products.edit',
        'update'  => 'products.update',
        'destroy' => 'products.destroy',
    ]);
    Route::delete('products/{product}/main-image', [ProductController::class, 'destroyMainImage'])->name('products.destroy-main-image');
    Route::delete('product-images/{image}', [ProductController::class, 'destroyImage'])->name('product-images.destroy');

    // Categories
    Route::resource('categories', CategoryController::class)->names([
        'index'   => 'categories.index',
        'create'  => 'categories.create',
        'store'   => 'categories.store',
        'show'    => 'categories.show',
        'edit'    => 'categories.edit',
        'update'  => 'categories.update',
        'destroy' => 'categories.destroy',
    ]);
    Route::delete('categories/{category}/image', [CategoryController::class, 'destroyImage'])->name('categories.destroy-image');

    // Testimonials
    Route::get('testimonials', [TestimonialController::class, 'index'])->name('testimonials.index');
    Route::put('testimonials/settings', [TestimonialController::class, 'updateSettings'])->name('testimonials.settings.update');
    Route::get('testimonials/create', [TestimonialController::class, 'create'])->name('testimonials.create');
    Route::post('testimonials', [TestimonialController::class, 'store'])->name('testimonials.store');
    Route::get('testimonials/{testimonial}/edit', [TestimonialController::class, 'edit'])->name('testimonials.edit');
    Route::put('testimonials/{testimonial}', [TestimonialController::class, 'update'])->name('testimonials.update');
    Route::delete('testimonials/{testimonial}', [TestimonialController::class, 'destroy'])->name('testimonials.destroy');

    // Instagram
    Route::resource('instagram', InstagramController::class)->names([
        'index' => 'instagram.index',
        'create' => 'instagram.create',
        'store' => 'instagram.store',
        'edit' => 'instagram.edit',
        'update' => 'instagram.update',
        'destroy' => 'instagram.destroy',
    ]);

  // Orders
Route::resource('orders', OrderController::class)->only(['index', 'show']);
Route::get('orders/create-manual', [OrderController::class, 'createManual'])->name('orders.create-manual');
Route::post('orders/store-manual', [OrderController::class, 'storeManual'])->name('orders.store-manual');
Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.status');
    // FAQ
    Route::resource('faqs', FaqController::class)->names([
        'index' => 'faqs.index',
        'create' => 'faqs.create',
        'store' => 'faqs.store',
        'edit' => 'faqs.edit',
        'update' => 'faqs.update',
        'destroy' => 'faqs.destroy',
    ]);
   Route::resource('marquee', App\Http\Controllers\Admin\MarqueeController::class)->except(['show', 'create', 'edit'])->names([
    'index' => 'marquee.index',
    'store' => 'marquee.store',
    'update' => 'marquee.update',
    'destroy' => 'marquee.destroy',
]);
});

require __DIR__ . '/auth.php';
