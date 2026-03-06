<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Vehicle;

class VehicleApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test : N'importe qui peut voir le catalogue de véhicules (Public)
     */
    public function test_anyone_can_fetch_all_vehicles()
    {
        $response = $this->getJson('/api/vehicles');
        $response->assertStatus(200);
    }

    /**
     * Test : Un administrateur connecté peut ajouter un nouveau véhicule.
     */
    public function test_admin_can_create_vehicle()
    {
        // Création d'un faux Admin
        $admin = User::factory()->create(['role' => 'admin']);

        // Création d'un véhicule en tant qu'Admin
        $response = $this->actingAs($admin, 'sanctum')->postJson('/api/vehicles', [
            'brand' => 'Ferrari',
            'model' => 'F8 Tributo',
            'category' => 'Sport',
            'gearbox' => 'Automatique',
            'engine' => 'V8 3.9L Bi-Turbo',
            'power_hp' => 720,
            'acceleration' => 2.9,
            'seats' => 2,
            'daily_price' => 1200,
            'min_age' => 25,
            'min_license_years' => 5,
            'deposit' => 10000,
            'status' => 'Disponible'
        ]);

        // On attend un statut 201 (Créé)
        $response->assertStatus(201);
        
        // Vérification que la Ferrari est bien dans la base de données
        $this->assertDatabaseHas('vehicles', [
            'brand' => 'Ferrari',
            'model' => 'F8 Tributo'
        ]);
    }

    /**
     * Test : Un simple client se fait rejeter s'il essaie d'ajouter un véhicule (Sécurité Middleware).
     */
    public function test_client_cannot_create_vehicle()
    {
        // Création d'un faux Client
        $client = User::factory()->create(['role' => 'client']);

        // Création d'un véhicule en tant que Client
        $response = $this->actingAs($client, 'sanctum')->postJson('/api/vehicles', [
            'brand' => 'Ferrari',
            'model' => 'F8 Tributo',
            'category' => 'Sport',
            'gearbox' => 'Automatique',
            'engine' => 'V8 3.9L Bi-Turbo',
            'power_hp' => 720,
            'acceleration' => 2.9,
            'seats' => 2,
            'daily_price' => 1200,
            'min_age' => 25,
            'min_license_years' => 5,
            'deposit' => 10000,
            'status' => 'Disponible'
        ]);

        // On attend un statut 403 (Accès Refusé)
        $response->assertStatus(403);
    }
}