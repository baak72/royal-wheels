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
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();

            // 1. LES CLÉS ÉTRANGÈRES
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();

            // 2. LES DATES
            $table->date('start_date');
            $table->date('end_date');

            // 3. LE STATUT
            $table->enum('status', [
                'En attente de validation',
                'Acompte payé',
                'En cours',
                'Terminée',
                'Annulée'
            ])->default('En attente de validation');

            // 4. LA PARTIE FINANCIÈRE
            $table->decimal('base_price', 10, 2); // prix avant réduction
            $table->decimal('total_price', 10, 2); // Prix après réduction
            // La réduction peut être de 0, 10 ou 20 selon la durée
            $table->integer('discount')->default(0); 
            $table->decimal('deposit_amount', 10, 2); // acompte de 30% obligatoire
            $table->decimal('balance', 10, 2); // solde restant à payer sur place
            
            // 5. LES DOCUMENTS
            $table->string('invoice_link')->nullable(); // lien_facture (qui sera généré en PDF à la fin de la location)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};