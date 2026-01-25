<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi\ArtificialInseminationController;

Route::prefix('care-livestock/{farm_id}/artificial-inseminasi')
    ->middleware('check.farm.access')
    ->controller(ArtificialInseminationController::class)
    ->name('admin.care-livestock.artificial-inseminasi.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });