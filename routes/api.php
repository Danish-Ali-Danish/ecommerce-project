<?php

use App\Http\Controllers\Api\BrandApiController;
use App\Http\Controllers\Api\CategoryApiController;
use App\Http\Controllers\Api\ProductApiController;
use Illuminate\Support\Facades\Route;

Route::get('/categories', [CategoryApiController::class, 'index']);
Route::get('/brands', [BrandApiController::class, 'index']);
Route::get('/featured-products', [ProductApiController::class, 'featured']);
