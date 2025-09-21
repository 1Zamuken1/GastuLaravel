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
        Schema::create('aporte_ahorro', function (Blueprint $table) {
            $table->bigIncrements('aporte_ahorro_id');
            $table->unsignedBigInteger('ahorro_meta_id')->index('ahorro_meta_id');
            $table->decimal('aporte_asignado', 12)->nullable()->default(0);
            $table->decimal('aporte', 12)->nullable()->default(0);
            $table->date('fecha_limite')->nullable();
            $table->string('estado', 30)->nullable();
            $table->timestamp('fecha_registro')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aporte_ahorro');
    }
};
