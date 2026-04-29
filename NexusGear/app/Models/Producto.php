<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Producto extends Model
{
    protected $table = 'productos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
        'stock',
        'categoria_id',
        'destacado',
    ];

    protected $casts = [
        'precio'       => 'decimal:2',
        'stock'        => 'integer',
        'destacado'    => 'boolean',
        'categoria_id' => 'integer',
    ];

    public function getPrecioFormateadoAttribute(): string
    {
        return number_format((float) $this->precio, 2, ',', '.').' €';
    }

    public function getDisponibleAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function getPerfilNombreAttribute(): string
    {
        return $this->categoria->nombre ?? 'Sin categoría';
    }

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class, 'categoria_id');
    }

    public function getIconoAttribute(): string
    {
        $nombre = strtolower($this->nombre);

        return match (true) {
            str_contains($nombre, 'teclado') => 'bi-keyboard',
            str_contains($nombre, 'mouse'), str_contains($nombre, 'raton'), str_contains($nombre, 'ratón') => 'bi-mouse2',
            str_contains($nombre, 'reposamuñecas') => 'bi-hand-index-thumb',
            str_contains($nombre, 'soporte') => 'bi-laptop',
            default => 'bi-cpu',
        };
    }

    public function categorias(): BelongsToMany
    {
        return $this->belongsToMany(Categoria::class, 'producto_categoria', 'producto_id', 'categoria_id');
    }

    public function descuentos(): BelongsToMany
    {
        return $this->belongsToMany(Descuento::class, 'descuento_producto', 'producto_id', 'descuento_id');
    }

    public function favoritos(): HasMany
    {
        return $this->hasMany(Favorito::class, 'producto_id');
    }

    public function itemsCarrito(): HasMany
    {
        return $this->hasMany(ItemCarrito::class, 'producto_id');
    }

    public function lineasPedido(): HasMany
    {
        return $this->hasMany(LineaPedido::class, 'producto_id');
    }

    /**
    * Método extra para obtener el precio final ya rebajado
    */
    public function getPrecioFinalAttribute()
    {
        // Buscamos si tiene algún descuento activo
        $descuento = $this->descuentos()->active()->first();
        
        return $descuento 
            ? $descuento->calcularPrecioDescontado($this->precio) 
            : $this->precio;
    }
}
