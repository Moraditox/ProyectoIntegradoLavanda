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
    public function up(): void
    {
        Schema::create('alumnado', function (Blueprint $table) {
            $table->id();
            $table->string('apellido1', 45);
            $table->string('apellido2', 45);
            $table->string('nombre', 45);
            $table->string('nie', 45);
            $table->string('email_corporativo', 45);
            $table->string('email_personal', 45);
            $table->string('dni', 9);
            $table->string('movil', 12);
            $table->string('imagen', 255);
            $table->string('token', 64)->default('');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alumnado');
    }
};
