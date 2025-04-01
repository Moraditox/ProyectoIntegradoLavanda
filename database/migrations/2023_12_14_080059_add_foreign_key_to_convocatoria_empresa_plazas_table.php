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
        Schema::table('convocatoria_empresa_plazas', function (Blueprint $table) {
            $table->foreign('empresa_id', 'fk_convocatoria_empresa_plazas_empresas')->references('id')->on('empresas')->onDelete('cascade');
        });

    }

    public function down()
    {
        Schema::table('convocatoria_empresa_plazas', function (Blueprint $table) {
            $table->dropForeign(['empresa_id']);
        });
    }
};
