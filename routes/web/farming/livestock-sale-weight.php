<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeight\LivestockSaleWeightController;

/*
|--------------------------------------------------------------------------
| Livestock Sale Weight Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/livestock-sale-weight')
    ->middleware('check.farm.access')
    ->controller(LivestockSaleWeightController::class)
    ->name('admin.care-livestock.livestock-sale-weight.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
