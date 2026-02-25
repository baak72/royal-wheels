<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Les Seeders dans l'ordre logique
        $this->call([
            UserSeeder::class,    // D'abord les utilisateurs (Admin, Employé)
            OptionSeeder::class,  // Ensuite les options du catalogue
            VehicleSeeder::class, // Enfin, nos 12 voitures.
        ]);
    }
}