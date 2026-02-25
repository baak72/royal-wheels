<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash; // L'outil pour crypter les mots de passe

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Création du compte Administrateur
        User::create([
            'first_name' => 'Jean',
            'last_name' => 'Patron',
            'email' => 'admin@royalwheels.fr',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Création d'un compte Employé
        User::create([
            'first_name' => 'Marc',
            'last_name' => 'Agent',
            'email' => 'employe@royalwheels.fr',
            'password' => Hash::make('password'),
            'role' => 'employee',
        ]);

        // 3. Création d'un compte Client de test complet
        User::create([
            'first_name' => 'Sophie',
            'last_name' => 'Cliente',
            'email' => 'client@royalwheels.fr',
            'password' => Hash::make('password'),
            'role' => 'client',
            'address' => '123 Avenue des Champs-Élysées, 75008 Paris',
            'birth_date' => '1990-05-15',
            'license_date' => '2010-08-20',
        ]);
    }
}