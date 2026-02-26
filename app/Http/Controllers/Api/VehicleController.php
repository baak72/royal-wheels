<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;

class VehicleController extends Controller
{
    /**
     * Retourne la liste de tous les véhicules pour le catalogue.
     */
    public function index()
    {
        // On récupère tout les véhicules avec leur photo principale
        $vehicles = Vehicle::with('primaryPhoto')->get();

        return VehicleResource::collection($vehicles);
    }
}