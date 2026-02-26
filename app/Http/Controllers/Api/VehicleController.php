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
        // On récupère tous les véhicules AVEC leur photo principale
        $vehicles = Vehicle::with('primaryPhoto')->get();

        return VehicleResource::collection($vehicles);
    }

    /**
     * Retourne les détails d'un SEUL véhicule spécifique avec TOUTES ses photos.
     */
    public function show($id)
    {
        // 1. findOrFail($id) : Cherche le véhicule numéro X. S'il n'existe pas, renvoie une erreur.
        // 2. with('photos') : Chargement de toute la galerie de photos liées à ce véhicule.
        $vehicle = Vehicle::with('photos')->findOrFail($id);

        // Filtrage ("new" car il n'y a qu'un seul véhicule)
        return new VehicleResource($vehicle);
    }
}