<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\TransactionController;

// Authentication Routes
Route::post('/register', [AuthController::class, 'register'])->middleware('throttle:10,1');
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:5,1');

// Protected Routes (Require Authentication)
Route::middleware(['auth:sanctum', 'throttle:60,1'])->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    // Account Routes
    Route::get('/accounts', [AccountController::class, 'index']);
    Route::post('/accounts', [AccountController::class, 'store']);
    Route::get('/accounts/{id}', [AccountController::class, 'show']);
    Route::put('/accounts/{id}', [AccountController::class, 'update']);
    Route::delete('/accounts/{id}', [AccountController::class, 'destroy']);

    // Transaction Routes
    Route::post('/transactions', [TransactionController::class, 'store']);
    Route::get('/transactions/{accountId}', [TransactionController::class, 'index']);
});


Route::get('/test', function () {
    return response()->json(['message' => 'API is working']);
});
