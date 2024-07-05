<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\UserController;

Route::group(['prefix' => 'v1/admin'], function () {

    Route::post('/login', [AuthController::class, 'login']);
    Route::post('forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('reset-password-token', [AuthController::class, 'resetPasswordWithToken']);

    Route::group(['middleware' => ['auth:api', 'admin']], function () {
        Route::post('/create', [AdminController::class, 'create']);
        Route::get('logout', [AuthController::class, 'logout']);

        Route::get('/user-listing', [UserController::class, 'index']);
        Route::put('/user-edit/{uuid}', [UserController::class, 'update']);
        Route::delete('/user-delete/{uuid}', [UserController::class, 'destroy']);
    });
});
