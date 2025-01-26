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


Route::middleware(['auth', 'email.verified'])->group(function() {
    Route::get('dashboard' , function(){
        return view('menu.index');
    });

    Route::group([
        'prefix' => 'qurban',
        'controller' => App\Http\Controllers\Admin\Qurban\DashboardController::class
    ], function () {
        Route::get('dashboard', 'dashboard');
    });
});
