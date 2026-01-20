<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')
    ->controller(AuthController::class)
    ->group(function () {
        Route::get('login', 'showLoginForm');
        Route::post('login', 'login')->name('login');
        Route::post('logout', 'logout')->name('logout');
        Route::get('register', 'showRegisterForm')->name('register.form');
        Route::post('register', 'register')->name('register');
        Route::get('verify-phone', fn () => view('admin.auth.verify-phone'))->name('verify.phone');
        Route::post('verify-otp', 'verifyOtp')->name('otp.verify');
        Route::post('resend-otp', 'resendOtp')->name('otp.resend');
    });
