<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\CustomerController;

/*
|--------------------------------------------------------------------------
| Qurban Customer Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban/customer')
    ->middleware('farmer')
    ->controller(CustomerController::class)
    ->name('qurban.customer.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{customerId}/edit', 'edit')->name('edit');
        Route::put('{customerId}', 'update')->name('update');
        Route::delete('{customerId}', 'destroy')->name('destroy');
    });

// Customer Address
Route::prefix('qurban/customer/address/{customerId}')
    ->middleware('farmer')
    ->controller(CustomerController::class)
    ->name('qurban.customer.address.')
    ->group(function () {
        Route::get('/', 'indexAddress')->name('index');
        Route::get('create', 'createAddress')->name('create');
        Route::post('/', 'storeAddress')->name('store');
        Route::get('{addressId}/edit', 'editAddress')->name('edit');
        Route::post('{addressId}', 'updateAddress')->name('update');
    });
