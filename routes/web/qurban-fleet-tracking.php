<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\FleetTrackingController;

/*
|--------------------------------------------------------------------------
| Qurban Fleet Tracking Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/fleet-tracking')
    ->middleware('farmer')
    ->controller(FleetTrackingController::class)
    ->name('qurban.fleet-tracking.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
