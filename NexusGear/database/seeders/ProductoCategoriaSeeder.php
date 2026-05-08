<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoCategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $officeId = Categoria::where('slug', 'office')->value('id');
        $gamerId  = Categoria::where('slug', 'gamer')->value('id');

        $asignaciones = [
            'office' => [
                'Nexus Vertical Pro',
                'Focus Pad Gel',
                'Split Core Ergo',
                'Lift Dock Stand'
            ],
            'gamer' => [
                'Aqua Keys 60',
                'Pulse Mouse X',
                'Stealth Wrist Rest',
                'Tactile Flow TKL'
            ]
        ];

        foreach ($asignaciones as $slug => $nombresProductos) {
            $categoriaId = ($slug === 'office') ? $officeId : $gamerId;

            foreach ($nombresProductos as $nombre) {
                $producto = Producto::where('nombre', $nombre)->first();

                if ($producto && $categoriaId) {
                    // syncWithoutDetaching evita duplicados si corres el seeder varias veces
                    $producto->categorias()->syncWithoutDetaching([$categoriaId]);
                }
            }
        }
    }
}
