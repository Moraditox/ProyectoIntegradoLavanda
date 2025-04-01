<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('convocatoria_empresa_plazas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('convocatoria_id');
            $table->unsignedBigInteger('curso_academico_id')->nullable();

            $table->timestamps();

            $table->foreign('convocatoria_id')
                ->references('id')
                ->on('convocatorias')
                ->onDelete('cascade');

            $table->foreign('curso_academico_id')
                ->references('id')
                ->on('curso_academico')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('convocatoria_empresa_plazas');
    }
};
