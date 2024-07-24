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

    Route::group(['prefix' => 'auth'], function () {
        Route::post('register', [App\Http\Controllers\Api\AuthController::class, 'register']);
        Route::post('verify', [App\Http\Controllers\Api\AuthController::class, 'verify']);
        Route::post('/resend-otp', [App\Http\Controllers\Api\AuthController::class,'resendOtp']);
        Route::post('login', [App\Http\Controllers\Api\AuthController::class, 'login']);

        Route::middleware(['auth:api', 'email.verified'])->group(function() {
            Route::get('/me', [App\Http\Controllers\Api\AuthController::class, 'me']);
            Route::post('/logout', [App\Http\Controllers\Api\AuthController::class, 'logout']);
        });
    });

    Route::group(['prefix' => 'qurban'], function () {
        Route::group(['prefix' => 'partner'], function () {
            Route::get('/', [App\Http\Controllers\Api\Qurban\PartnerController::class, 'index']);
            Route::get('{id}', [App\Http\Controllers\Api\Qurban\PartnerController::class, 'detail']);
            Route::get('{id}/pen', [App\Http\Controllers\Api\Qurban\PartnerController::class, 'getPen']);
        });

        Route::group(['prefix' => 'livestock'], function () {
            Route::get('/', [App\Http\Controllers\Api\Qurban\LivestockController::class, 'index']);
            Route::get('{id}', [App\Http\Controllers\Api\Qurban\LivestockController::class, 'detail']);
        });


        Route::middleware(['auth:api', 'email.verified'])->group(function() {

        });
    });

    Route::group(['prefix' => 'data-master'], function () {
        Route::group(['prefix' => 'livestock'], function () {
            Route::get('type', [App\Http\Controllers\Api\DataMasterController::class, 'getLivestockType']);
            Route::get('sex', [App\Http\Controllers\Api\DataMasterController::class, 'getLivestockSex']);
            Route::get('group', [App\Http\Controllers\Api\DataMasterController::class, 'getLivestockGroup']);
            Route::get('breed', [App\Http\Controllers\Api\DataMasterController::class, 'getLivestockBreed']);
        });
    });
});
