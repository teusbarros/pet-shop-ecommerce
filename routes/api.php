<?php

use App\Http\Controllers\API\v1\AdminAuthController;
use App\Http\Controllers\API\v1\AdminController;
use App\Http\Controllers\API\v1\MainPageController;
use App\Http\Controllers\API\v1\UserAuthController;
use App\Http\Controllers\API\v1\UserController;
use App\Http\Middleware\v1\AdminMiddleware;
use App\Http\Middleware\v1\APIMiddleware;
use App\Http\Middleware\v1\NotAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/v1/admin/login', [AdminAuthController::class, 'login']);
Route::get('/v1/admin/logout', [AdminAuthController::class, 'logout']);

Route::post('/v1/user/login', [UserAuthController::class, 'login']);
Route::get('/v1/user/logout', [UserAuthController::class, 'logout']);
Route::post('/v1/user/forgot-password', [UserAuthController::class, 'forgot']);
Route::post('/v1/user/reset-password-token', [UserAuthController::class, 'reset']);

// main page
Route::get('/v1/main/promotions', [MainPageController::class, 'promotions']);
Route::get('/v1/main/blog', [MainPageController::class, 'blogs']);
Route::get('/v1/main/blog/{post}', [MainPageController::class, 'blog']);

Route::middleware([APIMiddleware::class])->prefix('v1/')->group(function (): void {
    Route::middleware([AdminMiddleware::class])->prefix('admin')->group(function (): void {
        Route::post('create', [AdminController::class, 'create']);
        Route::put('user-edit/{user}', [AdminController::class, 'edit']);
        Route::delete('user-delete/{user}', [AdminController::class, 'destroy']);
        Route::get('user-listing', [AdminController::class, 'index']);
    });
    Route::middleware([NotAdminMiddleware::class])->prefix('user')->group(function (): void {
        Route::get('/', [UserController::class, 'show']);
        Route::delete('/', [UserController::class, 'destroy']);
        Route::post('create', [UserController::class, 'create']);
        Route::put('edit', [UserController::class, 'edit']);
        Route::delete('delete', [UserController::class, 'destroy']);
    });
});
