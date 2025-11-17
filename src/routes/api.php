<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\EmissionController;
use App\Http\Controllers\VehicleController;

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('emissions')->group(function () {
    Route::get('/hourly-average', [EmissionController::class, 'getHourlyAverage']);
    Route::get('/weekly-average', [EmissionController::class, 'getWeeklyAverage']);
    Route::get('/monthly-average', [EmissionController::class, 'getMonthlyAverage']);
});

Route::prefix('vehicles')->group(function () {
    Route::get('/hourly-average', [VehicleController::class, 'getHourlyAverage']);
    Route::get('/weekly-average', [VehicleController::class, 'getWeeklyAverage']);
    Route::get('/daily-average', [VehicleController::class, 'getDailyAverage']);
    Route::get('/monthly-average', [VehicleController::class, 'getMonthlyAverage']);
});