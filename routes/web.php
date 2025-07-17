<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\FarmController;
use App\Http\Controllers\Admin\Qurban\DashboardController;
use App\Http\Controllers\Admin\Qurban\CustomerController;
use App\Http\Controllers\Admin\Qurban\FleetController;
use App\Http\Controllers\Admin\Qurban\DriverController;
use App\Http\Controllers\Admin\Qurban\SalesOrderController;
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
use App\Http\Controllers\Admin\CareLivestock\SalesLivestockController;

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
    Route::get('dashboard', function () {
        return view('menu.index');
    });

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
        Route::put('{pen_id}', [PenController::class, 'update'])->name('admin.care-livestock.pens.update');
        Route::delete('{pen_id}', [PenController::class, 'destroy'])->name('admin.care-livestock.pens.destroy');
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

    Route::prefix('care-livestock/{farm_id}/sales-livestock')->group(function () {
        Route::get('/', [SalesLivestockController::class, 'index'])->name('admin.care-livestock.sales-livestock.index');
        Route::get('/create', [SalesLivestockController::class, 'create'])->name('admin.care-livestock.sales-livestock.create');
        Route::post('/', [SalesLivestockController::class, 'store'])->name('admin.care-livestock.sales-livestock.store');
        Route::get('/{id}/edit', [SalesLivestockController::class, 'edit'])->name('admin.care-livestock.sales-livestock.edit');
        Route::put('/{id}', [SalesLivestockController::class, 'update'])->name('admin.care-livestock.sales-livestock.update');
        Route::delete('/{id}', [SalesLivestockController::class, 'destroy'])->name('admin.care-livestock.sales-livestock.destroy');
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




});
