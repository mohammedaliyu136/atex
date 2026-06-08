<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ShopController;
use App\Http\Controllers\Api\SyncController;

Route::post('/auth/login', function (Request $request) {
    // Simple mock login for MVP
    return response()->json(['token' => 'mock-token']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/shops', [ShopController::class, 'store']);
    Route::get('/shops/{id}', [ShopController::class, 'show']);
    Route::post('/sync', [SyncController::class, 'sync']);
});

// For testing purposes without auth in MVP if needed
Route::post('/public/shops', [ShopController::class, 'store']);
Route::post('/public/sync', [SyncController::class, 'sync']);
