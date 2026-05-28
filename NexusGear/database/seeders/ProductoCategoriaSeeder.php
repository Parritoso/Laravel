<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Producto;
use App\Models\Categoria;

class ProductoCategoriaSeeder extends Seeder
{
    /**
     * Asigna categorías a productos de demo por nombre.
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
            // Se separa la asignación de categorías para poder ajustar los productos de demo sin tocar migraciones.
            $categoriaId = ($slug === 'office') ? $officeId : $gamerId;

            foreach ($nombresProductos as $nombre) {
                $producto = Producto::where('nombre', $nombre)->first();

                if ($producto && $categoriaId) {
                    // Evita duplicados si el seeder se ejecuta más de una vez.
                    $producto->categorias()->syncWithoutDetaching([$categoriaId]);
                }
            }
        }
    }
}
