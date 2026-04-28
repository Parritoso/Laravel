<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        $cart = $this->currentCart()->load('items.producto');

        return view('cart.index', [
            'cart' => $cart,
        ]);
    }

    public function store(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        if (! $producto->disponible) {
            return back()->with('error', 'Este producto no tiene stock disponible.');
        }

        $cart = $this->currentCart();
        $item = $cart->items()->where('producto_id', $producto->id)->first();
        $newQuantity = ($item?->cantidad ?? 0) + $data['cantidad'];

        if ($newQuantity > $producto->stock) {
            return back()->with('error', 'No hay suficiente stock para añadir esa cantidad.');
        }

        if ($item) {
            $cart->items()
                ->where('producto_id', $producto->id)
                ->update([
                    'cantidad' => $newQuantity,
                    'precio_actual' => $producto->precio,
                ]);
        } else {
            $cart->items()->create([
                'producto_id' => $producto->id,
                'cantidad' => $newQuantity,
                'precio_actual' => $producto->precio,
            ]);
        }

        return redirect()
            ->route('cart.index')
            ->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate([
            'cantidad' => ['required', 'integer', 'min:1'],
        ]);

        if ($data['cantidad'] > $producto->stock) {
            return back()->with('error', 'La cantidad solicitada supera el stock disponible.');
        }

        $this->currentCart()->items()->where('producto_id', $producto->id)->firstOrFail();

        $this->currentCart()->items()->where('producto_id', $producto->id)->update([
            'cantidad' => $data['cantidad'],
            'precio_actual' => $producto->precio,
        ]);

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        $this->currentCart()
            ->items()
            ->where('producto_id', $producto->id)
            ->delete();

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(): RedirectResponse
    {
        $this->currentCart()->items()->delete();

        return back()->with('success', 'Carrito vaciado.');
    }

    private function currentCart(): Carrito
    {
        return Carrito::firstOrCreate([
            'usuario_id' => Auth::id(),
        ]);
    }

}
