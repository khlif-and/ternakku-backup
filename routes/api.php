<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'middleware' => 'api',
], function () {

    Route::group([
        'prefix' => 'auth'
    ], function () {
        Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
        Route::post('verify', [App\Http\Controllers\Api\AuthController::class, 'verify']);
        Route::post('/resend-otp', [App\Http\Controllers\Api\AuthController::class,'resendOtp']);
        Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

        Route::middleware(['auth:api', 'email.verified'])->group(function() {
            Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
            Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        });
    });

    Route::group([
        'prefix' => 'qurban',
    ], function () {
        Route::get('partner', [App\Http\Controllers\Api\Qurban\PartnerController::class, 'index']);
        Route::get('partner/{id}', [App\Http\Controllers\Api\Qurban\PartnerController::class, 'detail']);
        Route::get('livestock', [App\Http\Controllers\Api\Qurban\LivestockController::class, 'index']);

        Route::group([
            'middleware' => ['auth:api', 'email.verified'],
        ], function () {

        });
    });

});
