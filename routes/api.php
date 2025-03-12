<?php

use App\Http\Controllers\InterventionHistoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CarController;
use App\Http\Controllers\InsuranceController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\MaintenanceServiceController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::apiResource('/cars', CarController::class);
Route::apiResource('/insurances', InsuranceController::class);
Route::apiResource('/interventions', InterventionController::class);
Route::apiResource('/services', MaintenanceServiceController::class);
Route::apiResource('/historiques', InterventionHistoryController::class);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {

});
