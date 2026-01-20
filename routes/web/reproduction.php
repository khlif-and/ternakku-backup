<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\Reproduction\ReproductionMasterController;

/*
|--------------------------------------------------------------------------
| Reproduction Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/reproduction')
    ->middleware('check.farm.access')
    ->controller(ReproductionMasterController::class)
    ->name('admin.care_livestock.reproduction.')
    ->group(function () {
        Route::get('/{livestock_id}/female', 'getFemaleLivestockData')->name('female.show');
    });
