<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Vehicle;
use App\Models\Photo;

class VehicleSeeder extends Seeder
{
    public function run(): void
    {
        // Catalogue de 12 véhicules de luxe
        $vehicles = [
            // --- CATÉGORIE SPORT ---
            [
                'brand' => 'Ferrari', 'model' => 'F8 Tributo', 'category' => 'Sport', 'gearbox' => 'Automatique',
                'engine' => 'V8 3.9L Bi-Turbo', 'power_hp' => 720, 'acceleration' => 2.9, 'seats' => 2,
                'daily_price' => 1200.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 10000.00,
                'image' => '/images/vehicles/ferrari-f8.webp'
            ],
            [
                'brand' => 'Lamborghini', 'model' => 'Huracán EVO', 'category' => 'Sport', 'gearbox' => 'Automatique',
                'engine' => 'V10 5.2L Atmosphérique', 'power_hp' => 640, 'acceleration' => 2.9, 'seats' => 2,
                'daily_price' => 1300.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 10000.00,
                'image' => '/images/vehicles/lambo-huracan.webp'
            ],
            [
                'brand' => 'Porsche', 'model' => '911 GT3 RS', 'category' => 'Sport', 'gearbox' => 'Automatique',
                'engine' => 'Flat-6 4.0L', 'power_hp' => 525, 'acceleration' => 3.2, 'seats' => 2,
                'daily_price' => 1100.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 8000.00,
                'image' => '/images/vehicles/porsche-911.webp'
            ],
            [
                'brand' => 'McLaren', 'model' => '720S', 'category' => 'Sport', 'gearbox' => 'Automatique',
                'engine' => 'V8 4.0L Bi-Turbo', 'power_hp' => 720, 'acceleration' => 2.9, 'seats' => 2,
                'daily_price' => 1400.00, 'min_age' => 28, 'min_license_years' => 7, 'deposit' => 12000.00,
                'image' => '/images/vehicles/mclaren-720s.webp'
            ],

            // --- CATÉGORIE SUV ---
            [
                'brand' => 'Lamborghini', 'model' => 'Urus', 'category' => 'SUV', 'gearbox' => 'Automatique',
                'engine' => 'V8 4.0L Bi-Turbo', 'power_hp' => 650, 'acceleration' => 3.6, 'seats' => 5,
                'daily_price' => 1100.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 9000.00,
                'image' => '/images/vehicles/lambo-urus.webp'
            ],
            [
                'brand' => 'Mercedes-Benz', 'model' => 'Classe G 63 AMG', 'category' => 'SUV', 'gearbox' => 'Automatique',
                'engine' => 'V8 4.0L Bi-Turbo', 'power_hp' => 585, 'acceleration' => 4.5, 'seats' => 5,
                'daily_price' => 950.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 7000.00,
                'image' => '/images/vehicles/mercedes-g63.webp'
            ],
            [
                'brand' => 'Range Rover', 'model' => 'SVAutobiography', 'category' => 'SUV', 'gearbox' => 'Automatique',
                'engine' => 'V8 5.0L Supercharged', 'power_hp' => 565, 'acceleration' => 5.4, 'seats' => 5,
                'daily_price' => 800.00, 'min_age' => 23, 'min_license_years' => 3, 'deposit' => 5000.00,
                'image' => '/images/vehicles/range-rover.webp'
            ],
            [
                'brand' => 'Bentley', 'model' => 'Bentayga', 'category' => 'SUV', 'gearbox' => 'Automatique',
                'engine' => 'W12 6.0L Bi-Turbo', 'power_hp' => 608, 'acceleration' => 4.1, 'seats' => 5,
                'daily_price' => 1000.00, 'min_age' => 28, 'min_license_years' => 5, 'deposit' => 8000.00,
                'image' => '/images/vehicles/bentley-bentayga.webp'
            ],

            // --- CATÉGORIE BERLINE ---
            [
                'brand' => 'Rolls-Royce', 'model' => 'Phantom', 'category' => 'Berline', 'gearbox' => 'Automatique',
                'engine' => 'V12 6.8L Bi-Turbo', 'power_hp' => 571, 'acceleration' => 5.3, 'seats' => 4,
                'daily_price' => 2000.00, 'min_age' => 30, 'min_license_years' => 7, 'deposit' => 15000.00,
                'image' => '/images/vehicles/rolls-phantom.webp'
            ],
            [
                'brand' => 'Bentley', 'model' => 'Flying Spur', 'category' => 'Berline', 'gearbox' => 'Automatique',
                'engine' => 'W12 6.0L Bi-Turbo', 'power_hp' => 635, 'acceleration' => 3.8, 'seats' => 5,
                'daily_price' => 1200.00, 'min_age' => 28, 'min_license_years' => 5, 'deposit' => 10000.00,
                'image' => '/images/vehicles/bentley-flying.webp'
            ],
            [
                'brand' => 'Mercedes-Maybach', 'model' => 'S 680', 'category' => 'Berline', 'gearbox' => 'Automatique',
                'engine' => 'V12 6.0L Bi-Turbo', 'power_hp' => 612, 'acceleration' => 4.5, 'seats' => 4,
                'daily_price' => 1100.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 8000.00,
                'image' => '/images/vehicles/maybach-s680.webp'
            ],
            [
                'brand' => 'Porsche', 'model' => 'Panamera Turbo S', 'category' => 'Berline', 'gearbox' => 'Automatique',
                'engine' => 'V8 4.0L Bi-Turbo', 'power_hp' => 630, 'acceleration' => 3.1, 'seats' => 4,
                'daily_price' => 900.00, 'min_age' => 25, 'min_license_years' => 5, 'deposit' => 7000.00,
                'image' => '/images/vehicles/porsche-panamera.webp'
            ]
        ];

        // Insertion de chaque véhicule dans la base de données
        foreach ($vehicles as $data) {
            // Extraction de l'image car elle ne va pas dans la table 'vehicles'
            $imagePath = $data['image'];
            unset($data['image']); // Suppression de l'image des données du véhicule
            
            // 1. On crée le véhicule
            $vehicle = Vehicle::create($data);
            
            // 2. On crée la photo principale liée à ce véhicule
            Photo::create([
                'vehicle_id' => $vehicle->id,
                'file_path' => $imagePath,
                'is_primary' => true // Photo de couverture
            ]);
        }
    }
}