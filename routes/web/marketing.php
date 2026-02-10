<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Marketing\Auth\LoginController;
use App\Http\Controllers\Marketing\DashboardController;
use App\Http\Controllers\Marketing\CustomerController;
use App\Http\Controllers\Marketing\SalesOrderController;

Route::prefix('marketing')->name('marketing.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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
});
