<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\UpdateClassification\LivestockClassificationController;

/*
|--------------------------------------------------------------------------
| Livestock Classification Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/classification')
    ->middleware('check.farm.access')
    ->controller(LivestockClassificationController::class)
    ->name('admin.care-livestock.classification.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
    });
