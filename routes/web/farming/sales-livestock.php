<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\SalesLivestock\SalesLivestockController;

/*
|--------------------------------------------------------------------------
| Sales Livestock Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/sales-livestock')
    ->middleware('check.farm.access')
    ->controller(SalesLivestockController::class)
    ->name('admin.care-livestock.sales-livestock.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}', 'show')->name('show');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
