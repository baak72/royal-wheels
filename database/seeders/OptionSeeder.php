<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Option;

class OptionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $options = [
            [
                'name' => 'Chauffeur privé',
                'price' => 500.00,
                'pricing_type' => 'daily',
            ],
            [
                'name' => 'Livraison à l\'aéroport',
                'price' => 200.00,
                'pricing_type' => 'flat',  // En une fois
            ],
            [
                'name' => 'Assurance Tous Risques',
                'price' => 100.00,
                'pricing_type' => 'daily',
            ]
        ];

        // Boucle pour créer chaque option en base de données
        foreach ($options as $option) {
            Option::create($option);
        }
    }
}