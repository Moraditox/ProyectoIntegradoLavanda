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
        Schema::create('matricula', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('alumno_id');
            $table->unsignedBigInteger('curso_academico_id');
            $table->string('anno_academico');
            $table->foreign('alumno_id')->references('id')->on('alumnado')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('curso_academico_id')->references('id')->on('curso_academico')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('anno_academico')->references('anno')->on('anno_academico')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matricula');
    }
};
