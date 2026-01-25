<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi\NaturalInseminationController;

/*
|--------------------------------------------------------------------------
| Natural Insemination Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/natural-inseminasi') // Ubah insemination -> inseminasi
    ->middleware('check.farm.access')
    ->controller(NaturalInseminationController::class)
    ->name('admin.care-livestock.natural-inseminasi.') // Gunakan dash '-' dan 'inseminasi'
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
