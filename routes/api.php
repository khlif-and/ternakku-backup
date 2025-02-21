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
        Route::post('forgot-password', 'forgotPassword');
        Route::post('forgot-password/reset', 'resetPassword');

        Route::middleware(['auth:api', 'email.verified',])->group(function() {
            Route::get('me', 'me');
            Route::post('logout', 'logout');
            Route::post('update-profile', 'updateProfile');
            Route::post('change-password', 'changePassword');
        });
    });

    Route::group(['prefix' => 'qurban'], function () {
        Route::group(['prefix' => 'partner', 'controller' => App\Http\Controllers\Api\Qurban\PartnerController::class], function () {
            Route::get('/', 'index');
            Route::get('{id}', 'detail');
            Route::get('{id}/pen', 'getPen');
            Route::get('{id}/price', 'getPrice');
            Route::post('{id}/price/estimation', 'estimationPrice');
            Route::get('{id}/breed', 'getBreed');
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
                Route::post('/register/{id}/save', 'save');
            });

            Route::group(['prefix' => 'contract', 'controller' => App\Http\Controllers\Api\Qurban\ContractController::class], function () {
                Route::post('/', 'contract');
                Route::get('/{id}', 'detail');
            });
        });

        Route::group([
            'middleware' => ['auth:api', 'email.verified', 'farmer', 'check.farm.access' , 'subs.basic_farming'],
        ], function () {
            Route::group([
                'prefix' => 'driver/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\DriverController::class,
            ], function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');
            });

            Route::group([
                'prefix' => 'fleet/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\FleetController::class,
            ], function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');
            });

            Route::group([
                'prefix' => 'customer/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\CustomerController::class,
            ], function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');

                Route::group([
                    'prefix' => '{customer_id}/address',
                ], function(){
                    Route::get('/', 'addressIndex');
                    Route::post('/', 'addressStore');
                    Route::get('{id}', 'addressShow');
                    Route::post('{id}', 'addressUpdate');
                    Route::delete('{id}', 'addressDestroy');
                });
            });

            Route::group([
                'prefix' => 'sales-order/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\SalesOrderController::class,
            ], function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}/detail', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');
            });

            Route::group([
                'prefix' => 'sales-livestock/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\SalesLivestockController::class,
            ], function(){
                Route::get('/available-livestock', 'availableLivestock');
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');
            });

            Route::group([
                'prefix' => 'payment/{farm_id}',
                'controller' => App\Http\Controllers\Api\Qurban\PaymentController::class,
            ], function(){
                Route::get('/', 'index');
                Route::post('/', 'store');
                Route::get('{id}', 'show');
                Route::post('{id}', 'update');
                Route::delete('{id}', 'destroy');
            });
        });
    });

    Route::group(['prefix' => 'data-master', 'controller' => App\Http\Controllers\Api\DataMasterController::class], function () {
        Route::group(['prefix' => 'livestock'], function () {
            Route::get('type', 'getLivestockType');
            Route::get('sex', 'getLivestockSex');
            Route::get('classification', 'getLivestockClassification');
            Route::get('bcs', 'getLivestockBcs');
            Route::get('group', 'getLivestockGroup');
            Route::get('breed', 'getLivestockBreed');
            Route::get('disease', 'getLivestockDisease');
        });

        Route::get('/region', 'getRegion');

        Route::get('bank', 'getBank');

        Route::get('module' , 'getModule');

        Route::get('module/{moduleId}' , 'getModuleDetail');

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
                    Route::get('/{farmId}/user-list', [App\Http\Controllers\Api\FarmController::class, 'userList']);
                    Route::post('/{farmId}/find-user', [App\Http\Controllers\Api\FarmController::class, 'findUser']);
                    Route::get('/{farmId}/list-user', [App\Http\Controllers\Api\FarmController::class, 'listUser']);
                    Route::post('/{farmId}/add-user', [App\Http\Controllers\Api\FarmController::class, 'addUser']);
                    Route::post('/{farmId}/remove-user', [App\Http\Controllers\Api\FarmController::class, 'removeUser']);
                });

                Route::group(['middleware' => ['check.farm.access' , 'subs.basic_farming']], function () {
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

                    Route::post('update-classification/{farm_id}/{data_ud}/update', [App\Http\Controllers\Api\Farming\UpdateClassificationController::class, 'update']);

                    Route::post('update-bcs/{farm_id}/{data_ud}/update', [App\Http\Controllers\Api\Farming\UpdateBcsController::class, 'update']);

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

                    Route::group(['prefix' => 'treatment-individu', 'controller' => App\Http\Controllers\Api\Farming\TreatmentIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{treatmentIndividuId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{treatmentIndividuId}/update', 'update');
                        Route::delete('/{farm_id}/{treatmentIndividuId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-production-individu', 'controller' => App\Http\Controllers\Api\Farming\MilkProductionIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkProductionIndividuId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkProductionIndividuId}/update', 'update');
                        Route::delete('/{farm_id}/{milkProductionIndividuId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-analysis-individu', 'controller' => App\Http\Controllers\Api\Farming\MilkAnalysisIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkAnalysisIndividuId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkAnalysisIndividuId}/update', 'update');
                        Route::delete('/{farm_id}/{milkAnalysisIndividuId}', 'destroy');
                    });

                    Route::group(['prefix' => 'livestock-reweight', 'controller' => App\Http\Controllers\Api\Farming\LivestockReweightController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{reweightId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{reweightId}/update', 'update');
                        Route::delete('/{farm_id}/{reweightId}', 'destroy');
                    });

                    Route::group(['prefix' => 'treatment-schedule-individu', 'controller' => App\Http\Controllers\Api\Farming\TreatmentScheduleIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{dataId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{dataId}/update', 'update');
                        Route::delete('/{farm_id}/{dataId}', 'destroy');
                    });

                    Route::group(['prefix' => 'mutation-individu', 'controller' => App\Http\Controllers\Api\Farming\MutationIndividuController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{dataId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{dataId}/update', 'update');
                        Route::delete('/{farm_id}/{dataId}', 'destroy');
                    });

                    Route::group(['prefix' => 'reproduction'], function () {
                        Route::get('get-female-livestock-data/{farm_id}/{livestock_id}' , [App\Http\Controllers\Api\Farming\ReproductionMasterController::class , 'getFemaleLivestockData']);

                        Route::group(['prefix' => 'artificial-insemination', 'controller' => App\Http\Controllers\Api\Farming\ArtificialInseminationController::class], function () {
                            Route::get('/{farm_id}', 'index');
                            Route::get('/{farm_id}/{dataId}', 'show');
                            Route::post('/{farm_id}', 'store');
                            Route::post('/{farm_id}/{dataId}/update', 'update');
                            Route::delete('/{farm_id}/{dataId}', 'destroy');
                        });

                        Route::group(['prefix' => 'natural-insemination', 'controller' => App\Http\Controllers\Api\Farming\NaturalInseminationController::class], function () {
                            Route::get('/{farm_id}', 'index');
                            Route::get('/{farm_id}/{reweightId}', 'show');
                            Route::post('/{farm_id}', 'store');
                            Route::post('/{farm_id}/{dataId}/update', 'update');
                            Route::delete('/{farm_id}/{dataId}', 'destroy');
                        });

                        Route::group(['prefix' => 'pregnant-check', 'controller' => App\Http\Controllers\Api\Farming\PregnantCheckController::class], function () {
                            Route::get('/{farm_id}', 'index');
                            Route::get('/{farm_id}/{dataId}', 'show');
                            Route::post('/{farm_id}', 'store');
                            Route::post('/{farm_id}/{dataId}/update', 'update');
                            Route::delete('/{farm_id}/{dataId}', 'destroy');
                        });

                        Route::group(['prefix' => 'livestock-birth', 'controller' => App\Http\Controllers\Api\Farming\LivestockBirthController::class], function () {
                            Route::get('/{farm_id}', 'index');
                            Route::get('/{farm_id}/{dataId}', 'show');
                            Route::post('/{farm_id}', 'store');
                            Route::post('/{farm_id}/{dataId}/update', 'update');
                            Route::delete('/{farm_id}/{dataId}', 'destroy');
                            Route::post('/{farm_id}/{dataId}/{child/id}/young-death', 'youngDeath');
                            Route::post('/{farm_id}/{dataId}/{child/id}/identification', 'identification');
                        });
                    });


                    Route::group(['prefix' => 'feeding-colony', 'controller' => App\Http\Controllers\Api\Farming\FeedingColonyController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{feedingColonyId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{feedingColonyId}/update', 'update');
                        Route::delete('/{farm_id}/{feedingColonyId}', 'destroy');
                    });

                    Route::group(['prefix' => 'treatment-colony', 'controller' => App\Http\Controllers\Api\Farming\TreatmentColonyController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{treatmentColonyId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{treatmentColonyId}/update', 'update');
                        Route::delete('/{farm_id}/{treatmentColonyId}', 'destroy');
                    });

                    Route::group(['prefix' => 'mutation-colony', 'controller' => App\Http\Controllers\Api\Farming\MutationColonyController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{dataId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{dataId}/update', 'update');
                        Route::delete('/{farm_id}/{dataId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-production-colony', 'controller' => App\Http\Controllers\Api\Farming\MilkProductionColonyController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkProductionColonyId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkProductionColonyId}/update', 'update');
                        Route::delete('/{farm_id}/{milkProductionColonyId}', 'destroy');
                    });

                    Route::group(['prefix' => 'milk-analysis-colony', 'controller' => App\Http\Controllers\Api\Farming\MilkAnalysisColonyController::class], function () {
                        Route::get('/{farm_id}', 'index');
                        Route::get('/{farm_id}/{milkAnalysisColonyId}', 'show');
                        Route::post('/{farm_id}', 'store');
                        Route::post('/{farm_id}/{milkAnalysisColonyId}/update', 'update');
                        Route::delete('/{farm_id}/{milkAnalysisColonyId}', 'destroy');
                    });
                });
            });
        });
    });
});
