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
        Schema::create('mensajes_app', function (Blueprint $table) {
            $table->bigIncrements('mensaje_id');
            $table->string('tipo', 50);
            $table->string('contenido_mensaje', 500);
            $table->boolean('activo')->nullable()->default(true);
            $table->timestamp('fecha_creacion')->useCurrent();
            $table->unsignedBigInteger('usuario_id')->nullable()->index('usuario_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mensajes_app');
    }
};
