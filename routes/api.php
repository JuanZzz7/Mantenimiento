<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AssetController;
use App\Http\Controllers\Api\WorkOrderController;
use App\Http\Controllers\Api\SpareController;
use Illuminate\Support\Facades\Route;

// ── API Auth ──────────────────────────────────────────────────────────────────
Route::post('/login',  [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// ── API Protegida con Sanctum ─────────────────────────────────────────────────
Route::middleware('auth:sanctum')->name('api.')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);

    Route::apiResource('assets',      AssetController::class);
    Route::apiResource('work-orders', WorkOrderController::class);
    Route::apiResource('spares',      SpareController::class);
});
