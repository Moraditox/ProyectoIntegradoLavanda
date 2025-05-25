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
        Schema::create('curso_academico_alumno', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('curso_academico_id');
            $table->string('ciclo_nombre', 10);
            $table->unsignedBigInteger('alumno_id');

            $table->foreign('curso_academico_id')->references('id')->on('cursos_academicos')->onDelete('cascade');
            $table->foreign('ciclo_nombre')->references('nombre')->on('ciclos_disponibles')->onDelete('cascade');
            $table->foreign('alumno_id')->references('id')->on('alumnado')->onDelete('cascade');

            $table->unique(['curso_academico_id', 'alumno_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_academico_alumno');
    }
};
