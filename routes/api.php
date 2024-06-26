<?php

use App\Http\Controllers\ShopUser\AuthController;

Route::group(['prefix' => 'v1/user'], function () {

    Route::post('/login', [AuthController::class, 'login']);

    Route::group(['middleware' => ['auth:api']], function () {
        Route::get('/protected', [AuthController::class, 'protected']);
    });
});
