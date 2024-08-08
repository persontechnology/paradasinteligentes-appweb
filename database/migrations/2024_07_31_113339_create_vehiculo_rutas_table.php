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
        Schema::create('vehiculo_rutas', function (Blueprint $table) {
            $table->id();
            $table->json('dias_activos')->nullable();
            $table->timestamps();
            $table->foreignId('vehiculo_id')->nullable()->constrained('vehiculos')->onDelete('cascade');
            $table->foreignId('ruta_id')->nullable()->constrained('rutas')->onDelete('cascade');
            
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculo_rutas');
    }
};
