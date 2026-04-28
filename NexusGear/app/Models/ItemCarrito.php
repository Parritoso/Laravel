<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemCarrito extends Model
{
    protected $table = 'item_carrito';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'carrito_id',
        'producto_id',
        'cantidad',
        'precio_actual',
    ];

    protected $casts = [
        'cantidad' => 'integer',
        'precio_actual' => 'decimal:2',
    ];

    public function getSubtotalAttribute(): float
    {
        return $this->cantidad * (float) $this->precio_actual;
    }

    public function getSubtotalFormateadoAttribute(): string
    {
        return number_format($this->subtotal, 2, ',', '.').' €';
    }

    public function getPrecioActualFormateadoAttribute(): string
    {
        return number_format((float) $this->precio_actual, 2, ',', '.').' €';
    }

    public function carrito(): BelongsTo
    {
        return $this->belongsTo(Carrito::class, 'carrito_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
