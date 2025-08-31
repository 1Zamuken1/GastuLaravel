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
        Schema::table('mensajes_app', function (Blueprint $table) {
            $table->foreign(['usuario_id'], 'mensajes_app_ibfk_1')->references(['usuario_id'])->on('usuario')->onUpdate('cascade')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mensajes_app', function (Blueprint $table) {
            $table->dropForeign('mensajes_app_ibfk_1');
        });
    }
};
