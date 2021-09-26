<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InwardQualityController;
use App\Http\Controllers\SellQualityController;
use App\Http\Controllers\BrokerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/inwardqualitycategories',[InwardQualityController::class,'getQualityCategories']);
Route::get('/inwardqualities',[InwardQualityController::class,'getAllInwardQualities']);
Route::prefix('/inwardquality')->group(function(){
    Route::post('/insert',[InwardQualityController::class,'insertInwardQuality']);
    Route::put('/update/{inward_quality_id}',[InwardQualityController::class,'updateInwardQuality']);
    Route::delete('/delete/{inward_quality_id}',[InwardQualityController::class,'deleteInwardQuality']);
});

Route::get('/sellqualitycategories',[SellQualityController::class,'getQualityCategories']);
Route::get('/sellqualities',[SellQualityController::class,'getAllSellQualities']);
Route::prefix('/sellquality')->group(function(){
    Route::post('/insert',[SellQualityController::class,'insertSellQuality']);
    Route::put('/update/{sell_quality_id}',[SellQualityController::class,'updateSellQuality']);
    Route::delete('/delete/{sell_quality_id}',[SellQualityController::class,'deleteSellQuality']);
});

Route::get('/brokers',[BrokerController::class,'getAllBrokers']);
Route::prefix('/broker')->group(function(){
    Route::post('/insert',[BrokerController::class,'insertBroker']);
    Route::put('/update/{broker_id}',[BrokerController::class,'updateBroker']);
    Route::delete('/delete/{broker_id}',[BrokerController::class,'deleteBroker']);
});