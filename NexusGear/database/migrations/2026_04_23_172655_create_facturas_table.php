<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('facturas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pedido_id')->unique()->constrained('pedidos')->cascadeOnDelete();
            $table->string('numero_factura')->unique();
            $table->float('subtotal');
            $table->float('iva');
            $table->float('total_factura');
            $table->dateTime('fecha_emision');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('facturas');
    }
};
