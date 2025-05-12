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
        // Crear la tabla convocatoria_empresas
        Schema::create('convocatoria_empresas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocatoria_id');
            $table->unsignedBigInteger('empresa_id');
            $table->unsignedBigInteger('alumno_referencia_id')->nullable();
            $table->unsignedBigInteger('profesor_referencia_id')->nullable();
            $table->text('observaciones')->nullable();
            $table->foreign('convocatoria_id')->references('id')->on('convocatorias')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('alumno_referencia_id')->references('id')->on('alumnado')->onUpdate('cascade')->onDelete('set null');
            $table->foreign('profesor_referencia_id')->references('id')->on('profesorado')->onUpdate('cascade')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Eliminar la tabla convocatoria_empresas
        Schema::dropIfExists('convocatoria_empresas');
    }
};
