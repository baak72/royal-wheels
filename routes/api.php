<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;

// URL pour TOUT le catalogue : http://royal-wheels.test/api/vehicles
Route::get('/vehicles', [VehicleController::class, 'index']);

// URL pour UN SEUL véhicule : http://royal-wheels.test/api/vehicles/1 (ou 2, ou 3...)
Route::get('/vehicles/{id}', [VehicleController::class, 'show']);