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
        'envio_calle',
        'envio_numero',
        'envio_ciudad',
        'envio_codigo_postal',
        'direccion_id',
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
        $key = 'statuses.'.$this->estado;

        // Si aparece un estado no traducido, se usa pendiente como etiqueta segura.
        return trans()->has($key) ? __($key) : __('statuses.pendiente');
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

    /**
     * Dirección de envío guardada en el pedido, independiente de cambios posteriores del perfil.
     */
    public function getDireccionCompletaAttribute() {
        return "{$this->envio_calle}, {$this->envio_ciudad} ({$this->envio_codigo_postal})";
    }
}
