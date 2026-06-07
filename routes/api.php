<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CourtController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WaitingListController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('/courts', [CourtController::class, 'index']);
Route::get('/courts/{court}', [CourtController::class, 'show']);
Route::get('/courts/{court}/available-slots', [CourtController::class, 'availableSlots']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/profile', [ProfileController::class, 'me']);
    Route::put('/profile', [ProfileController::class, 'updateMe']);

    Route::apiResource('reservations', ReservationController::class);
    Route::post('/reservations', [ReservationController::class, 'store']);
    
    Route::apiResource('payments', PaymentController::class);
    Route::apiResource('waiting-lists', WaitingListController::class);
});