<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReservationController;

// --- CATALOGUE ---
Route::get('/vehicles', [VehicleController::class, 'index']);
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);

// --- AUTHENTIFICATION ---
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// --- Routes protégées (nécessitent un token valide) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // La route pour se déconnecter
    Route::post('/logout', [AuthController::class, 'logout']);

    // La route pour créer une réservation
    Route::post('/reservations', [ReservationController::class, 'store']);
    
});