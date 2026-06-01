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
        foreach (['admin', 'customer'] as $role) {
            DB::table('roles')->updateOrInsert(
                ['nombre_rol' => $role],
                ['nombre_rol' => $role],
            );
        }
    }
}
