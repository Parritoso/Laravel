<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Factura extends Model
{
    protected $table = 'facturas';

    public $timestamps = false;

    protected $fillable = [
        'pedido_id',
        'numero_factura',
        'subtotal',
        'iva',
        'total_factura',
        'fecha_emision',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
    ];

    public function getSubtotalFormateadoAttribute(): string
    {
        return number_format((float) $this->subtotal, 2, ',', '.').' €';
    }

    public function getIvaFormateadoAttribute(): string
    {
        return number_format((float) $this->iva, 2, ',', '.').' €';
    }

    public function getTotalFormateadoAttribute(): string
    {
        return number_format((float) $this->total_factura, 2, ',', '.').' €';
    }

    public function pedido(): BelongsTo
    {
        return $this->belongsTo(Pedido::class, 'pedido_id');
    }
}
