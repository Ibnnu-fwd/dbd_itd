<?php

use App\Http\Controllers\Admin\BuildingTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\EnvironmentTypeController;
use App\Http\Controllers\Admin\FloorTypeController;
use App\Http\Controllers\Admin\LocationTypeController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\SettlementTypeController;
use App\Http\Controllers\Admin\TpaTypeController;
use App\Http\Controllers\Admin\VillageController;
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
    Route::post('district/list', [DistrictController::class, 'list'])->name('admin.district.list');
    Route::resource('district', DistrictController::class, ['as' => 'admin']);

    // Tpa Type
    Route::resource('tpa-type', TpaTypeController::class, ['as' => 'admin']);

    // Floor Type
    Route::resource('floor-type', FloorTypeController::class, ['as' => 'admin']);

    // Environment Type
    Route::resource('environment-type', EnvironmentTypeController::class, ['as' => 'admin']);

    // Village
    Route::resource('village', VillageController::class, ['as' => 'admin']);

    // Location Type
    Route::resource('location-type', LocationTypeController::class, ['as' => 'admin']);

    // Settlement Type
    Route::resource('settlement-type', SettlementTypeController::class, ['as' => 'admin']);

    // Building Type
    Route::resource('building-type', BuildingTypeController::class, ['as' => 'admin']);
});

require __DIR__.'/auth.php';
