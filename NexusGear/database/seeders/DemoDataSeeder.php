<?php

namespace Database\Seeders;

use App\Models\Carrito;
use App\Models\Comentario;
use App\Models\Descuento;
use App\Models\Direccion;
use App\Models\Factura;
use App\Models\ItemCarrito;
use App\Models\LineaPedido;
use App\Models\Pedido;
use App\Models\Producto;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@nexusgear.com')->firstOrFail();
        $juan = User::where('email', 'juan@example.com')->firstOrFail();
        $maria = User::where('email', 'maria@example.com')->firstOrFail();

        $vertical = Producto::where('nombre', 'Nexus Vertical Pro')->firstOrFail();
        $keys = Producto::where('nombre', 'Aqua Keys 60')->firstOrFail();
        $pad = Producto::where('nombre', 'Focus Pad Gel')->firstOrFail();
        $split = Producto::where('nombre', 'Split Core Ergo')->firstOrFail();
        $pulse = Producto::where('nombre', 'Pulse Mouse X')->firstOrFail();
        $stand = Producto::where('nombre', 'Lift Dock Stand')->firstOrFail();
        $wrist = Producto::where('nombre', 'Stealth Wrist Rest')->firstOrFail();
        $tkl = Producto::where('nombre', 'Tactile Flow TKL')->firstOrFail();

        $this->seedDescuentos([$vertical, $keys, $pad, $pulse, $tkl]);
        $direcciones = $this->seedDirecciones($juan, $maria);
        $this->seedCarritos($juan, $maria, $vertical, $pad, $pulse, $stand);
        $this->seedFavoritos($juan, $maria, $vertical, $keys, $wrist, $tkl);
        $this->seedComentarios($juan, $maria, $admin, $vertical, $keys, $pad, $split, $pulse);
        $this->seedPedidos($juan, $maria, $direcciones, $vertical, $keys, $pad, $pulse, $stand);
        $this->seedNotificaciones($juan, $maria, $vertical, $wrist);
    }

    /**
     * @param array<int, Producto> $productos
     */
    private function seedDescuentos(array $productos): void
    {
        $descuentos = [
            [
                'codigo' => 'ERGONOMIA10',
                'tipo' => 'porcentaje',
                'valor' => 10,
                'fecha_fin' => now()->addMonths(2),
                'productos' => [$productos[0]->id, $productos[1]->id, $productos[4]->id],
            ],
            [
                'codigo' => 'FOCUS5',
                'tipo' => 'fijo',
                'valor' => 5,
                'fecha_fin' => now()->addMonth(),
                'productos' => [$productos[2]->id, $productos[3]->id],
            ],
        ];

        foreach ($descuentos as $data) {
            $productosIds = $data['productos'];
            unset($data['productos']);

            $descuento = Descuento::updateOrCreate(['codigo' => $data['codigo']], $data);
            $descuento->productos()->sync($productosIds);
        }
    }

    /**
     * @return array<string, Direccion>
     */
    private function seedDirecciones(User $juan, User $maria): array
    {
        $direcciones = [
            'juan_casa' => Direccion::updateOrCreate(
                ['usuario_id' => $juan->id, 'calle' => 'Calle Teclado', 'numero' => '12'],
                [
                    'ciudad' => 'Madrid',
                    'codigo_postal' => '28013',
                    'es_predeterminada' => true,
                ],
            ),
            'juan_oficina' => Direccion::updateOrCreate(
                ['usuario_id' => $juan->id, 'calle' => 'Avenida Setup', 'numero' => '8'],
                [
                    'ciudad' => 'Madrid',
                    'codigo_postal' => '28020',
                    'es_predeterminada' => false,
                ],
            ),
            'maria_casa' => Direccion::updateOrCreate(
                ['usuario_id' => $maria->id, 'calle' => 'Paseo Ergonomía', 'numero' => '4B'],
                [
                    'ciudad' => 'Sevilla',
                    'codigo_postal' => '41001',
                    'es_predeterminada' => true,
                ],
            ),
        ];

        return $direcciones;
    }

    private function seedCarritos(User $juan, User $maria, Producto $vertical, Producto $pad, Producto $pulse, Producto $stand): void
    {
        $juanCart = Carrito::firstOrCreate(['usuario_id' => $juan->id]);
        $mariaCart = Carrito::firstOrCreate(['usuario_id' => $maria->id]);

        $items = [
            [$juanCart, $vertical, 1],
            [$juanCart, $pad, 2],
            [$mariaCart, $pulse, 1],
            [$mariaCart, $stand, 1],
        ];

        foreach ($items as [$cart, $producto, $cantidad]) {
            ItemCarrito::updateOrCreate(
                ['carrito_id' => $cart->id, 'producto_id' => $producto->id],
                ['cantidad' => $cantidad, 'precio_actual' => $producto->precio],
            );
        }
    }

    private function seedFavoritos(User $juan, User $maria, Producto $vertical, Producto $keys, Producto $wrist, Producto $tkl): void
    {
        $favoritos = [
            [$juan, $vertical, 5],
            [$juan, $keys, 4],
            [$maria, $wrist, 3],
            [$maria, $tkl, 8],
        ];

        foreach ($favoritos as [$user, $producto, $umbral]) {
            DB::table('favoritos')->updateOrInsert(
                ['usuario_id' => $user->id, 'producto_id' => $producto->id],
                [
                    'agregado_el' => now()->subDays($umbral),
                    'alerta_precio' => true,
                    'alerta_stock_bajo' => true,
                    'alerta_stock_agotado' => true,
                    'alerta_stock_disponible' => true,
                    'umbral_stock' => $umbral,
                ],
            );
        }
    }

    private function seedComentarios(User $juan, User $maria, User $admin, Producto $vertical, Producto $keys, Producto $pad, Producto $split, Producto $pulse): void
    {
        $comentarios = [
            [$juan, $vertical, 4.5, 'Muy cómodo para trabajar muchas horas.'],
            [$juan, $keys, 4.0, 'Compacto y estable, buena sensación de escritura.'],
            [$maria, $pad, 5.0, 'El reposamuñecas se nota desde el primer día.'],
            [$maria, $split, 4.5, 'Cuesta acostumbrarse, pero mejora la postura.'],
            [$admin, $pulse, 4.0, 'Buen equilibrio entre ligereza y agarre.'],
        ];

        foreach ($comentarios as [$user, $producto, $puntuacion, $contenido]) {
            Comentario::updateOrCreate(
                ['user_id' => $user->id, 'producto_id' => $producto->id],
                ['puntuacion' => $puntuacion, 'contenido' => $contenido],
            );
        }
    }

    /**
     * @param array<string, Direccion> $direcciones
     */
    private function seedPedidos(User $juan, User $maria, array $direcciones, Producto $vertical, Producto $keys, Producto $pad, Producto $pulse, Producto $stand): void
    {
        $pedidos = [
            [
                'numero_factura' => 'NG-2026-0001',
                'usuario' => $juan,
                'direccion' => $direcciones['juan_casa'],
                'estado' => 'entregado',
                'fecha' => now()->subDays(12),
                'lineas' => [
                    [$vertical, 1, 53.99],
                    [$pad, 1, 19.90],
                ],
            ],
            [
                'numero_factura' => 'NG-2026-0002',
                'usuario' => $maria,
                'direccion' => $direcciones['maria_casa'],
                'estado' => 'procesando',
                'fecha' => now()->subDays(2),
                'lineas' => [
                    [$keys, 1, 80.99],
                    [$pulse, 1, 74.50],
                    [$stand, 1, 39.95],
                ],
            ],
        ];

        foreach ($pedidos as $data) {
            $pedido = Pedido::updateOrCreate(
                [
                    'usuario_id' => $data['usuario']->id,
                    'fecha' => $data['fecha'],
                ],
                [
                    'estado' => $data['estado'],
                    'direccion_id' => $data['direccion']->id,
                    'envio_calle' => $data['direccion']->calle,
                    'envio_numero' => $data['direccion']->numero,
                    'envio_ciudad' => $data['direccion']->ciudad,
                    'envio_codigo_postal' => $data['direccion']->codigo_postal,
                ],
            );

            $subtotalPedido = 0;

            foreach ($data['lineas'] as [$producto, $cantidad, $precioUnitario]) {
                $subtotal = round($cantidad * $precioUnitario, 2);
                $subtotalPedido += $subtotal;

                LineaPedido::updateOrCreate(
                    ['pedido_id' => $pedido->id, 'producto_id' => $producto->id],
                    [
                        'cantidad' => $cantidad,
                        'precio_original' => $producto->precio,
                        'precio_unitario' => $precioUnitario,
                        'descuento_total' => round(($producto->precio - $precioUnitario) * $cantidad, 2),
                        'subtotal' => $subtotal,
                    ],
                );
            }

            $iva = round($subtotalPedido * 0.21, 2);

            Factura::updateOrCreate(
                ['pedido_id' => $pedido->id],
                [
                    'numero_factura' => $data['numero_factura'],
                    'subtotal' => round($subtotalPedido, 2),
                    'iva' => $iva,
                    'total_factura' => round($subtotalPedido + $iva, 2),
                    'fecha_emision' => $data['fecha']->copy()->addMinutes(5),
                ],
            );
        }
    }

    private function seedNotificaciones(User $juan, User $maria, Producto $vertical, Producto $wrist): void
    {
        $notificaciones = [
            [
                'id' => '00000000-0000-0000-0000-000000000701',
                'user' => $juan,
                'producto' => $vertical,
                'tipo' => 'precio',
                'mensaje' => 'Nexus Vertical Pro tiene una rebaja activa.',
                'read_at' => null,
            ],
            [
                'id' => '00000000-0000-0000-0000-000000000702',
                'user' => $maria,
                'producto' => $wrist,
                'tipo' => 'stock_agotado',
                'mensaje' => 'Stealth Wrist Rest está temporalmente agotado.',
                'read_at' => now()->subDay(),
            ],
        ];

        foreach ($notificaciones as $data) {
            DB::table('notifications')->updateOrInsert(
                ['id' => $data['id']],
                [
                    'type' => 'App\\Notifications\\ProductAlertNotification',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $data['user']->id,
                    'data' => json_encode([
                        'producto_id' => $data['producto']->id,
                        'tipo' => $data['tipo'],
                        'mensaje' => $data['mensaje'],
                        'url' => route('products.show', $data['producto']),
                    ], JSON_THROW_ON_ERROR),
                    'read_at' => $data['read_at'],
                    'created_at' => now()->subDays(3),
                    'updated_at' => now()->subDays(3),
                ],
            );
        }
    }
}
