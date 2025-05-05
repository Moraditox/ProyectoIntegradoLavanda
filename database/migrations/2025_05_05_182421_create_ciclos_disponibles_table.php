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
        Schema::create('ciclos_disponibles', function (Blueprint $table) {
            $table->string('nombre', 10)->primary();
        });

        // Insertar los datos
        DB::table('ciclos_disponibles')->insert([
            ['nombre' => '1 ASIR A'],
            ['nombre' => '1 ASIR B'],
            ['nombre' => '1 DAMP A'],
            ['nombre' => '1 DAMP B'],
            ['nombre' => '1 DAW A'],
            ['nombre' => '1 DAW B'],
            ['nombre' => '2 ASIR A'],
            ['nombre' => '2 ASIR B'],
            ['nombre' => '2 DAMP A'],
            ['nombre' => '2 DAMP B'],
            ['nombre' => '2 DAW A'],
            ['nombre' => '2 DAW B'],
        ]);
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ciclos_disponibles');
    }
};
