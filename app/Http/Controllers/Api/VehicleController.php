<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Retourne la liste de tous les véhicules pour le catalogue.
     */
    public function index()
    {
        // On récupère tous les véhicules AVEC leur photo principale
        $vehicles = Vehicle::with('primaryPhoto')->get();

        // On renvoie le tout au format JSON avec un code 200 (Succès)
        return response()->json($vehicles, 200);
    }
}