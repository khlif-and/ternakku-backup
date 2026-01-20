<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\FeedingLivestock\FeedingColonyController;

/*
|--------------------------------------------------------------------------
| Feeding Colony Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/feeding-colony')
    ->middleware('check.farm.access')
    ->controller(FeedingColonyController::class)
    ->name('admin.care-livestock.feeding-colony.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
