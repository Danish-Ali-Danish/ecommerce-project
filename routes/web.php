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

/*
 * |--------------------------------------------------------------------------
 * | Web Routes
 * |--------------------------------------------------------------------------
 */

// =========================
// 🔐 Authentication Routes
// =========================
Route::get('/login', [AuthController::class, 'createLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// ===============================
// 🔐 Protected Routes (Authenticated Users Only)
// ===============================
Route::middleware(['auth'])->group(function () {
    // =====================
    // 🧭 Dashboard Routes
    // =====================
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/', fn() => redirect()->route('dashboard'));

    // =====================
    // 🛠️ Admin Routes
    // =====================
    Route::resource('brands', BrandController::class);
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::post('products/bulk-delete', [ProductController::class, 'bulkDelete'])->name('products.bulkDelete');

    // ... other routes ...
    // 🛍️ Frontend User Routes (Protected)
    // =====================

    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::get('/allproducts', action: [PageController::class, 'allproducts'])->name('allproducts');
    Route::get('/product/{id}', [PageController::class, 'productDetails'])->name('product.details');
    Route::get('/cart', [PageController::class, 'cart'])->name('cart');
    Route::get('/checkout', [PageController::class, 'checkout'])->name('checkout');
    Route::get('/orders', [PageController::class, 'orders'])->name('orders');
    Route::get('/wishlist', [PageController::class, 'wishlist'])->name('wishlist');

    // =====================
    // 📂 Categories & Brands (Frontend)
    // =====================
    Route::get('/cate', [FrontendController::class, 'allCate'])->name('allcate');
    Route::get('/cate/preview/{id}', [FrontendController::class, 'preview']);
    Route::get('/all-brands', [FrontendController::class, 'allBrands'])->name('allbrands');
    Route::get('/brand-preview/{id}', [FrontendController::class, 'previewBrand']);

    // 🧪 Optional fallback or test
    Route::get('/welcome', fn() => view('welcome'))->name('welcome');
});
