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
        Schema::create('actuaciones', function (Blueprint $table) {
            $table->id();
            $table->enum('emisor', ['Tutor Laboral', 'Docente', 'Alumno']);
            $table->enum('tipo', ['Formulario Seguimiento', 'Documento']);
            $table->string('observaciones', 255);
            $table->unsignedBigInteger('informe_alumno_id')->nullable();
            $table->unsignedBigInteger('informe_empresa_id')->nullable();
            $table->unsignedBigInteger('asignacion_id')->nullable();
            $table->foreign('informe_alumno_id')->references('id')->on('formulario_seguimiento_alumno')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('informe_empresa_id')->references('id')->on('formulario_seguimiento_empresa')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('asignacion_id')->references('id')->on('asignaciones')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('actuaciones');
    }
};
