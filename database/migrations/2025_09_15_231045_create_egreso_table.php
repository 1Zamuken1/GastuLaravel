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
        Schema::create('egreso', function (Blueprint $table) {
            $table->bigIncrements('egreso_id');
            $table->string('tipo', 20)->nullable();
            $table->decimal('monto', 12);
            $table->string('descripcion', 100)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
            $table->unsignedBigInteger('concepto_egreso_id')->nullable()->index('concepto_egreso_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('egreso');
    }
};
