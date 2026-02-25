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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();

            // Le client à qui appartient le document
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); 
            
            $table->enum('type', ['permis', 'cni']);
            $table->string('file_path'); // Le chemin où sera rangé le fichier
            
            // Le statut de validation par l'employé
            $table->enum('status', ['En attente', 'Validé', 'Refusé'])->default('En attente');
            
            // L'employé qui a validé (nullable au début car personne ne l'a encore validé)
            $table->foreignId('validator_id')->nullable()->constrained('users')->nullOnDelete();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
