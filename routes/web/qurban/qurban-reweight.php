<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\ReweightController;

/*
|--------------------------------------------------------------------------
| Qurban Reweight Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/reweight')
    ->middleware('farmer')
    ->controller(ReweightController::class)
    ->name('qurban.reweight.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
