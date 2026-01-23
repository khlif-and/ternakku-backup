<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\LiveestockDeliveryNoteController;

/*
|--------------------------------------------------------------------------
| Qurban Livestock Delivery Note Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/livestock-delivery-note')
    ->middleware('farmer')
    ->controller(LiveestockDeliveryNoteController::class)
    ->name('qurban.livestock-delivery-note.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
