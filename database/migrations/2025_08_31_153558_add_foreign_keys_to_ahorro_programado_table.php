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
        Schema::table('ahorro_programado', function (Blueprint $table) {
            $table->foreign(['ahorro_meta_id'], 'ahorro_programado_ibfk_1')->references(['ahorro_meta_id'])->on('ahorro_meta')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahorro_programado', function (Blueprint $table) {
            $table->dropForeign('ahorro_programado_ibfk_1');
        });
    }
};
