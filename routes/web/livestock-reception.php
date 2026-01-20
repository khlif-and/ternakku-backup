<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\CareLivestock\LivestockReception\LivestockReceptionController;

/*
|--------------------------------------------------------------------------
| Livestock Reception Routes
|--------------------------------------------------------------------------
|
| Routes untuk modul penerimaan ternak (Livestock Reception)
| Prefix: care-livestock/{farm_id}/livestock-reception
| Note: store, update, destroy handled by Livewire components
|
*/

Route::prefix('care-livestock/{farm_id}/livestock-reception')
    ->middleware('check.farm.access')
    ->controller(LivestockReceptionController::class)
    ->name('admin.care-livestock.livestock-reception.')
    ->group(function () {
        
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/{id}/edit', 'edit')->name('edit');
        
    });
