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
        Schema::create('vehiculos', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->string('codigo');
            $table->string('placa')->nullable();
            $table->string('marca')->nullable();
            $table->string('modelo')->nullable();
            $table->string('nombre_cooperativa')->nullable();
            $table->string('foto')->nullable();
            $table->string('descripcion')->nullable();
            $table->enum('estado',['ACTIVO','INACTIVO'])->nullable();
            $table->json('ubicacion_actual')->nullable(); 
            $table->foreignId('coductor_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('ayudante_id')->nullable()->constrained('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vehiculos');
    }
};
