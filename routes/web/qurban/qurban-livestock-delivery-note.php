<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\LivestockDeliveryQurban\LivestockDeliveryController;

/*
|--------------------------------------------------------------------------
| Qurban Livestock Delivery Note Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/livestock-delivery-note')
    ->middleware('farmer')
    ->controller(LivestockDeliveryController::class)
    ->name('qurban.livestock-delivery-note.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}', 'show')->name('show');
    });
