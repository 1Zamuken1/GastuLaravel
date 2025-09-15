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
        Schema::table('proyeccion_ingreso', function (Blueprint $table) {
            $table->foreign(['concepto_ingreso_id'], 'proyeccion_ingreso_ibfk_1')->references(['concepto_ingreso_id'])->on('concepto_ingreso')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('proyeccion_ingreso', function (Blueprint $table) {
            $table->dropForeign('proyeccion_ingreso_ibfk_1');
        });
    }
};
