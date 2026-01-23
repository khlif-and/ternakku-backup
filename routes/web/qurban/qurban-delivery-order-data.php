<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\QurbanDeliveryOrderDataController;

/*
|--------------------------------------------------------------------------
| Qurban Delivery Order Data Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/qurban-delivery-order-data')
    ->middleware('farmer')
    ->controller(QurbanDeliveryOrderDataController::class)
    ->name('qurban.delivery-order-data.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
