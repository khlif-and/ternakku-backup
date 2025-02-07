<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [HomeController::class, 'index']);

Route::group([
    'prefix' => 'auth',
    'controller' => App\Http\Controllers\Admin\AuthController::class
], function () {
    Route::get('login', 'showLoginForm');
    Route::post('login', 'login')->name('login');
    Route::post('/logout', 'logout')->name('logout');
});


Route::middleware(['auth', 'email.verified' , 'farmer'])->group(function() {
    Route::get('dashboard' , function(){
        return view('menu.index');
    });

    Route::get('select-farm' , [App\Http\Controllers\Admin\FarmController::class , 'selectFarm']);
    Route::post('select-farm' , [App\Http\Controllers\Admin\FarmController::class , 'selectFarmStore']);

    Route::group([
        'prefix' => 'qurban',
    ], function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\Qurban\DashboardController::class , 'dashboard']);

        Route::group([
            'prefix' => 'farm',
            'controller' => App\Http\Controllers\Admin\FarmController::class
        ], function () {
            Route::get('/find-user', 'findUser');
            Route::get('/user-list', 'userList');
            Route::post('/add-user', 'addUser');
            Route::get('/user-list/create', 'userCreate');
        });

        Route::group([
            'prefix' => 'customer',
            'controller' => App\Http\Controllers\Admin\Qurban\CustomerController::class
        ], function () {
            Route::get('/', 'index');
            Route::get('/create', 'create');
            Route::post('/', 'store');
            Route::get('/{customerId}/edit', 'edit');
            Route::put('/{customerId}', 'update');
            Route::delete('/{customerId}', 'destroy');
        });

        Route::group([
            'prefix' => 'customer/address/{customerId}',
            'controller' => App\Http\Controllers\Admin\Qurban\CustomerController::class
        ], function () {
            Route::get('/', 'indexAddress');
            Route::get('/create', 'createAddress');
            Route::post('/', 'storeAddress');
            Route::get('/{addressId}/edit', 'editAddress');
            Route::post('/{addressId}', 'updateAddress');
            Route::post('/{addressId}', 'updateAddress');
        });

        Route::group([
            'prefix' => 'fleet',
            'controller' => App\Http\Controllers\Admin\Qurban\FleetController::class
        ], function () {
            Route::get('/', 'index');
            Route::get('/create', 'create');
            Route::post('/', 'store');
            Route::get('/{fleetId}/edit', 'edit');
            Route::put('/{fleetId}', 'update');
            Route::delete('/{fleetId}', 'destroy');
        });

        Route::group([
            'prefix' => 'driver',
            'controller' => App\Http\Controllers\Admin\Qurban\DriverController::class
        ], function () {
            Route::get('/', 'index');
        });
    });
});
