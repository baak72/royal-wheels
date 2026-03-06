<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;
use App\Models\Reservation;

class AdminApiTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;
    protected $client;
    protected $vehicle;
    protected $reservation;

    /**
     * Préparation du terrain avant chaque test
     */
    protected function setUp(): void
    {
        parent::setUp();
        
        // Un Admin
        $this->admin = User::factory()->create(['role' => 'admin']);
        
        // Un Client "Actif"
        $this->client = User::factory()->create([
            'role' => 'client',
            'is_active' => true
        ]);
        
        // Un Véhicule
        $this->vehicle = Vehicle::create([
            'brand' => 'Lamborghini',
            'model' => 'Huracan',
            'category' => 'Sport',
            'gearbox' => 'Automatique',
            'engine' => 'V10',
            'power_hp' => 640,
            'acceleration' => 2.9,
            'seats' => 2,
            'daily_price' => 1500,
            'min_age' => 25,
            'min_license_years' => 5,
            'deposit' => 15000,
            'status' => 'Disponible'
        ]);

        // Une Réservation "En attente de validation"
        $this->reservation = Reservation::create([
            'user_id' => $this->client->id,
            'vehicle_id' => $this->vehicle->id,
            'start_date' => '2026-08-01',
            'end_date' => '2026-08-05',
            'base_price' => 7500,
            'total_price' => 7500,
            'discount' => 0,
            'deposit_amount' => 2250,
            'balance' => 5250,
            'status' => 'En attente de validation'
        ]);
    }

    /**
     * Test : L'Admin peut changer le statut d'une réservation.
     */
    public function test_admin_can_update_reservation_status()
    {
        // L'Admin passe la réservation en "Acompte payé"
        $response = $this->actingAs($this->admin, 'sanctum')->patchJson("/api/admin/reservations/{$this->reservation->id}/status", [
            'status' => 'Acompte payé'
        ]);

        $response->assertStatus(200);

        // Vérification en base de données
        $this->assertDatabaseHas('reservations', [
            'id' => $this->reservation->id,
            'status' => 'Acompte payé'
        ]);
    }

    /**
     * Test : L'Admin peut désactiver le compte d'un client (Bannissement).
     */
    public function test_admin_can_deactivate_client_account()
    {
        // L'Admin désactive le client
        $response = $this->actingAs($this->admin, 'sanctum')->patchJson("/api/admin/users/{$this->client->id}/status");

        $response->assertStatus(200);

        // Vérication que la colonne is_active est bien passée à false (0 en base)
        $this->assertDatabaseHas('users', [
            'id' => $this->client->id,
            'is_active' => false
        ]);
    }
}