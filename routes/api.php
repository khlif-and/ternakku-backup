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
            Route::get('disease', 'getLivestockDisease');
        });

        Route::get('/region', 'getRegion');

        Route::get('bank', 'getBank');

    });

    Route::group(['prefix' => 'farming'], function () {
        Route::group(['prefix' => 'blog', 'controller' => App\Http\Controllers\Api\Farming\BlogController::class], function () {
            Route::get('/', 'index');
        });

        Route::group(['middleware' =>   ['auth:api', 'email.verified']], function(){
            Route::post('/farm', [App\Http\Controllers\Api\FarmController::class, 'store']);

            Route::group(['middleware' => ['farmer']] ,  function(){
                Route::group(['prefix' => 'farm'], function(){
                    Route::get('/', [App\Http\Controllers\Api\FarmController::class, 'index']);
                    Route::get('/{farmId}', [App\Http\Controllers\Api\FarmController::class, 'detail']);
                    Route::post('/{farmId}/update', [App\Http\Controllers\Api\FarmController::class, 'update']);
                    Route::delete('/{farmId}', [App\Http\Controllers\Api\FarmController::class, 'destroy']);
                });

                Route::group(['middleware' => ['check.farm.ownership']], function () {
                    Route::group(['prefix' => 'dashboard','controller' => App\Http\Controllers\Api\Farming\DashboardController::class], function () {
                        Route::get('/{farm_id}/livestock-population-summary', 'livestockPopulationSummary');
                        Route::get('/{farm_id}/livestock', 'getLivestock');
                        Route::get('/{farm_id}/livestock/{id}', 'getDetailLivestock');
                    });

                    Route::group(['prefix' => 'pen','controller' => App\Http\Controllers\Api\Farming\PenController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{pen_id}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{pen_id}/update', 'update');
                        Route::delete('/{farm_id}/{pen_id}', 'destroy');
                    });

                    Route::group(['prefix' => 'livestock-reception', 'controller' => App\Http\Controllers\Api\Farming\LivestockReceptionController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{livestockReceptionId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{livestockReceptionId}/update', 'update');
                        Route::delete('/{farm_id}/{livestockReceptionId}', 'destroy');
                    });

                    Route::group(['prefix' => 'livestock-death', 'controller' => App\Http\Controllers\Api\Farming\LivestockDeathController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{livestockDeathId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{livestockDeathId}/update', 'update');
                        Route::delete('/{farm_id}/{livestockDeathId}', 'destroy');
                    });

                    Route::group(['prefix' => 'livestock-sale-weight', 'controller' => App\Http\Controllers\Api\Farming\LivestockSaleWeightController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{livestockSaleWeightId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{livestockSaleWeightId}/update', 'update');
                        Route::delete('/{farm_id}/{livestockSaleWeightId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-analysis-global', 'controller' => App\Http\Controllers\Api\Farming\MilkAnalysisGlobalController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkAnalysisGlobalId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkAnalysisGlobalId}/update', 'update');
                        Route::delete('/{farm_id}/{milkAnalysisGlobalId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-production-global', 'controller' => App\Http\Controllers\Api\Farming\MilkProductionGlobalController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkProductionGlobalId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkProductionGlobalId}/update', 'update');
                        Route::delete('/{farm_id}/{milkProductionGlobalId}', 'destroy');
                    });

                    Route::group(['prefix' => 'feed-medicine-purchase', 'controller' => App\Http\Controllers\Api\Farming\FeedMedicinePurchaseController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{id}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{id}/update', 'update');
                        Route::delete('/{farm_id}/{id}', 'destroy');
                    });

                    Route::group(['prefix' => 'feeding-individu', 'controller' => App\Http\Controllers\Api\Farming\FeedingIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{feedingIndividuId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{feedingIndividuId}/update', 'update');
                        Route::delete('/{farm_id}/{feedingIndividuId}', 'destroy');
                    });

                    // Route::group(['prefix' => 'feeding-colony', 'controller' => App\Http\Controllers\Api\Farming\FeedingColonyController::class], function () {
                    //     Route::get('/{farm_id}', 'index');
                    //     Route::get('/{farm_id}/{feedingColonyId}', 'show');
                    //     Route::post('/{farm_id}', 'store');
                    //     Route::post('/{farm_id}/{feedingColonyId}/update', 'update');
                    //     Route::delete('/{farm_id}/{feedingColonyId}', 'destroy');
                    // });

                    // Route::group(['prefix' => 'treatment-individu', 'controller' => App\Http\Controllers\Api\Farming\TreatmentIndividuController::class], function () {
                    //     Route::get('/{farm_id}', 'index');
                    //     Route::get('/{farm_id}/{treatmentIndividuId}', 'show');
                    //     Route::post('/{farm_id}', 'store');
                    //     Route::post('/{farm_id}/{treatmentIndividuId}/update', 'update');
                    //     Route::delete('/{farm_id}/{treatmentIndividuId}', 'destroy');
                    // });

                    // Route::group(['prefix' => 'treatment-colony', 'controller' => App\Http\Controllers\Api\Farming\TreatmentColonyController::class], function () {
                    //     Route::get('/{farm_id}', 'index');
                    //     Route::get('/{farm_id}/{treatmentColonyId}', 'show');
                    //     Route::post('/{farm_id}', 'store');
                    //     Route::post('/{farm_id}/{treatmentColonyId}/update', 'update');
                    //     Route::delete('/{farm_id}/{treatmentColonyId}', 'destroy');
                    // });
                });
            });
        });
    });
});
