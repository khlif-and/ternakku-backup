<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\MilkAnalysisIndividu\MilkAnalysisIndividuController;

/*
|--------------------------------------------------------------------------
| Milk Analysis Individu Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/milk-analysis-individu')
    ->middleware('check.farm.access')
    ->controller(MilkAnalysisIndividuController::class)
    ->name('admin.care-livestock.milk-analysis-individu.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
