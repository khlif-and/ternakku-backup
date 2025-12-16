<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\FarmController;
use App\Http\Controllers\Admin\Qurban\DashboardController;
use App\Http\Controllers\Admin\Qurban\CustomerController;
use App\Http\Controllers\Admin\Qurban\FleetController;
use App\Http\Controllers\Admin\Qurban\DriverController;
use App\Http\Controllers\Admin\Qurban\ReweightController;
use App\Http\Controllers\Admin\Qurban\PaymentController;
use App\Http\Controllers\Admin\Qurban\QurbanDeliveryController;
use App\Http\Controllers\Admin\Qurban\FleetTrackingController;
use App\Http\Controllers\Admin\Qurban\LiveestockDeliveryNoteController;
use App\Http\Controllers\Admin\Qurban\QurbanDeliveryOrderDataController;
use App\Http\Controllers\Admin\Qurban\PopulationReportController;
use App\Http\Controllers\Admin\CareLivestock\CareLivestockController;
use App\Http\Controllers\Admin\Qurban\SalesQurbanController;
use App\Http\Controllers\Admin\Qurban\CancelationQurbanController;
use App\Http\Controllers\Admin\CareLivestock\PenController;
use App\Http\Controllers\Admin\CareLivestock\LivestockReceptionController;
use App\Http\Controllers\Admin\CareLivestock\SaledLivestock\SaledLivestockController;

use App\Http\Controllers\Admin\CareLivestock\FeedMedicinePurchaseController;
use App\Http\Controllers\Admin\CareLivestock\MilkProductionGlobalController;
use App\Http\Controllers\Admin\CareLivestock\MilkAnalysisColonyController;
use App\Http\Controllers\Admin\CareLivestock\MilkAnalysisGlobalController;
use App\Http\Controllers\Admin\CareLivestock\FeedingLivestock\FeedingIndividuController;
use App\Http\Controllers\Admin\CareLivestock\FeedingLivestock\FeedingColonyController;
use App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock\TreatmentIndividuController;
use App\Http\Controllers\Admin\CareLivestock\TreatmentLivestock\TreatmentColonyController;
use App\Http\Controllers\Admin\CareLivestock\TreatmentSchedule\TreatmentScheduleIndividuController;
use App\Http\Controllers\Admin\CareLivestock\MutationLivestock\MutationIndividuController;
use App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi\ArtificialInseminasiController;
use App\Http\Controllers\Admin\CareLivestock\ArtificialInseminasi\NaturalInseminationController;
use App\Http\Controllers\Admin\CareLivestock\PregnantCheck\PregnantCheckController;
use App\Http\Controllers\Admin\CareLivestock\Reproduction\ReproductionMasterController;
use App\Http\Controllers\Admin\CareLivestock\LivestockBirthController\LivestockBirthController;
use App\Http\Controllers\Admin\CareLivestock\MilkProductionIndividu\MilkProductionIndividuController;
use App\Http\Controllers\Admin\CareLivestock\MilkAnalysisIndividu\MilkAnalysisIndividuController;
use App\Http\Controllers\Admin\CareLivestock\UpdateClassification\LivestockClassificationController;
use App\Http\Controllers\Admin\LivestockOutlet\LivestockOutletController;
use App\Http\Controllers\Admin\Report\CareLivestock\Pen_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Mutation_Individu_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Artificial_Inseminasi_Report_Controller;
use App\Http\Controllers\Admin\Report\CareLivestock\Natural_Inseminasi_Report_Controller;
use App\Http\Controllers\Admin\CareLivestock\SalesOrder\SalesOrderController;


Route::get('/', [HomeController::class, 'index']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('login', 'showLoginForm');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
    Route::get('register', 'showRegisterForm')->name('register.form');
    Route::post('register', 'register')->name('register');
    Route::get('verify-phone', function () {
        return view('admin.auth.verify-phone');
    })->name('verify.phone');
    Route::post('verify-otp', 'verifyOtp')->name('otp.verify');
    Route::post('resend-otp', [AuthController::class, 'resendOtp'])->name('otp.resend');
});

Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('dashboard', [\App\Http\Controllers\Admin\MenuController::class, 'index'])->name('dashboard');


    Route::get('select-farm', [FarmController::class, 'selectFarm']);
    Route::post('select-farm', [FarmController::class, 'selectFarmStore']);
    Route::get('create-farm', [FarmController::class, 'create'])->name('farm.create');
    Route::post('create-farm', [FarmController::class, 'store'])->name('farm.store');

    Route::prefix('qurban')->middleware('farmer')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'dashboard']);
        Route::prefix('farm')->controller(FarmController::class)->group(function () {
            Route::get('find-user', 'findUser');
            Route::get('user-list', 'userList');
            Route::post('add-user', 'addUser');
            Route::get('user-list/create', 'userCreate');
        });
        Route::prefix('customer')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('/', 'store');
            Route::get('{customerId}/edit', 'edit');
            Route::put('{customerId}', 'update');
            Route::delete('{customerId}', 'destroy');
        });
        Route::prefix('customer/address/{customerId}')->controller(CustomerController::class)->group(function () {
            Route::get('/', 'indexAddress');
            Route::get('create', 'createAddress');
            Route::post('/', 'storeAddress');
            Route::get('{addressId}/edit', 'editAddress');
            Route::post('{addressId}', 'updateAddress');
        });
        Route::prefix('fleet')->controller(FleetController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('/', 'store');
            Route::get('{fleetId}/edit', 'edit');
            Route::put('{fleetId}', 'update');
            Route::delete('{fleetId}', 'destroy');
        });
        Route::prefix('driver')->controller(DriverController::class)->group(function () {
            Route::get('/', 'index');
        });
        Route::prefix('sales-order')->controller(SalesOrderController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('/', 'store');
            Route::get('{salesOrderId}/edit', 'edit');
            Route::put('{salesOrderId}', 'update');
            Route::delete('{salesOrderId}', 'destroy');
        });

        Route::prefix('reweight')->controller(ReweightController::class)->group(function () {
            Route::get('/', 'index')->name('reweight.index');
            Route::get('/create', 'create')->name('reweight.create');
        });
        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('payment.index');
            Route::get('/create', 'create')->name('payment.create');
        });
        Route::prefix('delivery')->controller(QurbanDeliveryController::class)->group(function () {
            Route::get('/', 'index')->name('delivery.index');
            Route::get('/create', 'create')->name('delivery.create');
        });
        Route::prefix('fleet-tracking')->controller(FleetTrackingController::class)->group(function () {
            Route::get('/', 'index')->name('fleet-tracking.index');
            Route::get('/create', 'create')->name('fleet-tracking.create');
        });
        Route::prefix('livestock-delivery-note')->controller(LiveestockDeliveryNoteController::class)->group(function () {
            Route::get('/', 'index')->name('livestock-delivery-note.index');
            Route::get('/create', 'create')->name('livestock-delivery-note.create');
        });
        Route::prefix('qurban-delivery-order-data')->controller(QurbanDeliveryOrderDataController::class)->group(function () {
            Route::get('/', 'index')->name('qurban-delivery-order-data.index');
            Route::get('/create', 'create')->name('qurban-delivery-order-data.create');
        });
        Route::prefix('population-report')->controller(PopulationReportController::class)->group(function () {
            Route::get('/', 'index')->name('population-report.index');
            Route::get('/create', 'create')->name('population-report.create');
        });
        Route::prefix('sales-qurban')->controller(SalesQurbanController::class)->group(function () {
            Route::get('/', 'index')->name('sales_qurban.index');
            Route::get('/create', 'create')->name('sales_qurban.create');
        });
        Route::prefix('cancelation-qurban')->controller(CancelationQurbanController::class)->group(function () {
            Route::get('/', 'index')->name('cancelation_qurban.index');
            Route::get('/create', 'create')->name('cancelation_qurban.create');
        });
    });

    Route::get('care-livestock', [CareLivestockController::class, 'index'])->name('care_livestock');
    Route::get('care-livestock/dashboard', function () {
        $farmId = session('selected_farm');
        if (!$farmId)
            return redirect()->route('care_livestock');
        return redirect()->route('admin.care-livestock.dashboard', ['farm_id' => $farmId]);
    })->name('care_livestock.shortcut_dashboard');

    Route::get('care-livestock/{farm_id}/dashboard', [CareLivestockController::class, 'dashboard'])
        ->name('admin.care-livestock.dashboard');

    Route::prefix('care-livestock/{farm_id}/pens')->middleware('check.farm.access')->group(function () {
        Route::get('/', [PenController::class, 'index'])->name('admin.care-livestock.pens.index');
        Route::get('/create', [PenController::class, 'create'])->name('admin.care-livestock.pens.create');
        Route::post('/', [PenController::class, 'store'])->name('admin.care-livestock.pens.store');
        Route::get('{pen_id}/edit', [PenController::class, 'edit'])->name('admin.care-livestock.pens.edit');
        Route::get('{pen_id}', [PenController::class, 'show'])->name('admin.care-livestock.pens.show'); // <--- Tambahan ini!
        Route::put('{pen_id}', [PenController::class, 'update'])->name('admin.care-livestock.pens.update');
        Route::delete('{pen_id}', [PenController::class, 'destroy'])->name('admin.care-livestock.pens.destroy');
    });

Route::prefix('care-livestock/{farm_id}/report/pen')
    ->middleware('check.farm.access')
    ->controller(Pen_Report_Controller::class)
    ->name('admin.care-livestock.pen-report.')
    ->group(function () {

        Route::get('/', 'index')->name('index'); // tampil form

        Route::get('/detail', 'detail')->name('detail'); // <-- GET FIX!!!!!

        Route::get('/export', 'exportPdf')->name('export'); // Export PDF
    });

    Route::prefix('care-livestock/{farm_id}/report/mutation-individu')
    ->middleware('check.farm.access')
    ->controller(Mutation_Individu_Report_Controller::class)
    ->name('admin.care-livestock.mutation-individu-report.')
    ->group(function () {

        Route::get('/', 'index')->name('index'); // form pilih kandang + tanggal

        Route::get('/detail', 'detail')->name('detail'); // preview laporan (GET)

        Route::get('/export', 'exportPdf')->name('export'); // export PDF
    });

    Route::prefix('care-livestock/{farm_id}/report/artificial-inseminasi')
    ->middleware('check.farm.access')
    ->controller(Artificial_Inseminasi_Report_Controller::class)
    ->name('admin.care-livestock.artificial-inseminasi-report.')
    ->group(function () {

        Route::get('/', 'index')->name('index');      // form filter
        Route::get('/detail', 'detail')->name('detail');  // preview laporan
        Route::get('/export', 'exportPdf')->name('export'); // export PDF
    });

    Route::prefix('care-livestock/{farm_id}/report/natural-inseminasi')
    ->middleware('check.farm.access')
    ->controller(\App\Http\Controllers\Admin\Report\CareLivestock\Natural_Inseminasi_Report_Controller::class)
    ->name('admin.care-livestock.natural-inseminasi-report.')
    ->group(function () {

        Route::get('/', 'index')->name('index'); // form pilih tanggal

        Route::get('/detail', 'detail')->name('detail'); // preview laporan

        Route::get('/export', 'exportPdf')->name('export'); // export PDF
    });







    Route::prefix('care-livestock/{farm_id}/livestock-reception')->middleware('check.farm.access')->group(function () {
        Route::get('/', [LivestockReceptionController::class, 'index'])->name('admin.care-livestock.livestock-reception.index');
        Route::get('/create', [LivestockReceptionController::class, 'create'])->name('admin.care-livestock.livestock-reception.create');
        Route::post('/', [LivestockReceptionController::class, 'store'])->name('admin.care-livestock.livestock-reception.store');
        Route::get('/{id}/edit', [LivestockReceptionController::class, 'edit'])->name('admin.care-livestock.livestock-reception.edit');
        Route::put('/{id}', [LivestockReceptionController::class, 'update'])->name('admin.care-livestock.livestock-reception.update');
        Route::delete('/{id}', [LivestockReceptionController::class, 'destroy'])->name('admin.care-livestock.livestock-reception.destroy');
        Route::get('/breeds', [LivestockReceptionController::class, 'breedIndex'])->name('admin.care-livestock.breeds.index');
    });

    Route::prefix('care-livestock/{farm_id}/sales-order')
    ->middleware('check.farm.access')
    ->group(function () {

        Route::get('/', [SalesOrderController::class, 'index'])
            ->name('admin.care-livestock.sales-order.index');

        Route::get('/create', [SalesOrderController::class, 'create'])
            ->name('admin.care-livestock.sales-order.create');

        Route::post('/', [SalesOrderController::class, 'store'])
            ->name('admin.care-livestock.sales-order.store');

        Route::get('/{id}/edit', [SalesOrderController::class, 'edit'])
            ->name('admin.care-livestock.sales-order.edit');

        Route::put('/{id}', [SalesOrderController::class, 'update'])
            ->name('admin.care-livestock.sales-order.update');

        Route::delete('/{id}', [SalesOrderController::class, 'destroy'])
            ->name('admin.care-livestock.sales-order.destroy');
    });

Route::prefix('care-livestock/{farm_id}/sales-livestock')->group(function () {
    Route::get('/', [SaledLivestockController::class, 'index'])
        ->name('admin.care-livestock.sales-livestock.index');

    Route::get('/create', [SaledLivestockController::class, 'create'])
        ->name('admin.care-livestock.sales-livestock.create');

    Route::post('/', [SaledLivestockController::class, 'store'])
        ->name('admin.care-livestock.sales-livestock.store');

    Route::get('/{id}/edit', [SaledLivestockController::class, 'edit'])
        ->name('admin.care-livestock.sales-livestock.edit');

    Route::put('/{id}', [SaledLivestockController::class, 'update'])
        ->name('admin.care-livestock.sales-livestock.update');

    Route::delete('/{id}', [SaledLivestockController::class, 'destroy'])
        ->name('admin.care-livestock.sales-livestock.destroy');
});

    Route::prefix('care-livestock/{farm_id}/livestock-sale-weight')->middleware('check.farm.access')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'index'])
            ->name('admin.care-livestock.livestock-sale-weight.index');
        Route::get('/create', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'create'])
            ->name('admin.care-livestock.livestock-sale-weight.create');
        Route::post('/', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'store'])
            ->name('admin.care-livestock.livestock-sale-weight.store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'show'])
            ->name('admin.care-livestock.livestock-sale-weight.show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'edit'])
            ->name('admin.care-livestock.livestock-sale-weight.edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'update'])
            ->name('admin.care-livestock.livestock-sale-weight.update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockSaleWeightController::class, 'destroy'])
            ->name('admin.care-livestock.livestock-sale-weight.destroy');
    });

    /**
 * Customer (Web)
 */
Route::prefix('care-livestock/{farm_id}/customer')
    ->middleware('check.farm.access')
    ->group(function () {

        // LIST CUSTOMER
        Route::get('/', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'index'
        ])->name('admin.care-livestock.customer.index');

        // CREATE CUSTOMER
        Route::get('/create', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'create'
        ])->name('admin.care-livestock.customer.create');

        // STORE CUSTOMER
        Route::post('/', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'store'
        ])->name('admin.care-livestock.customer.store');

        // EDIT CUSTOMER
        Route::get('/{id}/edit', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'edit'
        ])->name('admin.care-livestock.customer.edit');

        // UPDATE CUSTOMER
        Route::put('/{id}', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'update'
        ])->name('admin.care-livestock.customer.update');

        // DELETE CUSTOMER
        Route::delete('/{id}', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'destroy'
        ])->name('admin.care-livestock.customer.destroy');


        /**
         * ===============================
         *      CUSTOMER ADDRESS
         * ===============================
         */

        // LIST ADDRESS
        Route::get('/{customer_id}/address', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressIndex'
        ])->name('admin.care-livestock.customer.address.index');

        // CREATE ADDRESS
        Route::get('/{customer_id}/address/create', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressCreate'
        ])->name('admin.care-livestock.customer.address.create');

        // STORE ADDRESS
        Route::post('/{customer_id}/address', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressStore'
        ])->name('admin.care-livestock.customer.address.store');

        // EDIT ADDRESS
        Route::get('/{customer_id}/address/{id}/edit', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressEdit'
        ])->name('admin.care-livestock.customer.address.edit');

        // UPDATE ADDRESS
        Route::put('/{customer_id}/address/{id}', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressUpdate'
        ])->name('admin.care-livestock.customer.address.update');

        // DELETE ADDRESS
        Route::delete('/{customer_id}/address/{id}', [
            \App\Http\Controllers\Admin\CareLivestock\Customer\CustomerController::class,
            'addressDestroy'
        ])->name('admin.care-livestock.customer.address.destroy');
    });


    Route::prefix('care-livestock/{farm_id}/livestock-death')->middleware('check.farm.access')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'index'])
            ->name('admin.care-livestock.livestock-death.index');
        Route::get('/create', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'create'])
            ->name('admin.care-livestock.livestock-death.create');
        Route::post('/', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'store'])
            ->name('admin.care-livestock.livestock-death.store');
        Route::get('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'show'])
            ->name('admin.care-livestock.livestock-death.show');
        Route::get('/{id}/edit', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'edit'])
            ->name('admin.care-livestock.livestock-death.edit');
        Route::put('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'update'])
            ->name('admin.care-livestock.livestock-death.update');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\CareLivestock\LivestockDeathController::class, 'destroy'])
            ->name('admin.care-livestock.livestock-death.destroy');
    });

    Route::prefix('care-livestock/{farm_id}/feed-medicine-purchase')->middleware('check.farm.access')->group(function () {
        Route::get('/', [FeedMedicinePurchaseController::class, 'index'])
            ->name('admin.care-livestock.feed-medicine-purchase.index');
        Route::get('/create', [FeedMedicinePurchaseController::class, 'create'])
            ->name('admin.care-livestock.feed-medicine-purchase.create');
        Route::post('/', [FeedMedicinePurchaseController::class, 'store'])
            ->name('admin.care-livestock.feed-medicine-purchase.store');
        Route::get('/{id}', [FeedMedicinePurchaseController::class, 'show'])
            ->name('admin.care-livestock.feed-medicine-purchase.show');
        Route::get('/{id}/edit', [FeedMedicinePurchaseController::class, 'edit'])
            ->name('admin.care-livestock.feed-medicine-purchase.edit');
        Route::put('/{id}', [FeedMedicinePurchaseController::class, 'update'])
            ->name('admin.care-livestock.feed-medicine-purchase.update');
        Route::delete('/{id}', [FeedMedicinePurchaseController::class, 'destroy'])
            ->name('admin.care-livestock.feed-medicine-purchase.destroy');
    });

    Route::prefix('care-livestock/{farm_id}/milk-production-global')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [MilkProductionGlobalController::class, 'index'])
                ->name('admin.care-livestock.milk-production-global.index');
            Route::get('/create', [MilkProductionGlobalController::class, 'create'])
                ->name('admin.care-livestock.milk-production-global.create');
            Route::post('/', [MilkProductionGlobalController::class, 'store'])
                ->name('admin.care-livestock.milk-production-global.store');
            Route::get('/{id}', [MilkProductionGlobalController::class, 'show'])
                ->name('admin.care-livestock.milk-production-global.show');
            Route::get('/{id}/edit', [MilkProductionGlobalController::class, 'edit'])
                ->name('admin.care-livestock.milk-production-global.edit');
            Route::put('/{id}', [MilkProductionGlobalController::class, 'update'])
                ->name('admin.care-livestock.milk-production-global.update');
            Route::delete('/{id}', [MilkProductionGlobalController::class, 'destroy'])
                ->name('admin.care-livestock.milk-production-global.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/milk-analysis-colony')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [MilkAnalysisColonyController::class, 'index'])
                ->name('admin.care-livestock.milk-analysis-colony.index');
            Route::get('/create', [MilkAnalysisColonyController::class, 'create'])
                ->name('admin.care-livestock.milk-analysis-colony.create');
            Route::post('/', [MilkAnalysisColonyController::class, 'store'])
                ->name('admin.care-livestock.milk-analysis-colony.store');
            Route::get('/{id}', [MilkAnalysisColonyController::class, 'show'])
                ->name('admin.care-livestock.milk-analysis-colony.show');
            Route::get('/{id}/edit', [MilkAnalysisColonyController::class, 'edit'])
                ->name('admin.care-livestock.milk-analysis-colony.edit');
            Route::put('/{id}', [MilkAnalysisColonyController::class, 'update'])
                ->name('admin.care-livestock.milk-analysis-colony.update');
            Route::delete('/{id}', [MilkAnalysisColonyController::class, 'destroy'])
                ->name('admin.care-livestock.milk-analysis-colony.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/milk-analysis-global')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [MilkAnalysisGlobalController::class, 'index'])
                ->name('admin.care-livestock.milk-analysis-global.index');
            Route::get('/create', [MilkAnalysisGlobalController::class, 'create'])
                ->name('admin.care-livestock.milk-analysis-global.create');
            Route::post('/', [MilkAnalysisGlobalController::class, 'store'])
                ->name('admin.care-livestock.milk-analysis-global.store');
            Route::get('/{id}', [MilkAnalysisGlobalController::class, 'show'])
                ->name('admin.care-livestock.milk-analysis-global.show');
            Route::get('/{id}/edit', [MilkAnalysisGlobalController::class, 'edit'])
                ->name('admin.care-livestock.milk-analysis-global.edit');
            Route::put('/{id}', [MilkAnalysisGlobalController::class, 'update'])
                ->name('admin.care-livestock.milk-analysis-global.update');
            Route::delete('/{id}', [MilkAnalysisGlobalController::class, 'destroy'])
                ->name('admin.care-livestock.milk-analysis-global.destroy');
        });


    Route::prefix('care-livestock/{farm_id}/feeding-individu')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [FeedingIndividuController::class, 'index'])
                ->name('admin.care-livestock.feeding-individu.index');
            Route::get('/create', [FeedingIndividuController::class, 'create'])
                ->name('admin.care-livestock.feeding-individu.create');
            Route::post('/', [FeedingIndividuController::class, 'store'])
                ->name('admin.care-livestock.feeding-individu.store');
            Route::get('/{id}', [FeedingIndividuController::class, 'show'])
                ->name('admin.care-livestock.feeding-individu.show');
            Route::get('/{id}/edit', [FeedingIndividuController::class, 'edit'])
                ->name('admin.care-livestock.feeding-individu.edit');
            Route::put('/{id}', [FeedingIndividuController::class, 'update'])
                ->name('admin.care-livestock.feeding-individu.update');
            Route::delete('/{id}', [FeedingIndividuController::class, 'destroy'])
                ->name('admin.care-livestock.feeding-individu.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/feeding-colony')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [FeedingColonyController::class, 'index'])
                ->name('admin.care-livestock.feeding-colony.index');
            Route::get('/create', [FeedingColonyController::class, 'create'])
                ->name('admin.care-livestock.feeding-colony.create');
            Route::post('/', [FeedingColonyController::class, 'store'])
                ->name('admin.care-livestock.feeding-colony.store');
            Route::get('/{id}', [FeedingColonyController::class, 'show'])
                ->name('admin.care-livestock.feeding-colony.show');
            Route::get('/{id}/edit', [FeedingColonyController::class, 'edit'])
                ->name('admin.care-livestock.feeding-colony.edit');
            Route::put('/{id}', [FeedingColonyController::class, 'update'])
                ->name('admin.care-livestock.feeding-colony.update');
            Route::delete('/{id}', [FeedingColonyController::class, 'destroy'])
                ->name('admin.care-livestock.feeding-colony.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/treatment-individu')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [TreatmentIndividuController::class, 'index'])
                ->name('admin.care-livestock.treatment-individu.index');

            Route::get('/create', [TreatmentIndividuController::class, 'create'])
                ->name('admin.care-livestock.treatment-individu.create');

            Route::post('/', [TreatmentIndividuController::class, 'store'])
                ->name('admin.care-livestock.treatment-individu.store');

            Route::get('/{id}', [TreatmentIndividuController::class, 'show'])
                ->name('admin.care-livestock.treatment-individu.show');

            Route::get('/{id}/edit', [TreatmentIndividuController::class, 'edit'])
                ->name('admin.care-livestock.treatment-individu.edit');

            Route::put('/{id}', [TreatmentIndividuController::class, 'update'])
                ->name('admin.care-livestock.treatment-individu.update');

            Route::delete('/{id}', [TreatmentIndividuController::class, 'destroy'])
                ->name('admin.care-livestock.treatment-individu.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/treatment-colony')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [TreatmentColonyController::class, 'index'])
                ->name('admin.care-livestock.treatment-colony.index');

            Route::get('/create', [TreatmentColonyController::class, 'create'])
                ->name('admin.care-livestock.treatment-colony.create');

            Route::post('/', [TreatmentColonyController::class, 'store'])
                ->name('admin.care-livestock.treatment-colony.store');

            Route::get('/{id}', [TreatmentColonyController::class, 'show'])
                ->name('admin.care-livestock.treatment-colony.show');

            Route::get('/{id}/edit', [TreatmentColonyController::class, 'edit'])
                ->name('admin.care-livestock.treatment-colony.edit');

            Route::put('/{id}', [TreatmentColonyController::class, 'update'])
                ->name('admin.care-livestock.treatment-colony.update');

            Route::delete('/{id}', [TreatmentColonyController::class, 'destroy'])
                ->name('admin.care-livestock.treatment-colony.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/treatment-schedule-individu')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [TreatmentScheduleIndividuController::class, 'index'])
                ->name('admin.care-livestock.treatment-schedule-individu.index');

            Route::get('/create', [TreatmentScheduleIndividuController::class, 'create'])
                ->name('admin.care-livestock.treatment-schedule-individu.create');

            Route::post('/', [TreatmentScheduleIndividuController::class, 'store'])
                ->name('admin.care-livestock.treatment-schedule-individu.store');

            Route::get('/{id}', [TreatmentScheduleIndividuController::class, 'show'])
                ->name('admin.care-livestock.treatment-schedule-individu.show');

            Route::get('/{id}/edit', [TreatmentScheduleIndividuController::class, 'edit'])
                ->name('admin.care-livestock.treatment-schedule-individu.edit');

            Route::put('/{id}', [TreatmentScheduleIndividuController::class, 'update'])
                ->name('admin.care-livestock.treatment-schedule-individu.update');

            Route::delete('/{id}', [TreatmentScheduleIndividuController::class, 'destroy'])
                ->name('admin.care-livestock.treatment-schedule-individu.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/mutation-individu')
        ->middleware('check.farm.access')
        ->group(function () {
            Route::get('/', [MutationIndividuController::class, 'index'])
                ->name('admin.care-livestock.mutation-individu.index');

            Route::get('/create', [MutationIndividuController::class, 'create'])
                ->name('admin.care-livestock.mutation-individu.create');

            Route::post('/', [MutationIndividuController::class, 'store'])
                ->name('admin.care-livestock.mutation-individu.store');

            Route::get('/{id}', [MutationIndividuController::class, 'show'])
                ->name('admin.care-livestock.mutation-individu.show');

            Route::get('/{id}/edit', [MutationIndividuController::class, 'edit'])
                ->name('admin.care-livestock.mutation-individu.edit');

            Route::put('/{id}', [MutationIndividuController::class, 'update'])
                ->name('admin.care-livestock.mutation-individu.update');

            Route::delete('/{id}', [MutationIndividuController::class, 'destroy'])
                ->name('admin.care-livestock.mutation-individu.destroy');
        });

    Route::prefix('care-livestock/{farm_id}/artificial-inseminasi')
        ->middleware('check.farm.access')
        ->name('admin.care_livestock.artificial_inseminasi.')
        ->group(function () {
            Route::get('/', [ArtificialInseminasiController::class, 'index'])->name('index');
            Route::get('/create', [ArtificialInseminasiController::class, 'create'])->name('create');
            Route::post('/', [ArtificialInseminasiController::class, 'store'])->name('store');
            Route::get('/{id}', [ArtificialInseminasiController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [ArtificialInseminasiController::class, 'edit'])->name('edit');
            Route::put('/{id}', [ArtificialInseminasiController::class, 'update'])->name('update');
            Route::delete('/{id}', [ArtificialInseminasiController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('care-livestock/{farm_id}/natural-insemination')
        ->middleware('check.farm.access')
        ->name('admin.care_livestock.natural_insemination.')
        ->group(function () {
            Route::get('/', [NaturalInseminationController::class, 'index'])->name('index');
            Route::get('/create', [NaturalInseminationController::class, 'create'])->name('create');
            Route::post('/', [NaturalInseminationController::class, 'store'])->name('store');
            Route::get('/{id}', [NaturalInseminationController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [NaturalInseminationController::class, 'edit'])->name('edit');
            Route::put('/{id}', [NaturalInseminationController::class, 'update'])->name('update');
            Route::delete('/{id}', [NaturalInseminationController::class, 'destroy'])->name('destroy');
        });

    Route::prefix('care-livestock/{farm_id}/pregnant-check')
        ->middleware('check.farm.access')
        ->name('admin.care_livestock.pregnant_check.')
        ->group(function () {
            Route::get('/', [PregnantCheckController::class, 'index'])->name('index');
            Route::get('/create', [PregnantCheckController::class, 'create'])->name('create');
            Route::post('/', [PregnantCheckController::class, 'store'])->name('store');
            Route::get('/{id}', [PregnantCheckController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [PregnantCheckController::class, 'edit'])->name('edit');
            Route::put('/{id}', [PregnantCheckController::class, 'update'])->name('update');
            Route::delete('/{id}', [PregnantCheckController::class, 'destroy'])->name('destroy');
        });


    Route::prefix('care-livestock/{farm_id}/reproduction')
        ->middleware('check.farm.access')
        ->name('admin.care_livestock.reproduction.')
        ->group(function () {
            Route::get('/{livestock_id}/female', [ReproductionMasterController::class, 'getFemaleLivestockData'])
                ->name('female.show');
        });


    Route::prefix('care-livestock/{farm_id}/birth')
        ->middleware('check.farm.access')
        ->name('admin.care_livestock.livestock_birth.')
        ->group(function () {
            Route::get('/', [LivestockBirthController::class, 'index'])->name('index');
            Route::get('/create', [LivestockBirthController::class, 'create'])->name('create');
            Route::post('/', [LivestockBirthController::class, 'store'])->name('store');
            Route::get('/{id}', [LivestockBirthController::class, 'show'])->name('show');
            Route::get('/{id}/edit', [LivestockBirthController::class, 'edit'])->name('edit');
            Route::put('/{id}', [LivestockBirthController::class, 'update'])->name('update');
            Route::delete('/{id}', [LivestockBirthController::class, 'destroy'])->name('destroy');
        });

Route::prefix('care-livestock/{farm_id}/milk-production-individu') // DIUBAH
    ->middleware('check.farm.access')
    ->group(function () {
        Route::get('/', [MilkProductionIndividuController::class, 'index'])
            ->name('admin.care-livestock.milk-production-individu.index');

        Route::get('/create', [MilkProductionIndividuController::class, 'create'])
            ->name('admin.care-livestock.milk-production-individu.create');

        Route::post('/', [MilkProductionIndividuController::class, 'store'])
            ->name('admin.care-livestock.milk-production-individu.store');

        Route::get('/{id}/edit', [MilkProductionIndividuController::class, 'edit']) // DIUBAH
            ->name('admin.care-livestock.milk-production-individu.edit');

        Route::put('/{id}', [MilkProductionIndividuController::class, 'update']) // DIUBAH
            ->name('admin.care-livestock.milk-production-individu.update');

        Route::delete('/{id}', [MilkProductionIndividuController::class, 'destroy']) // DIUBAH
            ->name('admin.care-livestock.milk-production-individu.destroy');
    });

    Route::prefix('care-livestock/{farm_id}/milk-analysis-individu')
    ->middleware('check.farm.access')
    ->group(function () {
        Route::get('/', [MilkAnalysisIndividuController::class, 'index'])
            ->name('admin.care-livestock.milk-analysis-individu.index');

        Route::get('/create', [MilkAnalysisIndividuController::class, 'create'])
            ->name('admin.care-livestock.milk-analysis-individu.create');

        Route::post('/', [MilkAnalysisIndividuController::class, 'store'])
            ->name('admin.care-livestock.milk-analysis-individu.store');

        Route::get('/{id}/edit', [MilkAnalysisIndividuController::class, 'edit'])
            ->name('admin.care-livestock.milk-analysis-individu.edit');

        Route::put('/{id}', [MilkAnalysisIndividuController::class, 'update'])
            ->name('admin.care-livestock.milk-analysis-individu.update');

        Route::delete('/{id}', [MilkAnalysisIndividuController::class, 'destroy'])
            ->name('admin.care-livestock.milk-analysis-individu.destroy');
    });

Route::prefix('care-livestock/{farm_id}/classification')
    ->middleware('check.farm.access')
    ->group(function () {
        Route::get('/', [LivestockClassificationController::class, 'index'])
            ->name('admin.care-livestock.classification.index');

        Route::get('/{id}/edit', [LivestockClassificationController::class, 'edit'])
            ->name('admin.care-livestock.classification.edit');

        Route::put('/{id}', [LivestockClassificationController::class, 'update'])
            ->name('admin.care-livestock.classification.update');
    });

    // Ubah ini menjadi:
Route::get('admin/livestock-outlet/dashboard', [LivestockOutletController::class, 'dashboard'])->name('livestock_outlet.dashboard');

});
