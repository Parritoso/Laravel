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
     * Limita la consulta a descuentos que todavía pueden aplicarse.
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('fecha_fin', '>', now());
    }

    /**
     * Permite localizar un descuento desde formularios o futuras validaciones por código.
     */
    public function scopeByCodigo(Builder $query, string $codigo): void
    {
        $query->where('codigo', $codigo);
    }

    /**
     * Comprueba la validez desde una instancia ya cargada.
     */
    public function esValido(): bool
    {
        return $this->fecha_fin->isFuture();
    }

    /**
     * Calcula el precio final sin permitir importes negativos.
     * Los descuentos pueden ser porcentuales o una cantidad fija en euros.
     */
    public function calcularPrecioDescontado(float $precioOriginal): float
    {
        if ($this->tipo === 'porcentaje') {
            $descuento = $precioOriginal * ($this->valor / 100);
            return max(0, $precioOriginal - $descuento);
        }

        if ($this->tipo === 'fijo') {
            return max(0, $precioOriginal - $this->valor);
        }

        return $precioOriginal;
    }
}
