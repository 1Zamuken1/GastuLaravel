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
        Schema::table('ahorro_meta', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'ahorro_meta_ibfk_1')->references(['usuario_id'])->on('usuario')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ahorro_meta', function (Blueprint $table) {
            $table->dropForeign('ahorro_meta_ibfk_1');
        });
    }
};
