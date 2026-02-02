<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\DeliveryOrderQurban\DeliveryOrderQurbanController;

/*
|--------------------------------------------------------------------------
| Qurban Delivery Order Data Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/delivery-order-qurban')
    ->middleware('farmer')
    ->controller(DeliveryOrderQurbanController::class)
    ->name('admin.qurban.delivery_order_qurban.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
