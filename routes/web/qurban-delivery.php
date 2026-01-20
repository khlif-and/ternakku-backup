<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\QurbanDeliveryController;

/*
|--------------------------------------------------------------------------
| Qurban Delivery Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/delivery')
    ->middleware('farmer')
    ->controller(QurbanDeliveryController::class)
    ->name('qurban.delivery.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
