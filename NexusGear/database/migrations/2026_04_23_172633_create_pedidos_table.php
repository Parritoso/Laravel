<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')->constrained('users')->cascadeOnDelete();
            $table->enum('estado', ['pendiente', 'procesando', 'enviado', 'entregado', 'cancelado']);
            $table->dateTime('fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
