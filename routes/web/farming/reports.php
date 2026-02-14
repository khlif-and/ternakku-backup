<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Report\CareLivestock\Pen_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Mutation_Individu_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Artificial_Inseminasi_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Natural_Inseminasi_Report_Controller;

/* |-------------------------------------------------------------------------- | Care Livestock Reports Routes |-------------------------------------------------------------------------- */

// Pen Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/pen', [Pen_Report_Controller::class, 'index'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.pen-report.index');

// Mutation Individu Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/mutation-individu', \App\Livewire\Reports\CareLivestock\MutationIndividuReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.mutation-individu-report.index');

// Artificial Inseminasi Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/artificial-inseminasi', \App\Livewire\Reports\CareLivestock\ArtificialInseminasiReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.artificial-inseminasi-report.index');

// Natural Inseminasi Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/natural-inseminasi', \App\Livewire\Reports\CareLivestock\NaturalInseminasiReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.natural-inseminasi-report.index');

// Feed & Medicine Purchase Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/feed-medicine-purchase', \App\Livewire\Reports\CareLivestock\FeedMedicinePurchaseReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feed-medicine-purchase-report.index');

// Reweighing Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/reweight', \App\Livewire\Reports\CareLivestock\ReweightReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.reweight-report.index');

// Pregnancy Check Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/pregnant-check', \App\Livewire\Reports\CareLivestock\PregnantCheckReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.pregnant-check-report.index');

// Birth Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/birth', \App\Livewire\Reports\CareLivestock\BirthReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.birth-report.index');

// Sales Order Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/sales-order', \App\Livewire\Reports\CareLivestock\SalesOrderReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.sales-order-report.index');

// Customer Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/customer', \App\Livewire\Reports\CareLivestock\CustomerReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.customer-report.index');

// Feeding Colony Supply Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/feeding-colony-supply', \App\Livewire\Reports\CareLivestock\FeedingColonySupplyReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-colony-supply-report.index');

Route::get('care-livestock/{farm_id}/report/feeding-colony-supply/export-pdf', [\App\Services\Web\Report\FeedingColony\Controllers\FeedingColonyReportController::class, 'exportPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-colony-supply-report.export-pdf');

Route::get('care-livestock/{farm_id}/report/feeding-colony-supply/export-pdf/{id}', [\App\Services\Web\Report\FeedingColony\Controllers\FeedingColonyReportController::class, 'exportRowPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-colony-supply-report.export-row-pdf');

// Feeding Individu Supply Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/feeding-individu-supply', \App\Livewire\Reports\CareLivestock\FeedingIndividuSupplyReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-individu-supply-report.index');

Route::get('care-livestock/{farm_id}/report/feeding-individu-supply/export-pdf', [\App\Services\Web\Report\FeedingIndividu\Controllers\FeedingIndividuReportController::class, 'exportPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-individu-supply-report.export-pdf');

Route::get('care-livestock/{farm_id}/report/feeding-individu-supply/export-pdf/{id}', [\App\Services\Web\Report\FeedingIndividu\Controllers\FeedingIndividuReportController::class, 'exportRowPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feeding-individu-supply-report.export-row-pdf');

// Treatment Colony Report (Livewire-based)
Route::get('care-livestock/{farm_id}/report/treatment-colony', \App\Livewire\Reports\CareLivestock\TreatmentColonyReport\Index::class)
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.treatment-colony-report.index');

Route::get('care-livestock/{farm_id}/report/treatment-colony/export-pdf', [\App\Services\Web\Report\TreatmentColony\Controllers\TreatmentColonyReportController::class, 'exportPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.treatment-colony-report.export-pdf');

Route::get('care-livestock/{farm_id}/report/treatment-colony/export-pdf/{id}', [\App\Services\Web\Report\TreatmentColony\Controllers\TreatmentColonyReportController::class, 'exportRowPdf'])
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.treatment-colony-report.export-row-pdf');

// Treatment Individu Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/treatment-individu')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.treatment-individu-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\TreatmentIndividuReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\TreatmentIndividu\Controllers\TreatmentIndividuReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-row-pdf/{id}', [\App\Services\Web\Report\TreatmentIndividu\Controllers\TreatmentIndividuReportController::class, 'exportRowPdf'])->name('export-row-pdf');
    });


Route::prefix('care-livestock/{farm_id}/report/milk-production-global')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.milk-production-global-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\MilkProductionGlobalReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\MilkProductionGlobal\Controllers\MilkProductionGlobalReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-row-pdf/{id}', [\App\Services\Web\Report\MilkProductionGlobal\Controllers\MilkProductionGlobalReportController::class, 'exportRowPdf'])->name('export-row-pdf');
    });

// Milk Production Individu Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/milk-production-individu')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.milk-production-individu-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\MilkProductionIndividuReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\MilkProductionIndividu\Controllers\MilkProductionIndividuReportController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/export-row-pdf/{id}', [\App\Services\Web\Report\MilkProductionIndividu\Controllers\MilkProductionIndividuReportController::class, 'exportRowPdf'])->name('export-row-pdf');
    });

// Milk Analysis Global Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/milk-analysis-global')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.milk-analysis-global-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\MilkAnalysisGlobalReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\MilkAnalysisGlobal\Controllers\MilkAnalysisGlobalReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Milk Analysis Individu Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/milk-analysis-individu')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.milk-analysis-individu-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\MilkAnalysisIndividuReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\MilkAnalysisIndividu\Controllers\MilkAnalysisIndividuReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Sales Livestock Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/sales-livestock')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.sales-livestock-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\SalesLivestockReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\SalesLivestock\Controllers\SalesLivestockReportController::class, 'exportPdf'])->name('export-pdf');
    });
