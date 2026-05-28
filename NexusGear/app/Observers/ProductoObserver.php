<?php

namespace App\Observers;

use App\Models\Producto;
use App\Models\Favorito;
use App\Notifications\ProductAlertNotification;

class ProductoObserver
{
    /**
     * Handle the Producto "created" event.
     */
    public function created(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "updated" event.
     */
    public function updated(Producto $producto)
    {
        // CASO A: DETECTAR CAMBIO O BAJADA DE PRECIO
        if ($producto->isDirty('precio')) {
            $precioAntiguo = $producto->getOriginal('precio');
            $precioNuevo = $producto->precio;

            // Solo avisamos si el precio disminuye (oferta/descuento)
            if ($precioNuevo < $precioAntiguo) {
                $interesados = Favorito::where('producto_id', $producto->id)
                    ->where('alerta_precio', true)
                    ->with('usuario')
                    ->get();

                foreach ($interesados as $favorito) {
                    $favorito->usuario->notify(new ProductAlertNotification($producto, 'precio', [
                        'antiguo' => $precioAntiguo,
                        'nuevo'   => $precioNuevo
                    ]));
                }
            }
        }

        // CASO B: DETECTAR BAJADA CRÍTICA DE STOCK
        if ($producto->isDirty('stock')) {
            $stockAntiguo = $producto->getOriginal('stock');
            $stockNuevo = $producto->stock;

            // Si el stock disminuye, comprobamos qué usuarios tienen el umbral afectado
            if ($stockNuevo < $stockAntiguo) {
                $interesados = Favorito::where('producto_id', $producto->id)
                    ->where('alerta_stock', true)
                    ->where('umbral_stock', '>=', $stockNuevo)
                    // Evitamos spamear si el producto ya estaba por debajo del umbral en el cambio anterior
                    ->where('umbral_stock', '<', $stockAntiguo) 
                    ->with('usuario')
                    ->get();

                foreach ($interesados as $favorito) {
                    $favorito->usuario->notify(new ProductAlertNotification($producto, 'stock'));
                }
            }
        }
    }

    /**
     * Handle the Producto "deleted" event.
     */
    public function deleted(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "restored" event.
     */
    public function restored(Producto $producto): void
    {
        //
    }

    /**
     * Handle the Producto "force deleted" event.
     */
    public function forceDeleted(Producto $producto): void
    {
        //
    }
}
