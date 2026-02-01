<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\QurbanDelivery\QurbanDeliveryController;

/*
|--------------------------------------------------------------------------
| Qurban Delivery Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/delivery')
    ->middleware('farmer')
    ->controller(QurbanDeliveryController::class)
    ->name('admin.qurban.qurban_delivery.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
