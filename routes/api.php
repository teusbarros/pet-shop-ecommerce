<?php

use \App\Http\Controllers\API\v1\UserAuthController;
use \App\Http\Controllers\API\v1\UserController;
use App\Http\Controllers\API\v1\AdminAuthController;
use App\Http\Controllers\API\v1\AdminController;
use App\Http\Middleware\v1\APIMiddleware;
use Illuminate\Support\Facades\Route;

Route::post('/v1/admin/login', [AdminAuthController::class, 'login']);
Route::post('/v1/user/login', [UserAuthController::class, 'login']);

Route::middleware([APIMiddleware::class])->group(function (): void {
    Route::group(['prefix' => 'v1/admin'], function (): void {
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::post('create', [AdminController::class, 'create']);
        Route::put('user-edit/{user}', [AdminController::class, 'edit']);
        Route::delete('user-delete/{user}', [AdminController::class, 'destroy']);
        Route::get('user-listing', [AdminController::class, 'index']);
    });
    Route::group(['prefix' => 'v1/user'], function (): void {
        Route::get('', [UserController::class, 'show']);
        Route::delete('', [UserController::class, 'destroy']);
        Route::get('logout', [UserAuthController::class, 'logout']);
        Route::post('create', [UserController::class, 'create']);
        Route::put('edit', [UserController::class, 'edit']);
        Route::delete('delete', [UserController::class, 'destroy']);
    });
});
