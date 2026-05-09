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
            if (! Schema::hasColumn('linea_pedido', 'precio_original')) {
                $table->decimal('precio_original', 10, 2)->default(0)->after('producto_id');
            }

            if (! Schema::hasColumn('linea_pedido', 'descuento_total')) {
                $table->decimal('descuento_total', 10, 2)->default(0)->after('precio_unitario');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('linea_pedido', function (Blueprint $table) {
            $columns = array_filter([
                Schema::hasColumn('linea_pedido', 'precio_original') ? 'precio_original' : null,
                Schema::hasColumn('linea_pedido', 'descuento_total') ? 'descuento_total' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
