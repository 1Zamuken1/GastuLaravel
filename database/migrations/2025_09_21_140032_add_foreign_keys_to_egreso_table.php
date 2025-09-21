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
        Schema::table('egreso', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'egreso_ibfk_1')->references(['usuario_id'])->on('usuario')->onUpdate('cascade')->onDelete('restrict');
            $table->foreign(['concepto_egreso_id'], 'egreso_ibfk_2')->references(['concepto_egreso_id'])->on('concepto_egreso')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('egreso', function (Blueprint $table) {
            $table->dropForeign('egreso_ibfk_1');
            $table->dropForeign('egreso_ibfk_2');
        });
    }
};
