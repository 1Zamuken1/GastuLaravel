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
        Schema::create('proyeccion_ingreso', function (Blueprint $table) {
            $table->bigIncrements('proyeccion_ingreso_id');
            $table->decimal('monto_programado', 12);
            $table->string('descripcion', 100);
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->unsignedBigInteger('concepto_ingreso_id')->index('concepto_ingreso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proyeccion_ingreso');
    }
};
