<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\CancelationQurbanController;

/*
|--------------------------------------------------------------------------
| Qurban Cancelation Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/cancelation-qurban')
    ->middleware('farmer')
    ->controller(CancelationQurbanController::class)
    ->name('qurban.cancelation.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
    });
