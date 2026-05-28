<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Carga los datos mínimos en el orden necesario por dependencias.
     */
    public function run(): void
    {
        $this->call([
            RolSeeder::class,
            CategoriaSeeder::class,
            ProductSeeder::class,
            ProductoCategoriaSeeder::class,
            UserSeeder::class,
        ]);
    }
}
