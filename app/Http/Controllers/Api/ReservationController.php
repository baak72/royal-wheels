<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Reservation;
use App\Models\Vehicle;
use Carbon\Carbon;

class ReservationController extends Controller
{
    /**
     * Retourne l'historique des réservations du client connecté.
     */
    public function index(Request $request)
    {
        // 1. On ne ramène que les réservations de l'utilisateur connecté
        $reservations = Reservation::with('vehicle') // Les infos de la voiture
            ->where('user_id', $request->user()->id) // Uniquement SON historique
            ->orderBy('created_at', 'desc') // Triage pour avoir les plus récentes en haut de la liste
            ->get();

        // 2. Envoie un JSON de toutes les réservations du client
        return response()->json($reservations, 200);
    }

    /**
     * Crée une nouvelle réservation.
     */
    public function store(Request $request)
    {
        // 1. Vérification des données envoyées par le Front-end
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date' => 'required|date|after:start_date',
        ]);

        // 2. Récupération de la voiture demandée pour lire son prix
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        
        // 3. Le calcul des dates avec Carbon
        $start = Carbon::parse($validated['start_date']);
        $end = Carbon::parse($validated['end_date']);
        
        $days = $start->diffInDays($end) + 1;
        
        $totalPrice = $days * $vehicle->daily_price;

        // 4. Création de la réservation en base de données
        $reservation = Reservation::create([
            'user_id' => $request->user()->id, 
            'vehicle_id' => $vehicle->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'total_price' => $totalPrice,
            'status' => 'pending', // 'En attente' par défaut
        ]);

        // 5. Renvoie un JSON de succès avec les détails
        return response()->json([
            'message' => 'Réservation créée avec succès !',
            'days_rented' => $days,
            'daily_price' => (float) $vehicle->daily_price,
            'total_price' => $totalPrice,
            'reservation' => $reservation
        ], 201);
    }
}