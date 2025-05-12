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
        Schema::create('oferta_plazas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('relacion_convocatoria_empresa_id');
            $table->string('especialidad');
            $table->integer('plazas');
            $table->string('observaciones')->nullable();
            $table->foreign('relacion_convocatoria_empresa_id')->references('id')->on('convocatoria_empresas')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferta_plazas');
    }
};
