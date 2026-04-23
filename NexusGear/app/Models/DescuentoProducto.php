<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DescuentoProducto extends Model
{
    protected $table = 'descuento_producto';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'descuento_id',
        'producto_id',
    ];

    public function descuento(): BelongsTo
    {
        return $this->belongsTo(Descuento::class, 'descuento_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
