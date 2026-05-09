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
        Schema::table('pedidos', function (Blueprint $table) {
            if (! Schema::hasColumn('pedidos', 'envio_calle')) {
                $table->string('envio_calle')->nullable();
            }

            if (! Schema::hasColumn('pedidos', 'envio_numero')) {
                $table->string('envio_numero')->nullable();
            }

            if (! Schema::hasColumn('pedidos', 'envio_ciudad')) {
                $table->string('envio_ciudad')->nullable();
            }

            if (! Schema::hasColumn('pedidos', 'envio_codigo_postal')) {
                $table->string('envio_codigo_postal')->nullable();
            }

            if (! Schema::hasColumn('pedidos', 'direccion_id')) {
                $table->foreignId('direccion_id')->nullable()->constrained('direcciones')->nullOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pedidos', function (Blueprint $table) {
            if (Schema::hasColumn('pedidos', 'direccion_id')) {
                $table->dropForeign(['direccion_id']);
            }

            $columns = array_filter([
                Schema::hasColumn('pedidos', 'envio_calle') ? 'envio_calle' : null,
                Schema::hasColumn('pedidos', 'envio_numero') ? 'envio_numero' : null,
                Schema::hasColumn('pedidos', 'envio_ciudad') ? 'envio_ciudad' : null,
                Schema::hasColumn('pedidos', 'envio_codigo_postal') ? 'envio_codigo_postal' : null,
                Schema::hasColumn('pedidos', 'direccion_id') ? 'direccion_id' : null,
            ]);

            if ($columns !== []) {
                $table->dropColumn($columns);
            }
        });
    }
};
