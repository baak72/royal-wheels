<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;

class AuthApiTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test : Un nouvel utilisateur peut s'inscrire.
     */
    public function test_user_can_register()
    {
        $response = $this->postJson('/api/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@test.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'birth_date' => '1990-01-01',
            'license_date' => '2010-01-01'
        ]);

        // On s'attend à un code 201 (Created)
        $response->assertStatus(201);
        
        // Vérification que la réponse contient bien les bonnes clés !
        $response->assertJsonStructure([
            'message',
            'user',
            'access_token',
            'token_type'
        ]);
        
        // Vérification que l'utilisateur est bien dans la base de données virtuelle
        $this->assertDatabaseHas('users', [
            'email' => 'john@test.com'
        ]);
    }

    /**
     * Test : Un utilisateur existant peut se connecter et recevoir un Token.
     */
    public function test_user_can_login()
    {
        // Création d'un faux utilisateur dans la base de test
        $user = User::factory()->create([
            'email' => 'jane@test.com',
            'password' => bcrypt('password123')
        ]);

        // Tentative de connexion avec ses identifiants
        $response = $this->postJson('/api/login', [
            'email' => 'jane@test.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'message', 
            'user', 
            'access_token',
            'token_type'
        ]);
    }

    /**
     * Test : Un utilisateur connecté peut se déconnecter (Révocation du Token).
     */
    public function test_user_can_logout()
    {
        // Création d'un utilisateur
        $user = User::factory()->create();

        // Génération d'un vrai Token Sanctum
        $token = $user->createToken('test-token')->plainTextToken;

        // Requête POST en passant le Token dans le Header (comme sur Postman)
        $response = $this->withHeaders([
            'Authorization' => 'Bearer ' . $token
        ])->postJson('/api/logout');

        // Vérification que la déconnexion a réussi
        $response->assertStatus(200);
        $response->assertJson(['message' => 'Déconnexion réussie. Le token a été détruit.']);
        
        // Vérifiaction que le Token a bien été révoqué
        $this->assertDatabaseCount('personal_access_tokens', 0);
    }
}