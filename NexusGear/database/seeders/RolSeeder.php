<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Insertamos los roles base siguiendo tu esquema
        DB::table('roles')->insert([
            ['nombre_rol' => 'admin'],
            ['nombre_rol' => 'customer'],
        ]);
    }
}
