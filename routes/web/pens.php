<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\PenController;

/*
|--------------------------------------------------------------------------
| Pens Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/pens')
    ->middleware('check.farm.access')
    ->controller(PenController::class)
    ->name('admin.care-livestock.pens.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{pen_id}/edit', 'edit')->name('edit');
        Route::get('/{pen_id}', 'show')->name('show');
        Route::put('/{pen_id}', 'update')->name('update');
        Route::delete('/{pen_id}', 'destroy')->name('destroy');
    });
