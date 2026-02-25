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
        Schema::create('photos', function (Blueprint $table) {
            $table->id();

            // Le véhicule concerné
            $table->foreignId('vehicle_id')->constrained()->cascadeOnDelete();
            
            $table->string('file_path'); // L'URL de l'image
            
            // Est-ce la photo principale de la carte ou juste une photo du carrousel ?
            $table->boolean('is_primary')->default(false); 
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
