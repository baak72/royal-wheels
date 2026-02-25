<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\VehicleController;

// URL : http://127.0.0.1:8000/api/vehicles
Route::get('/vehicles', [VehicleController::class, 'index']);