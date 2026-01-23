<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\MutationLivestock\MutationIndividuController;

/*
|--------------------------------------------------------------------------
| Mutation Individu Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/mutation-individu')
    ->middleware('check.farm.access')
    ->controller(MutationIndividuController::class)
    ->name('admin.care-livestock.mutation-individu.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
