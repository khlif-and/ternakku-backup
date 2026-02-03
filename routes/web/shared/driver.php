<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Shared\DriverController;

Route::prefix('shared/driver/{farm}')
    ->middleware('farmer')
    ->name('shared.driver.')
    ->group(function () {
        Route::get('/', [DriverController::class, 'index'])->name('index');
        Route::get('/create', [DriverController::class, 'create'])->name('create');
        Route::get('/{id}', [DriverController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [DriverController::class, 'edit'])->name('edit');
    });
