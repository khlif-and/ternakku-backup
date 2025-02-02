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

    Route::group([
        'prefix' => 'qurban',
    ], function () {
        Route::get('dashboard', [App\Http\Controllers\Admin\Qurban\DashboardController::class , 'dashboard']);

        Route::group([
            'prefix' => 'farm',
            'controller' => App\Http\Controllers\Admin\FarmController::class
        ], function () {
            Route::get('/user-list', 'userList');
        });

        Route::group([
            'prefix' => 'customer',
            'controller' => App\Http\Controllers\Admin\Qurban\CustomerController::class
        ], function () {
            Route::get('/', 'index');
        });

        Route::group([
            'prefix' => 'fleet',
            'controller' => App\Http\Controllers\Admin\Qurban\FleetController::class
        ], function () {
            Route::get('/', 'index');
        });

        Route::group([
            'prefix' => 'driver',
            'controller' => App\Http\Controllers\Admin\Qurban\DriverController::class
        ], function () {
            Route::get('/', 'index');
        });
    });
});
