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
        Schema::table('rol_permiso', function (Blueprint $table) {
            $table->foreign(['rol_id'], 'rol_permiso_ibfk_1')->references(['rol_id'])->on('rol')->onUpdate('cascade')->onDelete('cascade');
            $table->foreign(['permiso_id'], 'rol_permiso_ibfk_2')->references(['permiso_id'])->on('permiso')->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rol_permiso', function (Blueprint $table) {
            $table->dropForeign('rol_permiso_ibfk_1');
            $table->dropForeign('rol_permiso_ibfk_2');
        });
    }
};
