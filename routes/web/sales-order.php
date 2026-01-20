<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\SalesOrder\SalesOrderController;

/*
|--------------------------------------------------------------------------
| Sales Order Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/sales-order')
    ->middleware('check.farm.access')
    ->controller(SalesOrderController::class)
    ->name('admin.care-livestock.sales-order.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
