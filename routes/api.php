<?php

use App\Http\Controllers\Api\RegencyControllerApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbjControllerApi;
use App\Http\Controllers\Api\DistrictControllerApi;
use App\Http\Controllers\Api\KshControllerApi;
use App\Http\Controllers\Api\DataKasusControllerApi; 
use App\Http\Controllers\Api\UserControllerApi; 

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Abj get data
Route::get('/abj132[]-23*09@#!', [AbjControllerApi::class, 'index']);
// Cara get data abj
// abj132%5B%5D-23%2A09%40%23%21

// Regency get data
Route::get('/regency283019231-{}sdasda', [RegencyControllerApi::class, 'index']);
Route::get('/regencies/{id}', [RegencyControllerApi::class, 'show']);
Route::get('/regencies/search', [RegencyControllerApi::class, 'search']);
// Cara get regency
// /regency283019231-%7B%7Dsdasda

// District get data
Route::get('/districts283019231-{}sdasda', [DistrictControllerApi::class, 'index']);
Route::get('/districts/{id}', [DistrictControllerApi::class, 'show']);
Route::get('/districts/search', [DistrictControllerApi::class, 'search']);
// cara get District
// /districts283019231-%7B%7Dsdasda

// ksh
Route::get('/ksh', [KshControllerApi::class, 'index']);
Route::get('/ksh/{id}', [KshControllerApi::class, 'show']);
Route::post('/ksh', [KshControllerApi::class, 'store']);
Route::put('/ksh/{id}', [KshControllerApi::class, 'update']);
Route::delete('/ksh/{id}', [KshControllerApi::class, 'destroy']);

// Rute API untuk DataKasusController
Route::get('/cases', [DataKasusControllerApi::class, 'index']);
Route::get('/cases/{id}', [DataKasusControllerApi::class, 'show']);
Route::post('/cases', [DataKasusControllerApi::class, 'store']);
Route::put('/cases/{id}', [DataKasusControllerApi::class, 'update']);
Route::delete('/cases/{id}', [DataKasusControllerApi::class, 'destroy']);

// login
Route::post('/login', [UserControllerApi::class, 'login']);






