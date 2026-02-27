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
            
            // 1. Les clés étrangères
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('vehicle_id')->constrained()->onDelete('cascade');
            
            // 2. Les dates
            $table->date('start_date');
            $table->date('end_date');
            
            // 3. La facturation
            $table->decimal('total_price', 10, 2);
            
            // 4. Le status
            $table->string('status')->default('pending');
            
            $table->timestamps();
        });
    }
};
