<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\CareLivestock\CareLivestockController;
use App\Http\Controllers\Admin\LivestockOutlet\LivestockOutletController;

Route::get('/', [HomeController::class, 'index']);

require __DIR__.'/web/auth.php';

Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('dashboard');

    require __DIR__.'/web/farm.php';

    require __DIR__.'/web/qurban/qurban.php';
    require __DIR__.'/web/qurban/qurban-customer.php';
    require __DIR__.'/web/qurban/qurban-fleet.php';
    require __DIR__.'/web/qurban/qurban-driver.php';
    require __DIR__.'/web/qurban/qurban-reweight.php';
    require __DIR__.'/web/qurban/qurban-payment.php';
    require __DIR__.'/web/qurban/qurban-delivery.php';
    require __DIR__.'/web/qurban/qurban-fleet-tracking.php';
    require __DIR__.'/web/qurban/qurban-livestock-delivery-note.php';
    require __DIR__.'/web/qurban/qurban-delivery-order-data.php';
    require __DIR__.'/web/qurban/qurban-population-report.php';
    require __DIR__.'/web/qurban/qurban-sales.php';
    require __DIR__.'/web/qurban/qurban-cancelation.php';

    Route::get('care-livestock', [CareLivestockController::class, 'index'])->name('care_livestock');
    Route::get('care-livestock/dashboard', function () {
        $farmId = session('selected_farm');
        if (!$farmId) return redirect()->route('care_livestock');
        return redirect()->route('admin.care-livestock.dashboard', ['farm_id' => $farmId]);
    })->name('care_livestock.shortcut_dashboard');

    Route::get('care-livestock/{farm_id}/dashboard', [CareLivestockController::class, 'dashboard'])
        ->name('admin.care-livestock.dashboard');

    require __DIR__.'/web/farming/pens.php';
    require __DIR__.'/web/farming/reports.php';
    require __DIR__.'/web/farming/livestock-reception.php';
    require __DIR__.'/web/farming/sales-order.php';
    require __DIR__.'/web/farming/sales-livestock.php';
    require __DIR__.'/web/farming/livestock-sale-weight.php';
    require __DIR__.'/web/farming/livestock-death.php';
    require __DIR__.'/web/farming/feed-medicine-purchase.php';
    require __DIR__.'/web/farming/milk-production-global.php';
    require __DIR__.'/web/farming/milk-analysis-colony.php';
    require __DIR__.'/web/farming/milk-analysis-global.php';
    require __DIR__.'/web/farming/feeding-individu.php';
    require __DIR__.'/web/farming/feeding-colony.php';
    require __DIR__.'/web/farming/treatment-individu.php';
    require __DIR__.'/web/farming/treatment-colony.php';
    require __DIR__.'/web/farming/treatment-schedule-individu.php';
    require __DIR__.'/web/farming/mutation-individu.php';
    require __DIR__.'/web/farming/artificial-insemination.php';
    require __DIR__.'/web/farming/natural-insemination.php';
    require __DIR__.'/web/farming/pregnant-check.php';
    require __DIR__.'/web/farming/reproduction.php';
    require __DIR__.'/web/farming/birth.php';
    require __DIR__.'/web/farming/milk-production-individu.php';
    require __DIR__.'/web/farming/milk-analysis-individu.php';
    require __DIR__.'/web/farming/classification.php';
    require __DIR__.'/web/farming/customer.php';

    Route::get('admin/livestock-outlet/dashboard', [LivestockOutletController::class, 'dashboard'])
        ->name('livestock_outlet.dashboard');
});