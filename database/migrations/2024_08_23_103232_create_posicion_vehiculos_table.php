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
        Schema::create('posicion_vehiculos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->json('coordenadas')->nullable();
            $table->string('detalle')->nullable();
            $table->enum('direccion',['IDA','RETORNO','N/A'])->default('N/A');
            $table->string('velocidad')->nullable();
            $table->enum('esta_ruta',['SI','NO'])->nullable();
            $table->foreignId('tipo_ruta_id')->nullable()->constrained('tipo_rutas')->onDelete('cascade');
            $table->foreignId('vehiculo_id')->constrained('vehiculos')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posicion_vehiculos');
    }
};
