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
        Schema::create('ahorro_meta', function (Blueprint $table) {
            $table->bigIncrements('ahorro_meta_id');
            $table->unsignedBigInteger('usuario_id')->index('usuario_id');
            $table->string('concepto', 60);
            $table->string('descripcion', 100)->nullable();
            $table->decimal('monto_meta', 12)->nullable();
            $table->decimal('total_acumulado', 12)->nullable()->default(0);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->date('fecha_meta')->nullable();
            $table->boolean('activa')->nullable()->default(true);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahorro_meta');
    }
};
