<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('vehicles', function (Blueprint $table) {
            $table->id();

            // Informations générales
            $table->string('brand'); // Marque 
            $table->string('model'); // Modèle 
            // Catégories Sport, SUV, Berline 
            $table->enum('category', ['Sport', 'SUV', 'Berline']); 
            // Boîte automatique ou manuelle 
            $table->enum('gearbox', ['Automatique', 'Manuelle']); 
            
            // Caractéristiques techniques
            $table->string('engine'); // Motorisation
            $table->integer('power_hp'); // Puissance en chevaux (ch)
            // Le 0 à 100km/h (ex: 3.5 sec) donc on utilise un nombre à virgule
            $table->decimal('acceleration', 4, 1); 
            $table->integer('seats'); // Nombre de places
            
            // Conditions de location
            $table->decimal('daily_price', 10, 2); // Tarif journalier
            $table->integer('min_age'); // Âge minimum requis
            $table->integer('min_license_years'); // Années de permis obligatoires
            $table->decimal('deposit', 10, 2); // Montant de la caution
            
            // Statut du véhicule (Disponible ou Maintenance)
            $table->enum('status', ['Disponible', 'Maintenance'])->default('Disponible');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehicles');
    }
};
