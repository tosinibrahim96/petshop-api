<?php

use App\Http\Controllers\ShopUser\AuthController;
use App\Http\Controllers\ShopUser\UserController;

Route::group(['prefix' => 'v1/user'], function () {

    Route::post('/create', [UserController::class, 'create']);
    Route::post('/login', [AuthController::class, 'login']);
});
