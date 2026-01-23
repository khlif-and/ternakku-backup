<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\LivestockDeathController\LivestockDeathController;

Route::prefix('care-livestock/{farm_id}/livestock-death')
    ->middleware('check.farm.access')
    ->controller(LivestockDeathController::class)
    ->name('admin.care-livestock.livestock-death.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
    });
