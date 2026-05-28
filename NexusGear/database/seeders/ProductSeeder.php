<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // updateOrCreate permite regenerar datos de demo sin duplicar categorías.
        $office = Categoria::updateOrCreate(
            ['slug' => 'office'],
            ['nombre' => 'Office & Focus', 'slug' => 'office']
        );

        $gamer = Categoria::updateOrCreate(
            ['slug' => 'gamer'],
            ['nombre' => 'Gamer Pro', 'slug' => 'gamer']
        );

        $productos = [
            [
                'nombre'       => 'Nexus Vertical Pro',
                'categorias'   => [$office->id],
                'precio'       => 59.99,
                'descripcion'  => 'Ratón vertical inalámbrico con agarre neutro, sensor preciso y superficie mate para jornadas largas sin sobrecargar la muñeca.',
                'imagen' => 'productos/default.png',
                'stock'        => 24,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Aqua Keys 60',
                'categorias'   => [$gamer->id],
                'precio'       => 89.99,
                'descripcion'  => 'Teclado mecánico compacto con switches táctiles silenciosos, formato 60% y cuerpo rígido para escritorios despejados.',
                'imagen' => 'productos/default.png',
                'stock'        => 12,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Focus Pad Gel',
                'categorias'   => [$office->id],
                'precio'       => 24.90,
                'descripcion'  => 'Reposamuñecas de gel viscoelástico con base antideslizante y altura estable para escribir con una postura más natural.',
                'stock'        => 38,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Split Core Ergo',
                'categorias'   => [$office->id],
                'precio'       => 129.00,
                'descripcion'  => 'Teclado dividido con inclinación ajustable, pensado para reducir tensión en hombros y antebrazos durante sesiones intensas.',
                'stock'        => 7,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Pulse Mouse X',
                'categorias'   => [$gamer->id],
                'precio'       => 74.50,
                'descripcion'  => 'Mouse ligero para gaming con laterales texturizados, baja latencia y forma ergonómica para agarres palm y claw.',
                'stock'        => 18,
                'destacado'    => true,
            ],
            [
                'nombre'       => 'Lift Dock Stand',
                'categorias'   => [$office->id],
                'precio'       => 39.95,
                'descripcion'  => 'Soporte elevador para portátil con aluminio ventilado, plegado compacto y altura preparada para trabajar con pantalla a la vista.',
                'stock'        => 20,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Stealth Wrist Rest',
                'categorias'   => [$gamer->id],
                'precio'       => 29.99,
                'descripcion'  => 'Reposamuñecas de perfil bajo con acabado textil transpirable, ideal para teclados compactos y setups minimalistas.',
                'stock'        => 0,
                'destacado'    => false,
            ],
            [
                'nombre'       => 'Tactile Flow TKL',
                'categorias'   => [$gamer->id],
                'precio'       => 109.90,
                'descripcion'  => 'Teclado TKL con switches táctiles, estabilizadores lubricados y cubierta superior sobria para trabajo y juego.',
                'stock'        => 15,
                'destacado'    => false,
            ],
        ];

        foreach ($productos as $producto) {
        // Las categorías se sincronizan después porque pertenecen a una tabla pivote.
            $categorias = $producto['categorias'];
            unset($producto['categorias']);

            $model = Producto::updateOrCreate(
                ['nombre' => $producto['nombre']],
                $producto
            );

            $model->categorias()->sync($categorias);
        }
    }
}
