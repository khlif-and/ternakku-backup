<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\TreatmentSchedule\TreatmentScheduleIndividuController;

/*
|--------------------------------------------------------------------------
| Treatment Schedule Individu Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/treatment-schedule-individu')
    ->middleware('check.farm.access')
    ->controller(TreatmentScheduleIndividuController::class)
    ->name('admin.care-livestock.treatment-schedule-individu.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
