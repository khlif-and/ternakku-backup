<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\PregnantCheck\PregnantCheckController;

/*
|--------------------------------------------------------------------------
| Pregnant Check Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/pregnant-check')
    ->middleware('check.farm.access')
    ->controller(PregnantCheckController::class)
    ->name('admin.care_livestock.pregnant_check.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
