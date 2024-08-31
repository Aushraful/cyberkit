<?php

use App\Http\Controllers\AuthenticationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    # AuthenticationController Group
    Route::controller(AuthenticationController::class)->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('/register', 'register');
            Route::post('/login', 'login');

            Route::group(['middleware' => 'auth:api'], function () {
                Route::post('/logout', 'logout');
                Route::post('/refresh', 'refreshToken');
            });
        });
    });
});
