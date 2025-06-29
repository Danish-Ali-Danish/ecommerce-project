<?php

use App\Http\Controllers\User\HomeController;
use App\Http\Controllers\User\PageController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrandController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use Illuminate\Support\Facades\Route;

// Page Routes

/*
 * w
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 */

// Authentication Routes
Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (Dashboard, Categories, Brands, Root Redirect)
Route::middleware('auth')->group(function () {
    // Dashboard
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // Redirect root to dashboard if authenticated
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });
    Route::Resource('brands', BrandController::class);
    Route::Resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::resource('orders', OrderController::class);
    Route::get('/orders-list', [OrderController::class, 'list'])->name('orders.list');
    // Route::get('/', [PageController::class, 'home'])->name('home');
    Route::get('/products', [PageController::class, 'products'])->name('products');
    Route::get('/product/{id}', [PageController::class, 'productDetails'])->name('product.details');
    Route::get('/cart', [PageController::class, 'cart'])->name('cart');
    Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [PageController::class, 'orders'])->name('orders');
    Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');
    Route::get('welcome', function () {
        return view('welcome');
    })->name('welcome');
    Route::get('/', [HomeController::class, 'index'])->name('home');
    Route::get('/cate', [FrontendController::class, 'allCate'])->name('allcate');
    Route::get('/cate/preview/{id}', [FrontendController::class, 'preview']);
});
