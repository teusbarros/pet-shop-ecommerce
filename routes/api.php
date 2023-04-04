<?php

use App\Http\Controllers\API\v1\AdminAuthController;
use App\Http\Controllers\API\v1\AdminController;
use App\Http\Controllers\API\v1\BrandController;
use App\Http\Controllers\API\v1\CategoryController;
use App\Http\Controllers\API\v1\MainPageController;
use App\Http\Controllers\API\v1\ProductController;
use App\Http\Controllers\API\v1\UserAuthController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Middleware\v1\AdminMiddleware;
use App\Http\Middleware\v1\APIMiddleware;
use App\Http\Middleware\v1\NotAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::prefix('v1/')->group(function (): void {
    // admin
    Route::post('admin/login', [AdminAuthController::class, 'login']);
    Route::get('admin/logout', [AdminAuthController::class, 'logout']);
    Route::post('admin/create', [AdminController::class, 'create']);

    // users
    Route::post('user/login', [UserAuthController::class, 'login']);
    Route::get('user/logout', [UserAuthController::class, 'logout']);
    Route::post('user/forgot-password', [UserAuthController::class, 'forgot']);
    Route::post('user/reset-password-token', [UserAuthController::class, 'reset']);
    Route::post('user/create', [UserController::class, 'create']);

    // main page
    Route::get('main/promotions', [MainPageController::class, 'promotions']);
    Route::get('main/blog', [MainPageController::class, 'blogs']);
    Route::get('main/blog/{post}', [MainPageController::class, 'blog']);

    // categories
    Route::get('categories', [CategoryController::class, 'index']);
    Route::get('category/{category}', [CategoryController::class, 'show']);

    // brands
    Route::get('brands', [BrandController::class, 'index']);
    Route::get('brand/{brand}', [BrandController::class, 'show']);

    // products
    Route::get('products', [ProductController::class, 'index']);
    Route::get('product/{uuid}', [ProductController::class, 'show']);
});



Route::middleware([APIMiddleware::class])->prefix('v1/')->group(function (): void {
    // only admin
    Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function (): void {
        Route::put('user-edit/{user}', [AdminController::class, 'edit']);
        Route::delete('user-delete/{user}', [AdminController::class, 'destroy']);
        Route::get('user-listing', [AdminController::class, 'index']);
    });
    // only non admin
    Route::middleware([NotAdminMiddleware::class])->prefix('user')->group(function (): void {
        Route::get('/', [UserController::class, 'show']);
        Route::delete('/', [UserController::class, 'destroy']);
        Route::put('edit', [UserController::class, 'edit']);
        Route::delete('delete', [UserController::class, 'destroy']);
    });

    // both
    Route::prefix('category')->group(function (): void {
        Route::post('create', [CategoryController::class, 'create']);
        Route::put('{category}', [CategoryController::class, 'edit']);
        Route::delete('{category}', [CategoryController::class, 'destroy']);
    });
    Route::prefix('brand')->group(function (): void {
        Route::post('create', [BrandController::class, 'create']);
        Route::put('{brand}', [BrandController::class, 'edit']);
        Route::delete('{brand}', [BrandController::class, 'destroy']);
    });
    Route::prefix('product')->group(function (): void {
        Route::post('create', [ProductController::class, 'create']);
        Route::put('{uuid}', [ProductController::class, 'edit']);
        Route::delete('{uuid}', [ProductController::class, 'destroy']);
    });
});
