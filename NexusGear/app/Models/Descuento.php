<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Builder;

class Descuento extends Model
{
    protected $table = 'descuentos';

    public $timestamps = false;

    protected $fillable = [
        'codigo',
        'tipo',
        'valor',
        'fecha_fin',
    ];

    protected $casts = [
        'fecha_fin' => 'datetime',
    ];

    public function productos(): BelongsToMany
    {
        return $this->belongsToMany(Producto::class, 'descuento_producto', 'descuento_id', 'producto_id');
    }

    /**
     * Filtra los descuentos que aún no han caducado.
     * Uso: Descuento::active()->get();
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('fecha_fin', '>', now());
    }

    /**
     * Busca un descuento por su código.
     */
    public function scopeByCodigo(Builder $query, string $codigo): void
    {
        $query->where('codigo', $codigo);
    }

    /**
     * Determina si el descuento ha expirado.
     */
    public function esValido(): bool
    {
        return $this->fecha_fin->isFuture();
    }

    /**
     * Calcula el nuevo precio aplicando este descuento.
     * * @param float $precioOriginal
     * @return float
     */
    public function calcularPrecioDescontado(float $precioOriginal): float
    {
        if ($this->tipo === 'porcentaje') {
            // Ejemplo: 20% de descuento
            $descuento = $precioOriginal * ($this->valor / 100);
            return max(0, $precioOriginal - $descuento);
        }

        if ($this->tipo === 'fijo') {
            // Ejemplo: 10€ de descuento
            return max(0, $precioOriginal - $this->valor);
        }

        return $precioOriginal;
    }
}
