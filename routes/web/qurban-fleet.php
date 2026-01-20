<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\FleetController;

/*
|--------------------------------------------------------------------------
| Qurban Fleet Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/fleet')
    ->middleware('farmer')
    ->controller(FleetController::class)
    ->name('qurban.fleet.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{fleetId}/edit', 'edit')->name('edit');
        Route::put('{fleetId}', 'update')->name('update');
        Route::delete('{fleetId}', 'destroy')->name('destroy');
    });
