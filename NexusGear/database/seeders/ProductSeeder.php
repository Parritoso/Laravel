<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $office = Categoria::where('slug', 'office')->value('id');
        $gamer  = Categoria::where('slug', 'gamer')->value('id');

        $productos = [
            [
                'nombre'       => 'Nexus Vertical Pro',
                'precio'       => 59.99,
                'descripcion'  => 'Ratón vertical inalámbrico con agarre neutro, sensor preciso y superficie mate para jornadas largas sin sobrecargar la muñeca.',
                'imagen' => 'productos/default.png',
                'stock'        => 24,
                'categoria_id' => $office,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Aqua Keys 60',
                'precio'       => 89.99,
                'descripcion'  => 'Teclado mecánico compacto con switches táctiles silenciosos, formato 60% y cuerpo rígido para escritorios despejados.',
                'imagen' => 'productos/default.png',
                'stock'        => 12,
                'categoria_id' => $gamer,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Focus Pad Gel',
                'precio'       => 24.90,
                'descripcion'  => 'Reposamuñecas de gel viscoelástico con base antideslizante y altura estable para escribir con una postura más natural.',
                'stock'        => 38,
                'categoria_id' => $office,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Split Core Ergo',
                'precio'       => 129.00,
                'descripcion'  => 'Teclado dividido con inclinación ajustable, pensado para reducir tensión en hombros y antebrazos durante sesiones intensas.',
                'stock'        => 7,
                'categoria_id' => $office,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Pulse Mouse X',
                'precio'       => 74.50,
                'descripcion'  => 'Mouse ligero para gaming con laterales texturizados, baja latencia y forma ergonómica para agarres palm y claw.',
                'stock'        => 18,
                'categoria_id' => $gamer,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Lift Dock Stand',
                'precio'       => 39.95,
                'descripcion'  => 'Soporte elevador para portátil con aluminio ventilado, plegado compacto y altura preparada para trabajar con pantalla a la vista.',
                'stock'        => 20,
                'categoria_id' => $office,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Stealth Wrist Rest',
                'precio'       => 29.99,
                'descripcion'  => 'Reposamuñecas de perfil bajo con acabado textil transpirable, ideal para teclados compactos y setups minimalistas.',
                'stock'        => 0,
                'categoria_id' => $gamer,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Tactile Flow TKL',
                'precio'       => 109.90,
                'descripcion'  => 'Teclado TKL con switches táctiles, estabilizadores lubricados y cubierta superior sobria para trabajo y juego.',
                'stock'        => 15,
                'categoria_id' => $gamer,
                'destacado'    => false,
            ],
        ];

        foreach ($productos as $producto) {
            Producto::updateOrCreate(
                ['nombre' => $producto['nombre']],
                $producto
            );
        }
    }
}
