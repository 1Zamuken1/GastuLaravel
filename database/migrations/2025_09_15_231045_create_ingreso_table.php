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
        Schema::create('ingreso', function (Blueprint $table) {
            $table->bigIncrements('ingreso_id');
            $table->string('tipo', 20)->nullable();
            $table->decimal('monto', 12);
            $table->string('descripcion', 100)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->unsignedBigInteger('concepto_ingreso_id')->nullable()->index('concepto_ingreso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ingreso');
    }
};
