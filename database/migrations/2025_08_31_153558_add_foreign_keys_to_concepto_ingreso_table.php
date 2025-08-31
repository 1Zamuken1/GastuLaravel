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
        Schema::table('concepto_ingreso', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'concepto_ingreso_ibfk_1')->references(['usuario_id'])->on('usuario')->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('concepto_ingreso', function (Blueprint $table) {
            $table->dropForeign('concepto_ingreso_ibfk_1');
        });
    }
};
