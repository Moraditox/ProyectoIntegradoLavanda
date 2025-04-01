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
        Schema::create('curso_academico', function (Blueprint $table) {
            $table->id();
            // $table->string('anno_academico'); Lo quitamos para que los cursos académicos sean únicos
            $table->string('ciclo');
            $table->enum('curso', ['1', '2']);
            $table->string('grupo', 1);
            $table->enum('turno', ['Mañana', 'Tarde']);
            // $table->foreign('anno_academico')->references('anno')->on('anno_academico')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('ciclo')->references('ciclo')->on('ciclos')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('curso_academico');
    }
};
