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
        Schema::create('politicas', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo_empleado', ['administrativo', 'operativo', 'directivo']);
            $table->integer('dias_disponibles_anuales');
            $table->text('reglas_especiales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('politicas');
    }
};
