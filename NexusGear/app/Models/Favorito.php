<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Favorito extends Model
{
    protected $table = 'favoritos';

    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'producto_id',
        'agregado_el',
        'alerta_precio',
        'alerta_stock_bajo',
        'alerta_stock_agotado',
        'alerta_stock_disponible',
        'umbral_stock',
    ];

    protected $casts = [
        'agregado_el' => 'datetime',
        'alerta_precio' => 'boolean',
        'alerta_stock_bajo'  => 'boolean',
        'alerta_stock_agotado' => 'boolean',
        'alerta_stock_disponible' => 'boolean',
        'umbral_stock'  => 'integer',
    ];

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function producto(): BelongsTo
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
