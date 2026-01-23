<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\SalesQurbanController;

/*
|--------------------------------------------------------------------------
| Sales Qurban Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/sales-qurban')
    ->middleware('farmer')
    ->controller(SalesQurbanController::class)
    ->name('qurban.sales.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
