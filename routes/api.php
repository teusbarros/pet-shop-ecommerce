<?php

use App\Http\Controllers\API\v1\AdminAuthController;
use App\Http\Controllers\API\v1\AdminController;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\v1\APIMiddleware;

Route::post('/v1/admin/login', [AdminAuthController::class, 'login']);

Route::middleware([APIMiddleware::class])->group(function () {
    Route::group(['prefix' => 'v1/admin'], function (){
        Route::get('logout', [AdminAuthController::class, 'logout']);
        Route::post('create', [AdminController::class, 'create']);
        Route::put('user-edit/{uuid}', [AdminController::class, 'edit']);
        Route::delete('user-delete/{uuid}', [AdminController::class, 'destroy']);
        Route::get('user-listing', [AdminController::class, 'index']);
    });
});
