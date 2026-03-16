<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Driver\DeliveryController;

Route::prefix('driver')->name('driver.')->group(function () {
    Route::prefix('delivery')->name('delivery.')->controller(DeliveryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/{id}', 'show')->name('show');
    });
});
