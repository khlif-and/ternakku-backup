<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\PopulationReportController;

/*
|--------------------------------------------------------------------------
| Qurban Population Report Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/population-report')
    ->middleware('farmer')
    ->controller(PopulationReportController::class)
    ->name('qurban.population-report.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
