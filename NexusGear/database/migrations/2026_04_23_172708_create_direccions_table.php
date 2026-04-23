<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('direcciones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('usuarios')->cascadeOnDelete();
            $table->string('calle');
            $table->string('numero');
            $table->string('ciudad');
            $table->string('codigo_postal');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('direcciones');
    }
};
