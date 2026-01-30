<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\PaymentController;

/*
|--------------------------------------------------------------------------
| Qurban Payment Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/payment')
    ->middleware('farmer')
    ->controller(PaymentController::class)
    ->name('admin.qurban.payment.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
    });
