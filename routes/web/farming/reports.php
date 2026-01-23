<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Report\CareLivestock\Pen_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Mutation_Individu_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Artificial_Inseminasi_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Natural_Inseminasi_Report_Controller;

/*
|--------------------------------------------------------------------------
| Care Livestock Reports Routes
|--------------------------------------------------------------------------
*/

// Pen Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/pen', [Pen_Report_Controller::class, 'index'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.pen-report.index');

// Mutation Individu Report
Route::prefix('care-livestock/{farm_id}/report/mutation-individu')
    ->middleware('check.farm.access')
    ->controller(Mutation_Individu_Report_Controller::class)
    ->name('admin.care-livestock.mutation-individu-report.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::get('/export', 'exportPdf')->name('export');
    });

// Artificial Inseminasi Report
Route::prefix('care-livestock/{farm_id}/report/artificial-inseminasi')
    ->middleware('check.farm.access')
    ->controller(Artificial_Inseminasi_Report_Controller::class)
    ->name('admin.care-livestock.artificial-inseminasi-report.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::get('/export', 'exportPdf')->name('export');
    });

// Natural Inseminasi Report
Route::prefix('care-livestock/{farm_id}/report/natural-inseminasi')
    ->middleware('check.farm.access')
    ->controller(Natural_Inseminasi_Report_Controller::class)
    ->name('admin.care-livestock.natural-inseminasi-report.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/detail', 'detail')->name('detail');
        Route::get('/export', 'exportPdf')->name('export');
    });
