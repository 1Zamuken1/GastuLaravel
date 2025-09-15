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
            $table->decimal('monto', 12);
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
