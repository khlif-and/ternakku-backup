<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\FarmController;

/*
|--------------------------------------------------------------------------
| Farm Management Routes
|--------------------------------------------------------------------------
*/

Route::get('select-farm', [FarmController::class, 'selectFarm']);
Route::post('select-farm', [FarmController::class, 'selectFarmStore']);
Route::get('create-farm', [FarmController::class, 'create'])->name('farm.create');
Route::post('create-farm', [FarmController::class, 'store'])->name('farm.store');
