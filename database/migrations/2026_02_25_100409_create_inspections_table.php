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
        Schema::create('inspections', function (Blueprint $table) {
            $table->id();

            // La réservation concernée et l'employé qui réalise l'état des lieux
            $table->foreignId('reservation_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // Ici, user_id représente l'employé
            
            // Est-ce le départ ou le retour ?
            $table->enum('type', ['Retrait', 'Retour']);
            
            // Les données de l'état des lieux
            $table->integer('mileage'); // Kilométrage
            $table->string('fuel_level'); // Niveau d'essence (ex: "100%", "Moitié", "Réserve")
            $table->text('damages')->nullable(); // Signalement de rayures/chocs
            $table->text('remarks')->nullable(); // Pour justifier un retard ou autre problème
            
            // Montant de pénalité (uniquement s'il y a des dommages au retour ou si le véhicule n'est pas rendu à temps)
            $table->decimal('penalty_amount', 10, 2)->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspections');
    }
};
