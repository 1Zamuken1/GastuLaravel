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
        Schema::create('permiso', function (Blueprint $table) {
            $table->integer('permiso_id', true);
            $table->string('nombre', 50);
            $table->string('descripcion', 200)->nullable();
            $table->string('categoria', 50)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permiso');
    }
};
