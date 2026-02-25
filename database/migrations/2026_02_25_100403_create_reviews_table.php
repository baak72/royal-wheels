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
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();

            // Le client qui laisse l'avis et le véhicule concerné
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            
            $table->integer('rating'); // La note (de 1 à 5)
            $table->text('comment'); // Le commentaire détaillé
            
            // Le statut pour la modération par l'employé
            $table->enum('status', ['En attente', 'Validé', 'Refusé'])->default('En attente');
            
            // L'employé qui a validé ou refusé l'avis
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reviews');
    }
};
