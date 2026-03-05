<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;

class AdminController extends Controller
{
    /**
     * Retourne TOUTES les réservations 
     */
    public function indexReservations()
    {
        // Récupération de toutes les réservations avec leurs relations (user, vehicle, options) pour une vue complète dans l'interface d'administration
        $reservations = Reservation::with(['user', 'vehicle', 'options'])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($reservations, 200);
    }

    /**
     * Modifie le statut d'une réservation
     */
    public function updateReservationStatus(Request $request, $id)
    {
        // 1. Validation du statut
        $validated = $request->validate([
            'status' => 'required|string|in:En attente de validation,Acompte payé,En cours,Terminée,Annulée'
        ]);

        // 2. On cherche la réservation
        $reservation = Reservation::findOrFail($id);

        // 3. Mise à jour et sauvegarde
        $reservation->status = $validated['status'];
        $reservation->save();

        // 4. Charger les relations pour une réponse complète
        $reservation->load(['user', 'vehicle', 'options']);

        return response()->json([
            'message' => "Le statut de la réservation #{$reservation->id} a été mis à jour avec succès.",
            'reservation' => $reservation
        ], 200);
    }
}