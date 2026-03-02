<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Photo;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    /**
     * Ajoute une nouvelle photo à un véhicule.
     */
    public function store(Request $request, string $vehicleId)
    {
        // 1. Vérifier que le véhicule existe bien
        $vehicle = Vehicle::find($vehicleId);

        if (!$vehicle) {
            return response()->json([
                'message' => 'Véhicule introuvable.'
            ], 404);
        }

        // 2. Validation stricte : fichier de type image, format précis, max 2Mo
        $request->validate([
            'image' => 'required|image|mimes:avif,webp|max:2048',
        ]);

        // 3. Sauvegarde du fichier sur le serveur
        $path = $request->file('image')->store('vehicles', 'public');

        // 4. Détermine si c'est la photo principale (si aucune photo n'existe encore pour ce véhicule, alors c'est la photo principale par défaut)
        $isPrimary = $vehicle->photos()->count() === 0;

        // 5. Enregistre les informations dans la base de données
        $photo = $vehicle->photos()->create([
            'file_path' => $path,
            'is_primary' => $isPrimary,
        ]);

        return response()->json([
            'message' => 'Photo ajoutée avec succès.',
            'photo' => $photo
        ], 201);
    }

    /**
     * Supprime une photo (base de données + fichier physique).
     */
    public function destroy(string $id)
    {
        // 1. Chercher la photo dans la base de données
        $photo = Photo::find($id);

        if (!$photo) {
            return response()->json([
                'message' => 'Photo introuvable.'
            ], 404);
        }

        // 2. Supprimer le fichier physique du serveur
        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        // 3. Supprimer l'entrée de la base de données
        $photo->delete();

        return response()->json([
            'message' => 'Photo supprimée avec succès et fichier retiré du serveur.'
        ], 200);
    }
}