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
        Schema::table('favoritos', function (Blueprint $table) {
            $table->boolean('alerta_precio')->default(true);
            $table->boolean('alerta_stock')->default(true);
            $table->boolean('alerta_stock_agotado')->default(true);
            $table->boolean('alerta_stock_disponible')->default(true);
            $table->unsignedInteger('umbral_stock')->default(5);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('favoritos', function (Blueprint $table) {
            $table->dropColumn(['alerta_precio', 'alerta_stock', 'alerta_stock_agotado', 'alerta_stock_disponible', 'umbral_stock']);
        });
    }
};
