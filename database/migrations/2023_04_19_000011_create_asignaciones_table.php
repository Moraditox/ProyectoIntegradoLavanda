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
        Schema::create('asignaciones', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocatoria_id');
            $table->unsignedBigInteger('alumnado_id');
            $table->unsignedBigInteger('profesores_id');
            $table->unsignedBigInteger('trabajadores_id');
            $table->unsignedBigInteger('empresa_id');
            $table->foreign('convocatoria_id')->references('id')->on('convocatorias')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('alumnado_id')->references('id')->on('alumnado')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('profesores_id')->references('id')->on('profesores')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('trabajadores_id')->references('id')->on('trabajadores')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('empresa_id')->references('id')->on('empresas')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asignaciones');
    }
};
