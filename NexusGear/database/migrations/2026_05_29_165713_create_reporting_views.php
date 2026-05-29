<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement('
            CREATE VIEW v_productos_mas_favoritos AS
            SELECT
                p.id AS producto_id,
                p.nombre,
                p.stock,
                MIN(c.nombre) AS categoria_principal,
                COUNT(f.producto_id) AS favoritos_count,
                MAX(f.agregado_el) AS ultimo_favorito
            FROM productos p
            LEFT JOIN favoritos f ON f.producto_id = p.id
            LEFT JOIN producto_categoria pc ON pc.producto_id = p.id
            LEFT JOIN categorias c ON c.id = pc.categoria_id
            GROUP BY p.id, p.nombre, p.stock
        ');

        DB::statement('
            CREATE VIEW v_ventas_por_producto AS
            SELECT
                p.id AS producto_id,
                p.nombre,
                MIN(c.nombre) AS categoria_principal,
                COALESCE(SUM(lp.cantidad), 0) AS unidades_vendidas,
                COALESCE(SUM(lp.subtotal), 0) AS ingresos_totales,
                COUNT(DISTINCT lp.pedido_id) AS pedidos_count
            FROM productos p
            LEFT JOIN linea_pedido lp ON lp.producto_id = p.id
            LEFT JOIN producto_categoria pc ON pc.producto_id = p.id
            LEFT JOIN categorias c ON c.id = pc.categoria_id
            GROUP BY p.id, p.nombre
        ');

         DB::statement('
            CREATE VIEW v_resumen_pedidos_por_estado AS
            SELECT
                p.estado,
                COUNT(p.id) AS pedidos_count,
                COALESCE(SUM(f.total_factura), 0) AS facturacion_total
            FROM pedidos p
            LEFT JOIN facturas f ON f.pedido_id = p.id
            GROUP BY p.estado
        ');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('DROP VIEW IF EXISTS v_resumen_pedidos_por_estado');
        DB::statement('DROP VIEW IF EXISTS v_ventas_por_producto');
        DB::statement('DROP VIEW IF EXISTS v_productos_mas_favoritos');
    }
};
