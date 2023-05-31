<?php

use App\Http\Controllers\Admin\BuildingTypeController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\EnvironmentTypeController;
use App\Http\Controllers\Admin\FloorTypeController;
use App\Http\Controllers\Admin\LocationTypeController;
use App\Http\Controllers\Admin\MorphotypeController;
use App\Http\Controllers\Admin\ProvinceController;
use App\Http\Controllers\Admin\RegencyController;
use App\Http\Controllers\Admin\SampleController;
use App\Http\Controllers\Admin\SampleMethodController;
use App\Http\Controllers\Admin\SerotypeController;
use App\Http\Controllers\Admin\SettlementTypeController;
use App\Http\Controllers\Admin\TpaTypeController;
use App\Http\Controllers\Admin\VillageController;
use App\Http\Controllers\Admin\VirusController;
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

Route::group(['prefix' => 'dashboard', 'middleware' => ['auth']], function () {
    Route::get('/', DashboardController::class)->name('admin.dashboard');

    // Province
    Route::post('province/list', [ProvinceController::class, 'list'])->name('admin.province.list');
    Route::resource('province', ProvinceController::class, ['as' => 'admin']);

    // Regency
    Route::post('regency/list', [RegencyController::class, 'list'])->name('admin.regency.list');
    Route::resource('regency', RegencyController::class, ['as' => 'admin']);

    // District
    Route::post('district/list', [DistrictController::class, 'list'])->name('admin.district.list');
    Route::resource('district', DistrictController::class, ['as' => 'admin']);


    // Village
    Route::post('village/list', [VillageController::class, 'list'])->name('admin.village.list');
    Route::resource('village', VillageController::class, ['as' => 'admin']);

    // Tpa Type
    Route::resource('tpa-type', TpaTypeController::class, ['as' => 'admin']);

    // Floor Type
    Route::resource('floor-type', FloorTypeController::class, ['as' => 'admin']);

    // Environment Type
    Route::resource('environment-type', EnvironmentTypeController::class, ['as' => 'admin']);

    // Location Type
    Route::resource('location-type', LocationTypeController::class, ['as' => 'admin']);

    // Settlement Type
    Route::resource('settlement-type', SettlementTypeController::class, ['as' => 'admin']);

    // Building Type
    Route::resource('building-type', BuildingTypeController::class, ['as' => 'admin']);

    // Serotype
    Route::resource('serotype', SerotypeController::class, ['as' => 'admin']);

    // Virus
    Route::get('virus/list', [VirusController::class, 'list'])->name('admin.virus.list');
    Route::resource('virus', VirusController::class, ['as' => 'admin']);

    // Morphotype
    Route::get('morphotype/list', [MorphotypeController::class, 'list'])->name('admin.morphotype.list');
    Route::resource('morphotype', MorphotypeController::class, ['as' => 'admin']);

    // Sample Method
    Route::get('sample-method/list', [SampleMethodController::class, 'list'])->name('admin.sample-method.list');
    Route::resource('sample-method', SampleMethodController::class, ['as' => 'admin']);

    // Sample
    Route::post('sample/detail-sample/virus/morphotype/delete', [SampleController::class, 'deleteDetailSampleVirusMorphotype'])->name('admin.sample.detail-sample.virus.morphotype.delete');
    Route::post('sample/detail-sample/virus/{id}/delete', [SampleController::class, 'deleteDetailSampleVirus'])->name('admin.sample.detail-sample.virus.delete');
    Route::post('sample/detail-sample/virus/{id}/store', [SampleController::class, 'storeDetailSampleVirus'])->name('admin.sample.detail-sample.virus.store');
    Route::get('sample/detail-sample/virus/{id}', [SampleController::class, 'detailSampleVirus'])->name('admin.sample.detail-sample.virus');
    Route::get('sample/detail-sample/{id}', [SampleController::class, 'detailSample'])->name('admin.sample.detail-sample');
    Route::resource('sample', SampleController::class, ['as' => 'admin']);
});

require __DIR__ . '/auth.php';