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
        'prefix' => 'auth',
        'controller' => App\Http\Controllers\Api\AuthController::class
    ], function () {
        Route::post('register', 'register');
        Route::post('verify', 'verify');
        Route::post('resend-otp', 'resendOtp');
        Route::post('login', 'login');

        Route::middleware(['auth:api', 'email.verified'])->group(function() {
            Route::get('me', 'me');
            Route::post('logout', 'logout');
        });
    });

    Route::group(['prefix' => 'qurban'], function () {
        Route::group([
            'prefix' => 'partner',
            'controller' => App\Http\Controllers\Api\Qurban\PartnerController::class
        ], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
            Route::get('{id}/pen', 'getPen');
        });

        Route::group([
            'prefix' => 'livestock',
            'controller' => App\Http\Controllers\Api\Qurban\LivestockController::class
        ], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
        });
    });

    Route::group([
        'prefix' => 'data-master',
        'controller' => App\Http\Controllers\Api\DataMasterController::class
    ], function () {
        Route::group(['prefix' => 'livestock'], function () {
            Route::get('type', 'getLivestockType');
            Route::get('sex', 'getLivestockSex');
            Route::get('group', 'getLivestockGroup');
            Route::get('breed', 'getLivestockBreed');
        });
    });
});
