<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\FarmUser\FarmUserController;

/*
|--------------------------------------------------------------------------
| Farm Users Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/farm-users')
    ->middleware('check.farm.access')
    ->controller(FarmUserController::class)
    ->name('admin.care-livestock.farm-users.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::get('/{id}', 'show')->name('show');
    });
