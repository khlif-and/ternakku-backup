<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\Reweight\ReweightController;

Route::prefix('care-livestock/{farm_id}/reweight')
    ->name('admin.care-livestock.reweight.')
    ->group(function () {
        Route::get('/', [ReweightController::class, 'index'])->name('index');
        Route::get('/create', [ReweightController::class, 'create'])->name('create');
        Route::post('/', [ReweightController::class, 'store'])->name('store');
        Route::get('/{reweight}', [ReweightController::class, 'show'])->name('show');
        Route::get('/{reweight}/edit', [ReweightController::class, 'edit'])->name('edit');
        Route::put('/{reweight}', [ReweightController::class, 'update'])->name('update');
        Route::delete('/{reweight}', [ReweightController::class, 'destroy'])->name('destroy');
    });
