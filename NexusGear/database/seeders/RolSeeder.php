<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Inserta los roles necesarios para permisos y navegacion.
     */
    public function run(): void
    {
        // Roles mínimos del proyecto: administración y cliente normal.
        DB::table('roles')->insert([
            ['nombre_rol' => 'admin'],
            ['nombre_rol' => 'customer'],
        ]);
    }
}
