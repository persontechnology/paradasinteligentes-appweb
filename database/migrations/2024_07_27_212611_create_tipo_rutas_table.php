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
        Schema::create('tipo_rutas', function (Blueprint $table) {
            $table->id();
            
            $table->enum('tipo', ['IDA', 'RETORNO']);
            $table->foreignId('ruta_id')->constrained()->onDelete('cascade');
            $table->string('inicio')->nullable();
            $table->string('finaliza')->nullable();
            $table->string('tiempo_total')->nullable();
            $table->text('detalle_recorrido')->nullable();
            $table->json('coordenadas')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_rutas');
    }
};
