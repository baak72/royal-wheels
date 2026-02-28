<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use App\Http\Resources\VehicleResource;
use Illuminate\Http\Request;

class VehicleController extends Controller
{
    /**
     * Affiche la liste de tous les véhicules.
     */
    public function index()
    {
        // Récupération de tous les véhicules depuis la base de données
        $vehicles = Vehicle::all();

        // On retourne le résultat en JSON
        return response()->json([
            'message' => 'Catalogue récupéré avec succès.',
            'vehicles' => $vehicles
        ], 200);
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

    /**
     * ADMIN : Ajoute un nouveau véhicule au catalogue.
     */
    public function store(Request $request)
    {
        // 1. Vérification des données envoyées
        $validated = $request->validate([
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'gearbox' => 'required|string|in:Manuelle,Automatique',
            'engine' => 'required|string|max:255',
            'power_hp' => 'required|integer|min:0',
            'acceleration' => 'required|numeric|min:0',
            'seats' => 'required|integer|min:1',
            'daily_price' => 'required|numeric|min:0',
            'min_age' => 'required|integer|min:18',
            'min_license_years' => 'required|integer|min:0',
            'deposit' => 'required|numeric|min:0',
            'status' => 'nullable|string|in:disponible,en_maintenance,loué',
        ]);

        // Statut "Disponible" par défaut
        $validated['status'] = $validated['status'] ?? 'Disponible';

        // 2. Enregistrement en base de données
        $vehicle = Vehicle::create($validated);

        // 3. Réponse JSON avec le véhicule créé
        return response()->json([
            'message' => 'Véhicule ajouté au catalogue avec succès !',
            'vehicle' => $vehicle
        ], 201);
    }
}