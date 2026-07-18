<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{id}', [ProductController::class, 'show']);

Route::get('/products/{id}/reviews', [ReviewController::class, 'index']);

Route::get('/categories', [CategoryController::class, 'index']);
Route::get('/categories/{id}', [CategoryController::class, 'show']);

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/users', [UserController::class, 'index']);
    Route::get('/users/{id}', [UserController::class, 'show']);

    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    Route::post('/categories', [CategoryController::class, 'store']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    Route::get('/orders', [OrderController::class, 'index']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);

    Route::post('/reviews', [ReviewController::class, 'store']);
    Route::delete('/reviews/{id}', [ReviewController::class, 'destroy']);

    Route::get('/cart', [CartController::class, 'index']);
    Route::post('/cart', [CartController::class, 'store']);
    Route::put('/cart/{id}', [CartController::class, 'update']);
    Route::delete('/cart/{id}', [CartController::class, 'destroy']);
    Route::post('/cart/checkout', [CartController::class, 'checkout']);

    Route::get('/wishlists', [\App\Http\Controllers\WishlistController::class, 'index']);
    Route::post('/wishlists/toggle/{id}', [\App\Http\Controllers\WishlistController::class, 'toggle']);

    // Admin Routes
    Route::get('/admin/stats', [AdminController::class, 'stats']);
    Route::get('/admin/users', [AdminController::class, 'getUsers']);
    Route::delete('/admin/users/{id}', [AdminController::class, 'deleteUser']);
    Route::get('/admin/products', [AdminController::class, 'getProducts']);
    Route::delete('/admin/products/{id}', [AdminController::class, 'deleteProduct']);
});
