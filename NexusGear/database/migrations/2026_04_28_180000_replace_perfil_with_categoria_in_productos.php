<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('categorias', 'slug')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->string('slug')->nullable()->after('nombre');
            });

            DB::table('categorias')
                ->whereNull('slug')
                ->where('nombre', 'like', '%office%')
                ->update(['slug' => 'office']);

            DB::table('categorias')
                ->whereNull('slug')
                ->where('nombre', 'like', '%gamer%')
                ->update(['slug' => 'gamer']);

            Schema::table('categorias', function (Blueprint $table) {
                $table->unique('slug');
            });
        }

        DB::table('categorias')->updateOrInsert(
            ['slug' => 'office'],
            ['nombre' => 'Office & Focus', 'slug' => 'office']
        );

        DB::table('categorias')->updateOrInsert(
            ['slug' => 'gamer'],
            ['nombre' => 'Gamer Pro', 'slug' => 'gamer']
        );

        if (! Schema::hasColumn('productos', 'categoria_id')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->foreignId('categoria_id')->nullable()->constrained('categorias')->after('descripcion');
            });
        }

        if (Schema::hasColumn('productos', 'perfil')) {
            $officeId = DB::table('categorias')->where('slug', 'office')->value('id');
            $gamerId = DB::table('categorias')->where('slug', 'gamer')->value('id');

            DB::table('productos')->where('perfil', 'office')->update(['categoria_id' => $officeId]);
            DB::table('productos')->where('perfil', 'gamer')->update(['categoria_id' => $gamerId]);

            Schema::table('productos', function (Blueprint $table) {
                $table->dropColumn('perfil');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('productos', 'categoria_id')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->dropForeign(['categoria_id']);
                $table->dropColumn('categoria_id');
            });
        }

        if (! Schema::hasColumn('productos', 'perfil')) {
            Schema::table('productos', function (Blueprint $table) {
                $table->string('perfil')->default('office');
            });
        }

        if (Schema::hasColumn('categorias', 'slug')) {
            Schema::table('categorias', function (Blueprint $table) {
                $table->dropUnique(['slug']);
                $table->dropColumn('slug');
            });
        }
    }
};
