<?php

use App\Http\Controllers\Api\v1\AuthController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'auth'], function () {
    Route::group(['prefix' => 'sign-up'], function () {
        Route::post('', [AuthController::class, 'signUp']);
        Route::post('confirm', [AuthController::class, 'confirmSignUp']);
        Route::post('resend-confirmation-code', [AuthController::class, 'resendConfirmationCode']);
    });

    Route::post('sign-in', [AuthController::class, 'signIn']);
    Route::get('user', [AuthController::class, 'user'])->middleware('cognito');
});
