<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController;

/*
|--------------------------------------------------------------------------
| Customer (Care Livestock) Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/customer')
    ->middleware('check.farm.access')
    ->controller(CustomerController::class)
    ->name('admin.care-livestock.customer.')
    ->group(function () {
        
        // Customer CRUD
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');

        // Customer Address
        Route::get('/{customer_id}/address', 'addressIndex')->name('address.index');
        Route::get('/{customer_id}/address/create', 'addressCreate')->name('address.create');
        Route::post('/{customer_id}/address', 'addressStore')->name('address.store');
        Route::get('/{customer_id}/address/{id}/edit', 'addressEdit')->name('address.edit');
        Route::put('/{customer_id}/address/{id}', 'addressUpdate')->name('address.update');
        Route::delete('/{customer_id}/address/{id}', 'addressDestroy')->name('address.destroy');
    });
