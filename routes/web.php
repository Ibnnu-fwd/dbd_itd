<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\FloorTypeController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\TpaTypeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('auth.login');
});

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function() {
    Route::get('/', DashboardController::class)->name('admin.dashboard');

    // Province
    Route::resource('province', ProvinceController::class, ['as' => 'admin']);

    // Regency
    Route::resource('regency', RegencyController::class, ['as' => 'admin']);

    // District
    Route::resource('district', DistrictController::class, ['as' => 'admin']);

    // Tpa Type
    Route::resource('tpa-type', TpaTypeController::class, ['as' => 'admin']);

    // Floor Type
    Route::resource('floor-type', FloorTypeController::class, ['as' => 'admin']);
});

require __DIR__.'/auth.php';
