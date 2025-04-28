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
use App\Http\Controllers\Admin\Qurban\SalesLivestockController;
use App\Http\Controllers\Admin\Qurban\ReweightController;
use App\Http\Controllers\Admin\Qurban\PaymentController;
use App\Http\Controllers\Admin\Qurban\QurbanDeliveryOrderDataController;
use App\Http\Controllers\Admin\Qurban\SalesQurbanController;
use App\Http\Controllers\Admin\Qurban\CancelationQurbanController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index']);

Route::prefix('auth')->controller(AuthController::class)->group(function () {
    Route::get('login', 'showLoginForm');
    Route::post('login', 'login')->name('login');
    Route::post('logout', 'logout')->name('logout');
});

Route::middleware(['auth', 'email.verified'])->group(function () {
    Route::get('dashboard', function () {
        return view('menu.index');
    });

    // Farm select and create
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

        Route::prefix('sales-livestock')->controller(SalesLivestockController::class)->group(function () {
            Route::get('/', 'index');
            Route::get('create', 'create');
            Route::post('/', 'store');
            Route::get('{saleLivestockId}/edit', 'edit');
            Route::put('{saleLivestockId}', 'update');
            Route::delete('{saleLivestockId}', 'destroy');
        });

        Route::prefix('reweight')->controller(ReweightController::class)->group(function () {
            Route::get('/', 'index')->name('reweight.index');
            Route::get('/create', 'create')->name('reweight.create');
        });

        Route::prefix('payment')->controller(PaymentController::class)->group(function () {
            Route::get('/', 'index')->name('payment.index');
            Route::get('/create', 'create')->name('payment.create');
        });

        Route::prefix('qurban-delivery-order-data')->controller(QurbanDeliveryOrderDataController::class)->group(function () {
            Route::get('/', 'index')->name('qurban_delivery_order_data.index');
            Route::get('/create', 'create')->name('qurban_delivery_order_data.create');
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
});
