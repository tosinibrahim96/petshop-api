<?php

use App\Http\Controllers\ShopUser\AuthController;
use App\Http\Controllers\ShopUser\UserController;

Route::group(['prefix' => 'v1/user'], function () {

    Route::post('/create', [UserController::class, 'create']);
    Route::post('/login', [AuthController::class, 'login']);


    Route::group(['middleware' => 'auth:api'], function () {
        Route::delete('/', [UserController::class, 'destroy']);
        Route::get('/', [UserController::class, 'show']);
        Route::put('/edit', [UserController::class, 'update']);
    });
});
