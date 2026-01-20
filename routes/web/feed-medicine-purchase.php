<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\FeedMedicinePurchase\FeedMedicinePurchaseController;

/*
|--------------------------------------------------------------------------
| Feed Medicine Purchase Routes
|--------------------------------------------------------------------------
*/

Route::prefix('care-livestock/{farm_id}/feed-medicine-purchase')
    ->middleware('check.farm.access')
    ->controller(FeedMedicinePurchaseController::class)
    ->name('admin.care-livestock.feed-medicine-purchase.')
    ->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{id}', 'show')->name('show');
        Route::get('/{id}/edit', 'edit')->name('edit');
        Route::put('/{id}', 'update')->name('update');
        Route::delete('/{id}', 'destroy')->name('destroy');
    });
