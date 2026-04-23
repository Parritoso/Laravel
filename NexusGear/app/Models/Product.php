<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $table = 'productos';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'precio',
        'descripcion',
    ];

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
}
