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

    Route::group(['prefix' => 'auth', 'controller' => App\Http\Controllers\Api\AuthController::class], function () {
        Route::post('register', 'register');
        Route::post('verify', 'verify');
        Route::post('resend-otp', 'resendOtp');
        Route::post('login', 'login');

        Route::middleware(['auth:api', 'email.verified',])->group(function() {
            Route::get('me', 'me');
            Route::post('logout', 'logout');
        });
    });

    Route::group(['prefix' => 'qurban'], function () {
        Route::group(['prefix' => 'partner', 'controller' => App\Http\Controllers\Api\Qurban\PartnerController::class], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
            Route::get('{id}/pen', 'getPen');
        });

        Route::group(['prefix' => 'breed', 'controller' => App\Http\Controllers\Api\Qurban\BreedController::class], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
        });

        Route::group(['prefix' => 'livestock', 'controller' => App\Http\Controllers\Api\Qurban\LivestockController::class], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
        });

        Route::group(['prefix' => 'blog', 'controller' => App\Http\Controllers\Api\Qurban\BlogController::class], function () {
            Route::get('/', 'index');
        });

        Route::middleware(['auth:api', 'email.verified'])->group(function() {
            Route::group(['prefix' => 'saving', 'controller' => App\Http\Controllers\Api\Qurban\SavingController::class], function () {
                Route::get('/register', 'index');
                Route::post('/register', 'register');
                Route::get('/register/{id}', 'detail');
                Route::post('/find-user', 'findUser');
            });

            Route::group(['prefix' => 'contract', 'controller' => App\Http\Controllers\Api\Qurban\ContractController::class], function () {
                Route::post('/', 'contract');
                Route::get('/{id}', 'detail');
            });
        });
    });

    Route::group(['prefix' => 'data-master', 'controller' => App\Http\Controllers\Api\DataMasterController::class], function () {
        Route::group(['prefix' => 'livestock'], function () {
            Route::get('type', 'getLivestockType');
            Route::get('sex', 'getLivestockSex');
            Route::get('group', 'getLivestockGroup');
            Route::get('breed', 'getLivestockBreed');
        });

        Route::group(['prefix' => 'location'], function () {
            Route::get('province', 'getProvince');
            Route::get('regency', 'getRegency');
            Route::get('district', 'getDistrict');
            Route::get('village', 'getVillage');
        });

        Route::get('bank', 'getBank');
    });

    Route::group(['prefix' => 'farming', 'middleware' => ['auth:api', 'email.verified' , 'farmer']], function () {
        Route::get('/farm', [App\Http\Controllers\Api\FarmController::class, 'index']);
        Route::get('/farm/{id}', [App\Http\Controllers\Api\FarmController::class, 'detail']);

        Route::group(['middleware' => ['check.farm.ownership']], function () {
            Route::group(['prefix' => 'dashboard','controller' => App\Http\Controllers\Api\Farming\DashboardController::class], function () {
                Route::get('/{farm_id}/pen', 'getPen');
                Route::get('/{farm_id}/livestock-population-summary', 'livestockPopulationSummary');
                Route::get('/{farm_id}/livestock', 'getLivestock');
            });

            Route::apiResource('pen', App\Http\Controllers\Api\Farming\PenController::class);
        });
    });
});
