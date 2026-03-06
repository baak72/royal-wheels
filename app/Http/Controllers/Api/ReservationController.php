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
     * Crée une nouvelle réservation avec options (Sécurisée avec Transaction).
     */
    public function store(Request $request)
    {
        // 1. Validation
        $validated = $request->request->add(['options' => $request->options]); 
        $validated = $request->validate([
            'vehicle_id' => 'required|exists:vehicles,id',
            'start_date' => 'required|date|after_or_equal:today',
            'end_date'   => 'required|date|after:start_date',
            'options'    => 'nullable|array',
            'options.*'  => 'exists:options,id',
        ]);

        $user = $request->user();
        $vehicle = \App\Models\Vehicle::findOrFail($validated['vehicle_id']);
        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);

        // 2 & 3. Règles Métiers : Profil et Éligibilité
        if (!$user->birth_date || !$user->license_date) {
            return response()->json(['message' => 'Action refusée : Veuillez compléter votre profil.'], 403);
        }

        if ($user->birth_date->age < $vehicle->min_age || $user->license_date->diffInYears(now()) < $vehicle->min_license_years) {
            return response()->json(['message' => 'Éligibilité refusée : Âge ou permis insuffisant.'], 403);
        }

        // 4. Règle Métier : Chevauchement
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

        // =========================================================
        // 5. CALCUL FINANCIER COMPLET
        // =========================================================
        $days = $start->diffInDays($end) + 1;
        
        $vehicleBasePrice = $days * $vehicle->daily_price;
        $discountPercent = $days >= 7 ? 20 : ($days >= 3 ? 10 : 0);
        $discountAmount = ($vehicleBasePrice * $discountPercent) / 100;
        $vehicleFinalPrice = $vehicleBasePrice - $discountAmount;

        $optionsTotal = 0;
        $options = collect(); // On initialise une collection vide par défaut
        
        if (!empty($validated['options'])) {
            $options = \App\Models\Option::whereIn('id', $validated['options'])->get();
            foreach ($options as $option) {
                // Si 'daily', on multiplie par le nb de jours. Si 'flat', on prend le prix fixe.
                $optionsTotal += $option->pricing_type === 'daily' ? ($option->price * $days) : $option->price;
            }
        }

        $grandTotal = $vehicleFinalPrice + $optionsTotal;
        $depositAmount = $grandTotal * 0.30;
        $balance = $grandTotal - $depositAmount;

        // =========================================================
        // 6 & 7. ENREGISTREMENT SÉCURISÉ (Transaction BD)
        // =========================================================
        // Tout ce qui est dans ce bloc réussit en entier, ou échoue en entier (garantissant l'intégrité des données)
        $reservation = \Illuminate\Support\Facades\DB::transaction(function () use ($user, $vehicle, $start, $end, $vehicleBasePrice, $optionsTotal, $discountPercent, $depositAmount, $balance, $validated, $options, $grandTotal) {
            
            // A. Création de la réservation
            $newReservation = \App\Models\Reservation::create([
                'user_id' => $user->id,
                'vehicle_id' => $vehicle->id,
                'start_date' => $start->toDateString(),
                'end_date' => $end->toDateString(),
                'status' => 'En attente de validation',
                'base_price' => $vehicleBasePrice + $optionsTotal,
                'total_price' => $grandTotal,
                'discount' => $discountPercent,
                'deposit_amount' => $depositAmount,
                'balance' => $balance,
            ]);

            // B. Attachement des options avec le prix historique
            if (!empty($validated['options'])) {
                $pivotData = [];
                foreach ($options as $option) {
                    $pivotData[$option->id] = ['historic_price' => $option->price];
                }
                $newReservation->options()->attach($pivotData);
            }

            return $newReservation;
        });

        // On recharge les relations pour la réponse JSON
        $reservation->load(['vehicle', 'options']);

        return response()->json([
            'message' => 'Réservation premium créée avec succès et sécurisée !',
            'financial_details' => [
                'days' => $days,
                'vehicle_price_after_discount' => $vehicleFinalPrice,
                'options_total' => $optionsTotal,
                'grand_total' => $grandTotal,
                'deposit_to_pay_now' => $depositAmount,
                'balance_due' => $balance,
            ],
            'reservation' => $reservation
        ], 201);
    }
}