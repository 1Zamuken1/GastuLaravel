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
        Schema::create('ahorro_programado', function (Blueprint $table) {
            $table->bigIncrements('ahorro_programado_id');
            $table->unsignedBigInteger('ahorro_meta_id')->index('ahorro_meta_id');
            $table->decimal('monto_programado', 12);
            $table->string('frecuencia', 30);
            $table->date('fecha_inicio');
            $table->date('fecha_fin')->nullable();
            $table->integer('num_cuotas')->nullable();
            $table->dateTime('ultimo_aporte_generadao')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahorro_programado');
    }
};
