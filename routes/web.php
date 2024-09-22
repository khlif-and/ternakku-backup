<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::group([
    'prefix' => 'auth',
    'controller' => App\Http\Controllers\Admin\AuthController::class
], function () {
    Route::get('login', 'showLoginForm'); //->name('login');
    Route::post('login', 'login');
    Route::get('register', 'showRegisterForm'); //->name('register');
    // Route::post('verify', 'verify');
    // Route::post('resend-otp', 'resendOtp');
});
