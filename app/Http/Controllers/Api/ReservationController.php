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
        // 1. Validation des données envoyées par le Front-end
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
        ]);

        $user = $request->user();
        $vehicle = Vehicle::findOrFail($validated['vehicle_id']);
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);

        // 2. RÈGLE MÉTIER : Vérification du profil complet
        if (!$user->birth_date || !$user->license_date) {
            return response()->json(['message' => 'Action refusée : Veuillez compléter votre profil (date de naissance et permis).'], 403);
        }

        // 3. RÈGLE MÉTIER : Vérification de l'éligibilité (Âge et Permis)
        $age = $user->birth_date->age;
        $licenseYears = $user->license_date->diffInYears(now());

        if ($age < $vehicle->min_age || $licenseYears < $vehicle->min_license_years) {
            return response()->json(['message' => 'Éligibilité refusée : Vous ne remplissez pas les conditions d\'âge ou de permis pour ce véhicule.'], 403);
        }

        // 4. RÈGLE MÉTIER : Disponibilité du véhicule
        $overlapping = \App\Models\Reservation::where('vehicle_id', $vehicle->id)
            ->where('status', '!=', 'Annulée')
            ->where(function ($query) use ($start, $end) {
                $query->whereBetween('start_date', [$start, $end])
                      ->orWhereBetween('end_date', [$start, $end])
                      ->orWhere(function ($q) use ($start, $end) {
                          $q->where('start_date', '<=', $start)->where('end_date', '>=', $end);
                      });
            })->exists();

        if ($overlapping) {
            return response()->json(['message' => 'Désolé, ce véhicule est déjà réservé pour ces dates.'], 409);
        }

        // 5. RÈGLE MÉTIER : Tarification dégressive et Acompte
        $days = $start->diffInDays($end) + 1; // +1 pour inclure le premier ET le dernier jour
        $basePrice = $days * $vehicle->daily_price;

        $discountPercent = 0;
        if ($days >= 7) {
            $discountPercent = 20;
        } elseif ($days >= 3) {
            $discountPercent = 10;
        }

        $discountAmount = ($basePrice * $discountPercent) / 100;
        $totalPrice = $basePrice - $discountAmount;

        // Acompte de 30% obligatoire
        $depositAmount = $totalPrice * 0.30;
        $balance = $totalPrice - $depositAmount;

        // 6. Création de la réservation en base
        $reservation = \App\Models\Reservation::create([
            'user_id' => $user->id,
            'vehicle_id' => $vehicle->id,
            'start_date' => $start->toDateString(),
            'end_date' => $end->toDateString(),
            'status' => 'En attente de validation',
            'base_price' => $basePrice,
            'discount' => $discountPercent,
            'deposit_amount' => $depositAmount,
            'balance' => $balance,
        ]);

        return response()->json([
            'message' => 'Réservation créée avec succès !',
            'details' => [
                'days_rented' => $days,
                'base_price' => $basePrice,
                'discount_applied' => $discountPercent . '%',
                'total_price' => $totalPrice,
                'deposit_to_pay_now' => $depositAmount,
                'balance_due_on_site' => $balance,
            ],
            'reservation' => $reservation
        ], 201);
    }
}