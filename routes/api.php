<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\InterventionHistoryController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\UserController;
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


Route::get('/postpage', [ChatController::class, 'index']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:api')->group(function () {
    //--------------------------------- user   route     ---------------------------
    Route::get('/userProfile', [AuthController::class, 'userProfile']);
    Route::get('/users-with-cars', [UserController::class, 'usersCountCar']);
    Route::get('/admin', [UserController::class, 'admin']);
    Route::apiResource('/users', UserController::class);



    Route::apiResource('/cars', CarController::class);
    Route::get('/my-car/{id}', [CarController::class, 'allCarUser']);


    Route::apiResource('/insurances', InsuranceController::class);

    Route::apiResource('/interventions', InterventionController::class);
    Route::get('/interventions.localisation', [InterventionController::class, 'localisationProgress']);

    Route::apiResource('/services', MaintenanceServiceController::class);
    Route::apiResource('/historiques', InterventionHistoryController::class);

    Route::get('/total-user', [StatController::class, 'totalUser']);
    Route::get('/intervention-month', [StatController::class, 'interventionByMonth']);
    Route::get('/insurance-month', [StatController::class, 'insurancesByMonth']);


    Route::post('/messages', [ChatController::class, 'sendMessage']);
    Route::get('/messages', [ChatController::class, 'getMessages']);
    Route::get('/messages/{id}', 'MessageController@show');
});
