<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasColumn('productos', 'categoria_id')) {
            $productos = DB::table('productos')->whereNotNull('categoria_id')->get();

            foreach ($productos as $producto) {
                DB::table('producto_categoria')->insertOrIgnore([
                    'producto_id'  => $producto->id,
                    'categoria_id' => $producto->categoria_id,
                ]);
            }

            Schema::table('productos', function (Blueprint $table) {
                $table->dropForeign(['categoria_id']);
                $table->dropColumn('categoria_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (! Schema::hasColumn('productos', 'categoria_id')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreignId('categoria_id')->nullable()->constrained('categorias')->after('descripcion');
            });
        }

        $assignments = DB::table('producto_categoria')->get();

        foreach ($assignments as $assignment) {
            DB::table('productos')
                ->where('id', $assignment->producto_id)
                ->whereNull('categoria_id')
                ->update(['categoria_id' => $assignment->categoria_id]);
        }
    }
};
