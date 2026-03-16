<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Marketing\CustomerController;
use App\Http\Controllers\Marketing\SalesOrderController;

Route::prefix('marketing')->name('marketing.')->group(function () {
    Route::prefix('customer')->name('customer.')->controller(CustomerController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
    });

    Route::prefix('sales-order')->name('sales-order.')->controller(SalesOrderController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}', 'show')->name('show');
    });
});
