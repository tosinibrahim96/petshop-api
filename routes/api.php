<?php

use App\Http\Controllers\ShopUser\AuthController;
use App\Http\Controllers\ShopUser\UserController;
use App\Http\Controllers\ShopUser\UserOrderController;

Route::group(['prefix' => 'v1/user'], function () {

    Route::post('/create', [UserController::class, 'create']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password-token', [AuthController::class, 'resetPasswordWithToken']);

    Route::group(['middleware' => 'auth:api'], function () {
        Route::delete('/', [UserController::class, 'destroy']);
        Route::get('/', [UserController::class, 'show']);
        Route::put('/edit', [UserController::class, 'update']);
        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('orders', [UserOrderController::class, 'index']);
    });
});
