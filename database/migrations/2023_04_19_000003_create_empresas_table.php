<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('empresas', function (Blueprint $table) {
        $table->id();
        $table->string('nombre', 128);
        $table->string('descripcion', 256);
        $table->string('cif', 9);
        $table->string('direccion', 256);
        $table->string('representante_legal', 150);
        $table->string('email', 45);
        $table->string('movil', 9);
        $table->string('nif_representante_legal', 9);
        $table->string('nif_tutor_laboral', 9);
        $table->string('localidad', 128)->default('Desconocida');
        $table->string('persona_contacto', 128)->default('Desconocida');
        $table->string('correo_contacto', 128)->default('Desconocido');
        $table->string('telefono_contacto', 9)->default('Desconocido');
        $table->string('web', 50)->default('Desconocida');
        $table->string('logo', 255);
        $table->string('token', 64)->default('');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};
