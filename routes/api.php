<?php

use App\Http\Controllers\API\v1\AdminAuthController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::group(['prefix' => 'admin'], function (){
        Route::post('login', [AdminAuthController::class, 'login'])->name('login');
    });
});
