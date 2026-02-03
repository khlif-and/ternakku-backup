<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\Auth\LoginController;
use App\Http\Controllers\Driver\DashboardController;
use App\Http\Controllers\Driver\DeliveryController;

Route::prefix('driver')->name('driver.')->group(function () {

    Route::middleware('guest')->group(function () {
        Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [LoginController::class, 'login']);
    });

    Route::middleware('auth')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::prefix('delivery')->name('delivery.')->controller(DeliveryController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/{id}', 'show')->name('show');
        });
    });
});
