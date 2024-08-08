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
        Schema::create('sub_rutas', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            
            $table->foreignId('ruta_id')->constrained('rutas')->onDelete('cascade');
            $table->foreignId('parada_inicio_id')->constrained('paradas')->onDelete('cascade');
            $table->foreignId('parada_final_id')->constrained('paradas')->onDelete('cascade');
            $table->time('tiempo_recorrido');
            $table->json('coordenadas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sub_rutas');
    }
};
