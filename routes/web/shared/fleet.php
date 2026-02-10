<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Shared\FleetController;

Route::prefix('shared/fleet/{farm}')
    ->middleware('farmer')
    ->name('shared.fleet.')
    ->group(function () {
        Route::get('/', [FleetController::class, 'index'])->name('index');
        Route::get('/create', [FleetController::class, 'create'])->name('create');
        Route::get('/{id}', [FleetController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [FleetController::class, 'edit'])->name('edit');
    });
