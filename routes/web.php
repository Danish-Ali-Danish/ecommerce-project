<?php

use App\Http\Controllers\User\PageController;
use Illuminate\Support\Facades\Route;

// Page Routes
Route::get('/', [PageController::class, 'home'])->name('home');
Route::get('/products', [PageController::class, 'products'])->name('products');
Route::get('/product/{id}', [PageController::class, 'productDetails'])->name('product.details');
Route::get('/cart', [PageController::class, 'cart'])->name('cart');
Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
Route::get('/orders', [PageController::class, 'orders'])->name('orders');
Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
