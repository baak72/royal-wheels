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
     * Affiche les détails d'un véhicule spécifique.
     */
    public function show(string $id)
    {
        // 1. On cherche le véhicule par son ID
        $vehicle = Vehicle::find($id);

        // 2. Si le véhicule n'existe pas, on renvoie une erreur
        if (!$vehicle) {
            return response()->json([
                'message' => 'Véhicule introuvable.'
            ], 404);
        }

        // 3. S'il existe, on retourne ses données
        return response()->json([
            'message' => 'Détails du véhicule récupérés avec succès.',
            'vehicle' => $vehicle
        ], 200);
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
            'status' => 'nullable|string|in:Disponible,En_maintenance,Loué',
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

    /**
     * ADMIN : Met à jour un véhicule existant.
     */
    public function update(Request $request, $id)
    {
        // 1. On cherche le véhicule (erreur si non trouvé)
        $vehicle = Vehicle::findOrFail($id);

        // 2. On valide les données reçues
        $validated = $request->validate([
            'brand' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'category' => 'sometimes|required|string|max:255',
            'gearbox' => 'sometimes|required|string|in:Manuelle,Automatique',
            'engine' => 'sometimes|required|string|max:255',
            'power_hp' => 'sometimes|required|integer|min:0',
            'acceleration' => 'sometimes|required|numeric|min:0',
            'seats' => 'sometimes|required|integer|min:1',
            'daily_price' => 'sometimes|required|numeric|min:0',
            'min_age' => 'sometimes|required|integer|min:18',
            'min_license_years' => 'sometimes|required|integer|min:0',
            'deposit' => 'sometimes|required|numeric|min:0',
            'status' => 'sometimes|string|in:Disponible,En_maintenance,Loué',
        ]);

        // 3. Mise à jour du véhicule avec les nouvelles données
        $vehicle->update($validated);

        // 4. Réponse JSON avec le véhicule mis à jour
        return response()->json([
            'message' => 'Véhicule mis à jour avec succès.',
            'vehicle' => $vehicle
        ], 200);
    }

    /**
     * Supprime un véhicule spécifique.
     */
    public function destroy(string $id)
    {
        // 1. On cherche le véhicule
        $vehicle = Vehicle::find($id);

        // 2. Si le véhicule n'existe pas (ou a déjà été supprimé)
        if (!$vehicle) {
            return response()->json([
                'message' => 'Véhicule introuvable.'
            ], 404);
        }

        // 3. On le supprime de la base de données
        $vehicle->delete();

        // 4. On retourne un message de confirmation
        return response()->json([
            'message' => 'Véhicule supprimé avec succès.'
        ], 200);
    }
}