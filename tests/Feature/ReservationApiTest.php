<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;

class ReservationApiTest extends TestCase
{
    use RefreshDatabase;

    protected $client;
    protected $vehicle;

    /**
     * Préparation des données avant le test (un client et une voiture).
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Préparation du client
        $this->client = User::factory()->create(['role' => 'client']);
        
        // Préparation de la voiture
        $this->vehicle = Vehicle::create([
            'brand' => 'Porsche',
            'model' => '911 GT3',
            'category' => 'Sport',
            'gearbox' => 'Automatique',
            'engine' => 'Flat-6',
            'power_hp' => 510,
            'acceleration' => 3.4,
            'seats' => 2,
            'daily_price' => 1000,
            'min_age' => 25,
            'min_license_years' => 5,
            'deposit' => 8000,
            'status' => 'Disponible'
        ]);
    }

    /**
     * Test : Un client peut réserver une voiture et le calcul de l'acompte est correct.
     */
    public function test_client_can_create_reservation()
    {
        // Le client tente de réserver la Porsche du 10 au 12 juin (3 jours)
        $response = $this->actingAs($this->client, 'sanctum')->postJson('/api/reservations', [
            'vehicle_id' => $this->vehicle->id,
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-12',
            'options' => [] // Pas d'option pour ce test
        ]);

        // Vérification que la réservation a bien été créée (201)
        $response->assertStatus(201);

        // Vérification des mathématiques dans la base de données
        $this->assertDatabaseHas('reservations', [
            'vehicle_id' => $this->vehicle->id,
            'user_id' => $this->client->id,
            'deposit_amount' => 810, 
            'status' => 'En attente de validation'
        ]);
    }

    /**
     * Test : Impossible de réserver un véhicule déjà pris aux mêmes dates (Surbooking).
     */
    public function test_cannot_book_already_reserved_vehicle()
    {
        // Création de la première réservation dans la base (du 10 au 15 juin)
        $this->actingAs($this->client, 'sanctum')->postJson('/api/reservations', [
            'vehicle_id' => $this->vehicle->id,
            'start_date' => '2026-06-10',
            'end_date' => '2026-06-15'
        ]);

        // Un autre client tente de réserver pendant cette même période (du 12 au 14 juin)
        $otherClient = User::factory()->create(['role' => 'client']);
        
        $response = $this->actingAs($otherClient, 'sanctum')->postJson('/api/reservations', [
            'vehicle_id' => $this->vehicle->id,
            'start_date' => '2026-06-12',
            'end_date' => '2026-06-14'
        ]);

        // On attend une erreur de confict (409) avec un message d'erreur spécifique
        $response->assertStatus(409);
        $response->assertJsonFragment([
            'message' => 'Désolé, ce véhicule est déjà réservé pour ces dates.'
        ]);
    }
}