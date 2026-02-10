<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\Shared\Reweight\IndexComponent;
use App\Livewire\Shared\Reweight\CreateComponent;
use App\Livewire\Shared\Reweight\EditComponent;
use App\Livewire\Shared\Reweight\ShowComponent;

/*
|--------------------------------------------------------------------------
| Shared Reweight Routes
|--------------------------------------------------------------------------
| Universal routes for Reweight feature, accessible from both 
| Care Livestock and Qurban modules.
*/

Route::prefix('shared/reweight/{farm}')
    ->middleware('farmer')
    ->name('shared.reweight.')
    ->group(function () {
        Route::get('/', IndexComponent::class)->name('index');
        Route::get('/create', CreateComponent::class)->name('create');
        Route::get('/{id}', ShowComponent::class)->name('show');
        Route::get('/{id}/edit', EditComponent::class)->name('edit');
    });
