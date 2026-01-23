<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi\ArtificialInseminasiController;

/*
|--------------------------------------------------------------------------
| Artificial Inseminasi Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/artificial-inseminasi')
    ->middleware('check.farm.access')
    ->controller(ArtificialInseminasiController::class)
    ->name('admin.care_livestock.artificial_inseminasi.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
