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
            $table->string('descripcion', 200);
            $table->string('frecuencia', 30);
            $table->integer('dia_recurrencia')->nullable();
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->date('ultima_generacion')->nullable();
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
