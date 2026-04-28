<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Pedido extends Model
{
    protected $table = 'pedidos';

    public $timestamps = false;

    protected $fillable = [
        'usuario_id',
        'estado',
        'fecha',
    ];

    protected $casts = [
        'fecha' => 'datetime',
    ];

    public function getFechaFormateadaAttribute(): string
    {
        return $this->fecha->format('d/m/Y H:i');
    }

    public function getEstadoLabelAttribute(): string
    {
        return match ($this->estado) {
            'procesando' => 'Procesando',
            'enviado' => 'Enviado',
            'entregado' => 'Entregado',
            'cancelado' => 'Cancelado',
            default => 'Pendiente',
        };
    }

    public function getEstadoBadgeAttribute(): string
    {
        return match ($this->estado) {
            'procesando' => 'warning',
            'enviado' => 'info',
            'entregado' => 'success',
            'cancelado' => 'danger',
            default => 'secondary',
        };
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function factura(): HasOne
    {
        return $this->hasOne(Factura::class, 'pedido_id');
    }

    public function lineas(): HasMany
    {
        return $this->hasMany(LineaPedido::class, 'pedido_id');
    }
}
