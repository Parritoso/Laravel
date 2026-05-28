<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LineaPedido extends Model
{
    protected $table = 'linea_pedido';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'producto_id',
        'cantidad',
        'precio_unitario', // precio final cobrado por unidad
        'precio_original', // precio base del producto en el momento de compra
        'descuento_total', // ahorro total de la línea
        'subtotal',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_unitario' => 'decimal:2',
        'subtotal' => 'decimal:2',
    ];

    public function getPrecioUnitarioFormateadoAttribute(): string
    {
        return number_format((float) $this->precio_unitario, 2, ',', '.').' €';
    }

    public function getSubtotalFormateadoAttribute(): string
    {
        return number_format((float) $this->subtotal, 2, ',', '.').' €';
    }

    public function getPrecioOriginalFormateadoAttribute(): string
    {
        return number_format((float) $this->precio_original, 2, ',', '.').' €';
    }

    public function getDescuentoTotalFormateadoAttribute(): string
    {
        return number_format((float) $this->descuento_total, 2, ',', '.').' €';
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
