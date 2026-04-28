<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Carrito extends Model
{
    protected $table = 'carritos';

    protected $fillable = [
        'usuario_id',
    ];

    public function getTotalAttribute(): float
    {
        return $this->items->sum(fn (ItemCarrito $item) => $item->subtotal);
    }

    public function getTotalFormateadoAttribute(): string
    {
        return number_format($this->total, 2, ',', '.').' €';
    }

    public function getCantidadTotalAttribute(): int
    {
        return $this->items->sum('cantidad');
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ItemCarrito::class, 'carrito_id');
    }
}
