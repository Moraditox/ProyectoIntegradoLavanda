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
        Schema::create('convocatoria_cursos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocatoria_id');
            $table->unsignedBigInteger('curso_academico_id');
            $table->foreign('convocatoria_id')->references('id')->on('convocatorias')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('curso_academico_id')->references('id')->on('curso_academico')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatoria_cursos');
    }
};
