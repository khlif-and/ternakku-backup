<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'email.verified', 'farmer'])->prefix('qurban/report')->group(function () {

    // Sales Order Report
    Route::get('sales-order', \App\Livewire\Reports\CareLivestock\SalesOrderReport\Index::class);

});
