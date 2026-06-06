<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CourtController;
use App\Http\Controllers\Api\ReservationController;



Route::post('/register',
    [AuthController::class, 'register']);

Route::post('/login',
    [AuthController::class, 'login']);

Route::middleware('auth:sanctum')
    ->group(function () {

    Route::post('/logout',
        [AuthController::class, 'logout']);

    Route::get('/profile',
        [ProfileController::class, 'me']);

    Route::put('/profile',
        [ProfileController::class, 'updateMe']);

    Route::apiResource(
        'reservations',
        ReservationController::class
    );

    Route::apiResource(
        'courts',
        CourtController::class
    );

    Route::apiResource(
        'payments',
        PaymentController::class
    );

    Route::apiResource(
        'waiting-lists',
        WaitingListController::class
    );
});