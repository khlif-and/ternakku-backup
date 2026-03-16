<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'email.verified', 'farmer'])->prefix('qurban/report')->group(function () {

    // Sales Order Report
    Route::get('sales-order', \App\Livewire\Reports\CareLivestock\SalesOrderReport\Index::class);

    // Sales Livestock Report
    Route::get('sales-livestock', \App\Livewire\Reports\CareLivestock\QurbanSalesLivestock\Index::class);

    // Payment Report
    Route::get('payment', \App\Livewire\Reports\CareLivestock\QurbanPaymentReport\Index::class)->name('qurban-payment-report.index');
    Route::get('payment/export', [\App\Services\Web\Report\QurbanPayment\Controllers\QurbanPaymentReportController::class, 'exportPdf'])->name('qurban.report.payment.export');

    // Cancelation Report
    Route::get('cancelation', \App\Livewire\Reports\Qurban\CancelationReport\Index::class)->name('qurban-cancelation-report.index');
    Route::get('cancelation/export', [\App\Services\Web\Report\QurbanCancelation\Controllers\QurbanCancelationReportController::class, 'exportPdf'])->name('qurban.report.cancelation.export');

    // Surat Jalan Report
    Route::get('delivery-order', \App\Livewire\Reports\Qurban\DeliveryOrderReport\Index::class)->name('qurban-delivery-order-report.index');
    Route::get('delivery-order/export', [\App\Services\Web\Report\QurbanDeliveryOrder\Controllers\QurbanDeliveryOrderReportController::class, 'exportPdf'])->name('qurban.report.delivery-order.export');

    // Delivery Report (Pengiriman Hewan)
    Route::get('delivery', \App\Livewire\Reports\Qurban\DeliveryReport\Index::class)->name('qurban-delivery-report.index');
    Route::get('delivery/export', [\App\Services\Web\Report\QurbanDelivery\Controllers\QurbanDeliveryReportController::class, 'exportPdf'])->name('qurban.report.delivery.export');
    Route::get('delivery/export-detailed', [\App\Services\Web\Report\QurbanDetailedDelivery\Controllers\QurbanDetailedDeliveryReportController::class, 'exportPdf'])->name('qurban.report.delivery.export-detailed');

    // Detailed Delivery Report Page
    Route::get('delivery-detail', \App\Livewire\Reports\Qurban\DetailedDeliveryReport\Index::class)->name('qurban-detailed-delivery-report.index');

    Route::get('reception', \App\Livewire\Reports\Qurban\ReceptionReport\Index::class)->name('qurban-reception-report.index');
    Route::get('reception/export', [\App\Services\Web\Report\QurbanReception\Controllers\QurbanReceptionReportController::class, 'exportPdf'])->name('qurban.report.reception.export');
});
