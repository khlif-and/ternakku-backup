<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Qurban\DashboardController;
use App\Http\Controllers\Admin\FarmController;
use App\Http\Controllers\Admin\CareLivestock\SalesOrder\SalesOrderController;

/*
|--------------------------------------------------------------------------
| Qurban Dashboard & Core Routes
|--------------------------------------------------------------------------
*/

Route::prefix('qurban')->middleware('farmer')->group(function () {
    
    // Dashboard
    Route::get('dashboard', [DashboardController::class, 'dashboard'])->name('qurban.dashboard');
    
    // Farm User Management
    Route::prefix('farm')
        ->controller(FarmController::class)
        ->name('qurban.farm.')
        ->group(function () {
            Route::get('find-user', 'findUser')->name('find-user');
            Route::get('user-list', 'userList')->name('user-list');
            Route::post('add-user', 'addUser')->name('add-user');
            Route::get('user-list/create', 'userCreate')->name('user-create');
        });

    // Sales Order (Qurban)
    Route::prefix('sales-order')
        ->controller(SalesOrderController::class)
        ->name('qurban.sales-order.')
        ->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
            Route::get('{salesOrderId}/edit', 'edit')->name('edit');
            Route::put('{salesOrderId}', 'update')->name('update');
            Route::delete('{salesOrderId}', 'destroy')->name('destroy');
        });
});
