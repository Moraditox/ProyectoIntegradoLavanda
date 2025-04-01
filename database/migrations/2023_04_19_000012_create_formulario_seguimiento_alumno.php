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
        Schema::create('formulario_seguimiento_alumno', function (Blueprint $table) {
            $table->id();
            $table->string('correo', 45);
            $table->unsignedBigInteger('empresa_id');
            $table->string('ciclo', 45);
            $table->unsignedBigInteger('alumnado_id');
            $table->string('actividades_formativas_y_tareas_que_realiza_la_empresa', 255);
            $table->string('actividades_y_tareas_que_estas_realizando_en_la_empresa', 255);
            $table->enum('posibilidades_formativas_que_ofrece_la_empresa', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('cumplimiento_del_programa_formativo_por_parte_de_la_empresa', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('seguimiento_realizado_por_el_tutor_del_centro_de_trabajo', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('seguimiento_hecho_por_tu_profesor', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('adecuacion__formacion_recibida_en_centro_docente_con_practicas', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->enum('integracion_en_el_entorno_laboral', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
            $table->string('observaciones', 255);
            $table->string('sugerencias_de_mejora', 255);
            $table->enum('valoracion_general_de_las_practicas', ['Nada satisfecho', 'Poco satisfecho', 'Satisfecho', 'Muy satisfecho']);
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
        Schema::dropIfExists('formulario_seguimiento_alumno');
    }
};
