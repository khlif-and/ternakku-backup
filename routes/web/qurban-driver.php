<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\DriverController;

/*
|--------------------------------------------------------------------------
| Qurban Driver Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/driver')
    ->middleware('farmer')
    ->controller(DriverController::class)
    ->name('qurban.driver.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
    });
