<?php

namespace Database\Seeders;

use App\Models\Categoria;
use Illuminate\Database\Seeder;

class CategoriaSeeder extends Seeder
{
    public function run(): void
    {
        // Categorías iniciales usadas por el catálogo y los filtros públicos.
        $categorias = [
            ['nombre' => 'Office & Focus', 'slug' => 'office'],
            ['nombre' => 'Gamer Pro',      'slug' => 'gamer'],
        ];

        foreach ($categorias as $data) {
            Categoria::updateOrCreate(['slug' => $data['slug']], $data);
        }
    }
}
