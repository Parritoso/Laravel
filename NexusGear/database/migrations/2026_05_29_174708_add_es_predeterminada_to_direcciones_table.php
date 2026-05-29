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
        if (! Schema::hasColumn('direcciones', 'es_predeterminada')) {
            Schema::table('direcciones', function (Blueprint $table) {
                $table->boolean('es_predeterminada')
                        ->default(false)
                        ->after('codigo_postal');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('direcciones', 'es_predeterminada')) {
            Schema::table('direcciones', function (Blueprint $table) {
                $table->dropColumn('es_predeterminada');
            });
        }
    }
};
