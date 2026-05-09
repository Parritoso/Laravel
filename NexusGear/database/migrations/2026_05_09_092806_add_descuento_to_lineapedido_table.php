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
        Schema::table('linea_pedido', function (Blueprint $table) {
            $table->decimal('precio_original', 10, 2)->after('producto_id');
            $table->decimal('descuento_total', 10, 2)->default(0)->after('precio_unitario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('linea_pedido', function (Blueprint $table) {
            $table->dropColumn(['precio_original', 'descuento_total']);
        });
    }
};
