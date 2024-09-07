<?php

use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\VerificationController;
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


    # VerificationController Group
    Route::controller(VerificationController::class)->group(function () {
        Route::group(['middleware' => 'auth:api'], function () {
            Route::get('/is-email-verified', 'isEmailVerified');
            Route::get('/verify/email/{id}', 'verifyEmail')->name('verifyEmail.verify');
        });
        Route::post('/resend/email', 'resendVerificationEmail')->name('verifyEmail.resend’');
    });
});
