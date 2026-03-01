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


// --- ROUTES PUBLICS ---

// Afficher tout le catalogue de véhicules
Route::get('/vehicles', [VehicleController::class, 'index']);
// Voir les détails de UN SEUL véhicule spécifique
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);


// --- Routes protégées (nécessitent un token valide) ---
Route::middleware('auth:sanctum')->group(function () {
    
    // La route pour se déconnecter
    Route::post('/logout', [AuthController::class, 'logout']);
    // Voir son historique de réservations
    Route::get('/reservations', [ReservationController::class, 'index']);
    // La route pour créer une réservation
    Route::post('/reservations', [ReservationController::class, 'store']);

    
// --- Routes administration (Nécessitent un Token valide ET le rôle Admin) ---
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    
    // Route de test temporaire
    Route::get('/admin/test', function () {
        return response()->json([
            'message' => 'Bienvenue dans le repaire secret de l\'Admin !'
        ], 200);
    });
    // Ajouter un véhicule au catalogue
    Route::post('/vehicles', [VehicleController::class, 'store']);
    // Modifier un véhicule du catalogue
    Route::put('/vehicles/{id}', [VehicleController::class, 'update']);
    // Supprimer un véhicule du catalogue
    Route::delete('/vehicles/{id}', [VehicleController::class, 'destroy']);
    
});
});