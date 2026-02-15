<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\Report\CareLivestock\Pen_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Mutation_Individu_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Artificial_Inseminasi_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Natural_Inseminasi_Report_Controller;

/* |-------------------------------------------------------------------------- | Care Livestock Reports Routes |-------------------------------------------------------------------------- */

// Pen Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/pen-report')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.pen-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\PenReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\Pen\Controllers\PenReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Mutation Individu Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/mutation-individu')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.mutation-individu-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\MutationIndividuReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\MutationIndividu\Controllers\MutationIndividuReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Artificial Inseminasi Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/artificial-inseminasi')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.artificial-inseminasi-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\ArtificialInseminationReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\ArtificialInsemination\Controllers\ArtificialInseminationReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Natural Inseminasi Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/natural-inseminasi')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.natural-inseminasi-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\NaturalInseminationReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\NaturalInsemination\Controllers\NaturalInseminationReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Feed & Medicine Purchase Report (Livewire-based)
// Feed & Medicine Purchase Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/feed-medicine-purchase')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.feed-medicine-purchase-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\FeedMedicinePurchaseReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\FeedMedicinePurchase\Controllers\FeedMedicinePurchaseReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Reweighing Report (Livewire-based)
// Reweighing Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/reweight')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.reweight-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\ReweightReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\Reweight\Controllers\ReweightReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Pregnancy Check Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/pregnancy-check')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.pregnancy-check-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\PregnancyCheckReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\PregnancyCheck\Controllers\PregnancyCheckReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Birth Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/birth')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.birth-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\BirthReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\Birth\Controllers\BirthReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Sales Order Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/sales-order')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.sales-order-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\SalesOrderReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\SalesOrder\Controllers\SalesOrderReportController::class, 'exportPdf'])->name('export-pdf');
    });

// Customer Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/customer')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.customer-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\CustomerReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\Customer\Controllers\CustomerReportController::class, 'exportPdf'])->name('export-pdf');
    });

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

// Qurban Population Report (Livewire-based)
Route::prefix('care-livestock/{farm_id}/report/qurban-population')
    ->middleware('check.farm.access')
    ->name('admin.care-livestock.qurban-population-report.')
    ->group(function () {
        Route::get('/', \App\Livewire\Reports\CareLivestock\QurbanPopulationReport\Index::class)->name('index');
        Route::get('/export-pdf', [\App\Services\Web\Report\QurbanPopulation\Controllers\QurbanPopulationReportController::class, 'exportPdf'])->name('export-pdf');
    });

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
