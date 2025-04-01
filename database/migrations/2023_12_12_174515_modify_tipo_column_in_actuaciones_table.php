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
        Schema::table('actuaciones', function (Blueprint $table) {
            $table->enum('tipo', ['Automático', 'Manual'])->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('actuaciones', function (Blueprint $table) {
            $table->enum('tipo', ['Formulario Seguimiento', 'Documento'])->change();
        });
    }
};
