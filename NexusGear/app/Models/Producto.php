<?php

namespace App\Models;

use App\Notifications\ProductAlertNotification;
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
        'imagen',
        'stock',
        'destacado',
    ];

    protected $casts = [
        'precio'       => 'decimal:2',
        'stock'        => 'integer',
        'destacado'    => 'boolean',
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
        $names = $this->categorias->pluck('nombre')->implode(', ');

        return $names !== '' ? $names : __('common.no_category');
    }

    public function getIconoAttribute(): string
    {
        $nombre = strtolower($this->nombre);

        // Icono orientativo para tarjetas del catálogo cuando no se necesita una imagen específica.
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

    public function comentarios(): HasMany
    {
        return $this->hasMany(Comentario::class, 'producto_id');
    }

    /**
     * Devuelve el precio vigente del producto aplicando el primer descuento activo.
     * Si no hay descuentos válidos, se mantiene el precio base.
     */
    public function getPrecioFinalAttribute()
    {
        $descuento = $this->descuentos()->active()->first();
        
        return $descuento 
            ? $descuento->calcularPrecioDescontado($this->precio) 
            : $this->precio;
    }

    /**
     * Puntuación media redondeada a un decimal para mostrarla en la ficha del producto.
     */
    public function getPuntuacionMediaAttribute(): float
    {
        return round($this->comentarios()->avg('puntuacion') ?? 0, 1);
    }

    /**
     * Total de valoraciones publicadas para este producto.
     */
    public function getTotalComentariosAttribute(): int
    {
        return $this->comentarios()->count();
    }

    public function procesarAlertasDeFavoritos(float $precioFinalAnterior, int $stockAnterior): void
    {
        $precioFinalActual = (float) $this->precio_final;
        $stockActual = (int) $this->stock;

        if ($precioFinalActual < $precioFinalAnterior) {
            $this->dispararAlerta('precio', [
                'antiguo' => $precioFinalAnterior,
                'nuevo' => $precioFinalActual,
            ]);
        }

        if ($stockAnterior > 0 && $stockActual === 0) {
            $this->dispararAlerta('stock_agotado');
            return;
        }

        if ($stockAnterior === 0 && $stockActual > 0) {
            $this->dispararAlerta('stock_disponible');
            return;
        }

        if ($stockActual > 0 && $stockActual < $stockAnterior) {
            $this->dispararAlerta('stock_bajo', ['stock_anterior' => $stockAnterior]);
        }
    }

    private function dispararAlerta(string $tipo, array $detalles = []): void
    {
        $query = Favorito::where('producto_id', $this->id)->with('usuario');

        match ($tipo) {
            'precio'           => $query->where('alerta_precio', true),
            'stock_agotado'    => $query->where('alerta_stock_agotado', true),
            'stock_disponible' => $query->where('alerta_stock_disponible', true),
            'stock_bajo'       => $query
                ->where('alerta_stock_bajo', true)
                ->where('umbral_stock', '>=', $this->stock)
                ->where('umbral_stock', '<', $detalles['stock_anterior'] ?? PHP_INT_MAX),
        };

        foreach ($query->get() as $favorito) {
            $favorito->usuario->notify(new ProductAlertNotification($this, $tipo, $detalles));
        }
    }
}
