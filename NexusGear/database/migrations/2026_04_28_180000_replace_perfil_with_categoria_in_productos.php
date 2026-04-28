<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('categorias', function (Blueprint $table) {
            $table->string('slug')->unique()->after('nombre');
        });

        Schema::table('productos', function (Blueprint $table) {
            $table->foreignId('categoria_id')->nullable()->constrained('categorias')->after('descripcion');
            $table->dropColumn('perfil');
        });
    }

    public function down(): void
    {
        Schema::table('productos', function (Blueprint $table) {
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
            $table->string('perfil')->default('office');
        });

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
