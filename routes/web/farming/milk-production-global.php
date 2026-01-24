<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\MilkProductionGlobal\MilkProductionGlobalController;

/*
|--------------------------------------------------------------------------
| Milk Production Global Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/milk-production-global')
    ->middleware('check.farm.access')
    ->controller(MilkProductionGlobalController::class)
    ->name('admin.care-livestock.milk-production-global.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });