<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\CourtController;
use App\Http\Controllers\Api\ReservationController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\WaitingListController;
use App\Http\Controllers\Api\PaymentOptionController;

// ── Public routes ──────────────────────────────────────────────────────────────
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login',    [AuthController::class, 'login']);


Route::middleware('auth:sanctum')->get(
    '/courts',
    [CourtController::class, 'index']
);
Route::middleware('auth:sanctum')->get(
    '/courts/nearest',
    [CourtController::class, 'nearest']
);
Route::get('/courts/{court}',                  [CourtController::class, 'show']);
Route::get('/courts/{court}/available-slots',  [CourtController::class, 'availableSlots']);

Route::get('/payments-options', [PaymentOptionController::class, 'index']);
Route::post('/payments', [PaymentController::class, 'store']); 

Route::middleware('auth:sanctum')->post(
    '/user/location',
    [AuthController::class, 'updateLocation']
);

Route::get(
    '/locations',
    [CourtController::class, 'locations']
);

Route::get(
    '/locations/{id}/courts',
    [CourtController::class, 'courtsByLocation']
);

// ── Protected routes ───────────────────────────────────────────────────────────
Route::middleware('auth:sanctum')->group(function () {

    // Auth
    Route::post('/logout', [AuthController::class, 'logout']);

    // Profile
    Route::get('/profile', [ProfileController::class, 'me']);
    Route::put('/profile', [ProfileController::class, 'updateMe']);

    // Reservations
    Route::apiResource('reservations', ReservationController::class)
         ->only(['index', 'show', 'store', 'destroy']);

    // Payments
    Route::get('/payments',              [PaymentController::class, 'index']);
    Route::get('/payments/{payment}',    [PaymentController::class, 'show']);
    Route::post('/payments/{payment}/pay', [PaymentController::class, 'pay']);
    

    // Waiting lists
    Route::apiResource('waiting-lists', WaitingListController::class)
         ->only(['index', 'store', 'destroy']);
});
