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
        Schema::create('concepto_egreso', function (Blueprint $table) {
            $table->bigIncrements('concepto_egreso_id');
            $table->string('nombre', 30);
            $table->string('descripcion', 100);
            $table->unsignedBigInteger('usuario_id')->index('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('concepto_egreso');
    }
};
