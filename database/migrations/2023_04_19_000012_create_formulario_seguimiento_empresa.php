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
        Schema::create('formulario_seguimiento_empresa', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 45);
            $table->unsignedBigInteger('empresa_id');
            $table->string('ciclo', 45);
            $table->unsignedBigInteger('alumnado_id');
            $table->enum('competencias_profesionales', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('competencias_organizativas', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('competencias_relacionales', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('capacidad_de_respuesta_a_las_contingencias', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('capacidad_de_aprendizaje', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('cumplimiento_de_las_normas', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->string('actividades_formativas_y_tareas_realizadas_en_la_empresa', 255);
            $table->string('observaciones_sobre_competencias_profesionales', 255);
            $table->string('observaciones_sobre_competencias_personales_y_sociales', 255);
            $table->string('sugerencias_generales_de_mejora', 255);
            $table->foreign('empresa_id')->references('id')->on('empresas')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign('alumnado_id')->references('id')->on('alumnado')->onUpdate('cascade')->onDelete('restrict');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('formulario_seguimiento_empresa');
    }
};
