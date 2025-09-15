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
        Schema::create('usuario', function (Blueprint $table) {
            $table->bigIncrements('usuario_id');
            $table->string('nombre', 30);
            $table->string('correo', 50)->unique('correo');
            $table->string('telefono', 20)->nullable();
            $table->string('password', 50);
            $table->timestamp('fecha_registro')->useCurrent();
            $table->boolean('activo')->nullable()->default(true);
            $table->integer('rol_id')->index('rol_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usuario');
    }
};
