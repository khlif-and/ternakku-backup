<?php

    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\HomeController;
    use App\Http\Controllers\Admin\CareLivestock\CareLivestockController;
    use App\Http\Controllers\Admin\LivestockOutlet\LivestockOutletController;

    /*
    |--------------------------------------------------------------------------
    | Web Routes
    |--------------------------------------------------------------------------
    */

    Route::get('/', [HomeController::class, 'index']);

// Authentication Routes (separated file)
    require __DIR__.'/web/auth.php';

    Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('dashboard');

    // Farm Routes (separated file)
    require __DIR__.'/web/farm.php';

    /*
    |--------------------------------------------------------------------------
    | Qurban Module (separated files)
    |--------------------------------------------------------------------------
    */
    require __DIR__.'/web/qurban.php';
    require __DIR__.'/web/qurban-customer.php';
    require __DIR__.'/web/qurban-fleet.php';
    require __DIR__.'/web/qurban-driver.php';
    require __DIR__.'/web/qurban-reweight.php';
    require __DIR__.'/web/qurban-payment.php';
    require __DIR__.'/web/qurban-delivery.php';
    require __DIR__.'/web/qurban-fleet-tracking.php';
    require __DIR__.'/web/qurban-livestock-delivery-note.php';
    require __DIR__.'/web/qurban-delivery-order-data.php';
    require __DIR__.'/web/qurban-population-report.php';
    require __DIR__.'/web/qurban-sales.php';
    require __DIR__.'/web/qurban-cancelation.php';

    /*
    |--------------------------------------------------------------------------
    | Care Livestock Module
    |--------------------------------------------------------------------------
    */
    Route::get('care-livestock', [CareLivestockController::class, 'index'])->name('care_livestock');
    Route::get('care-livestock/dashboard', function () {
        $farmId = session('selected_farm');
        if (!$farmId) return redirect()->route('care_livestock');
        return redirect()->route('admin.care-livestock.dashboard', ['farm_id' => $farmId]);
    })->name('care_livestock.shortcut_dashboard');

    Route::get('care-livestock/{farm_id}/dashboard', [CareLivestockController::class, 'dashboard'])
        ->name('admin.care-livestock.dashboard');

    /*
    |--------------------------------------------------------------------------
    | Separated Route Files (Care Livestock Modules)
    |--------------------------------------------------------------------------
    */
    require __DIR__.'/web/pens.php';
    require __DIR__.'/web/reports.php';
    require __DIR__.'/web/livestock-reception.php';
    require __DIR__.'/web/sales-order.php';
    require __DIR__.'/web/sales-livestock.php';
    require __DIR__.'/web/livestock-sale-weight.php';
    require __DIR__.'/web/livestock-death.php';
    require __DIR__.'/web/feed-medicine-purchase.php';
    require __DIR__.'/web/milk-production-global.php';
    require __DIR__.'/web/milk-analysis-colony.php';
    require __DIR__.'/web/milk-analysis-global.php';
    require __DIR__.'/web/feeding-individu.php';
    require __DIR__.'/web/feeding-colony.php';
    require __DIR__.'/web/treatment-individu.php';
    require __DIR__.'/web/treatment-colony.php';
    require __DIR__.'/web/treatment-schedule-individu.php';
    require __DIR__.'/web/mutation-individu.php';
    require __DIR__.'/web/artificial-inseminasi.php';
    require __DIR__.'/web/natural-insemination.php';
    require __DIR__.'/web/pregnant-check.php';
    require __DIR__.'/web/reproduction.php';
    require __DIR__.'/web/birth.php';
    require __DIR__.'/web/milk-production-individu.php';
    require __DIR__.'/web/milk-analysis-individu.php';
    require __DIR__.'/web/classification.php';
    require __DIR__.'/web/customer.php';

    /*
    |--------------------------------------------------------------------------
    | Livestock Outlet
    |--------------------------------------------------------------------------
    */
    Route::get('admin/livestock-outlet/dashboard', [LivestockOutletController::class, 'dashboard'])
        ->name('livestock_outlet.dashboard');
});
