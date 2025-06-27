<?php

use App\Http\Controllers\User\CartController;
use App\Http\Controllers\User\OrderController;
use App\Http\Controllers\User\ProductController;
use App\Http\Controllers\User\WishlistController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    // Products
    Route::get('/products', [ProductController::class, 'index']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::get('/products/featured', [ProductController::class, 'featured']);
    Route::get('/search', [ProductController::class, 'search']);

    // Cart
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'index']);
        Route::post('/add', [CartController::class, 'store']);
        Route::post('/update', [CartController::class, 'update']);
        Route::post('/remove', [CartController::class, 'destroy']);
        Route::get('/count', [CartController::class, 'count']);
    });

    // Orders
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::post('/place', [OrderController::class, 'store']);
        Route::get('/{id}/invoice', [OrderController::class, 'invoice']);
    });

    // Wishlist
    Route::prefix('wishlist')->group(function () {
        Route::get('/', [WishlistController::class, 'index']);
        Route::post('/toggle', [WishlistController::class, 'toggle']);
    });
});
