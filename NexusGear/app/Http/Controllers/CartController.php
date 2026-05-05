<?php

namespace App\Http\Controllers;

use App\Models\Carrito;
use App\Models\Producto;
use App\Services\GuestCartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(): View
    {
        if (Auth::check()) {
            $cart = $this->currentCart()->load('items.producto');
        } else {
            $cart = $this->buildGuestCartView();
        }

        return view('cart.index', ['cart' => $cart]);
    }

    public function store(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate(['cantidad' => ['required', 'integer', 'min:1']]);

        if (!$producto->disponible) {
            return back()->with('error', 'Este producto no tiene stock disponible.');
        }

        if (Auth::check()) {
            $cart        = $this->currentCart();
            $item        = $cart->items()->where('producto_id', $producto->id)->first();
            $newQuantity = ($item?->cantidad ?? 0) + $data['cantidad'];

            if ($newQuantity > $producto->stock) {
                return back()->with('error', 'No hay suficiente stock para añadir esa cantidad.');
            }

            if ($item) {
                $cart->items()->where('producto_id', $producto->id)->update([
                    'cantidad'      => $newQuantity,
                    'precio_actual' => $producto->precio,
                ]);
            } else {
                $cart->items()->create([
                    'producto_id'   => $producto->id,
                    'cantidad'      => $newQuantity,
                    'precio_actual' => $producto->precio,
                ]);
            }
        } else {
            $guest       = app(GuestCartService::class);
            $existing    = collect($guest->items())->firstWhere('producto_id', $producto->id);
            $newQuantity = ($existing['cantidad'] ?? 0) + $data['cantidad'];

            if ($newQuantity > $producto->stock) {
                return back()->with('error', 'No hay suficiente stock para añadir esa cantidad.');
            }

            $guest->add($producto->id, $data['cantidad'], (float) $producto->precio);
        }

        return redirect()->route('cart.index')->with('success', 'Producto añadido al carrito.');
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate(['cantidad' => ['required', 'integer', 'min:1']]);

        if ($data['cantidad'] > $producto->stock) {
            return back()->with('error', 'La cantidad solicitada supera el stock disponible.');
        }

        if (Auth::check()) {
            $this->currentCart()->items()->where('producto_id', $producto->id)->firstOrFail();
            $this->currentCart()->items()->where('producto_id', $producto->id)->update([
                'cantidad'      => $data['cantidad'],
                'precio_actual' => $producto->precio,
            ]);
        } else {
            app(GuestCartService::class)->update($producto->id, $data['cantidad'], (float) $producto->precio);
        }

        return back()->with('success', 'Carrito actualizado.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if (Auth::check()) {
            $this->currentCart()->items()->where('producto_id', $producto->id)->delete();
        } else {
            app(GuestCartService::class)->remove($producto->id);
        }

        return back()->with('success', 'Producto eliminado del carrito.');
    }

    public function clear(): RedirectResponse
    {
        if (Auth::check()) {
            $this->currentCart()->items()->delete();
        } else {
            app(GuestCartService::class)->clear();
        }

        return back()->with('success', 'Carrito vaciado.');
    }

    private function currentCart(): Carrito
    {
        return Carrito::firstOrCreate(['usuario_id' => Auth::id()]);
    }

    private function buildGuestCartView(): object
    {
        $rawItems   = app(GuestCartService::class)->items();
        $productIds = array_column($rawItems, 'producto_id');
        $products   = $productIds
            ? Producto::whereIn('id', $productIds)->get()->keyBy('id')
            : collect();

        $items = collect($rawItems)
            ->filter(fn($i) => isset($products[$i['producto_id']]))
            ->map(fn($i) => (object) [
                'producto_id'              => $i['producto_id'],
                'cantidad'                 => $i['cantidad'],
                'precio_actual'            => $i['precio_actual'],
                'precio_actual_formateado' => number_format((float) $i['precio_actual'], 2, ',', '.') . ' €',
                'subtotal_formateado'      => number_format($i['cantidad'] * (float) $i['precio_actual'], 2, ',', '.') . ' €',
                'producto'                 => $products[$i['producto_id']],
            ])
            ->values();

        $total = $items->sum(fn($i) => $i->cantidad * (float) $i->precio_actual);

        return (object) [
            'items'            => $items,
            'cantidad_total'   => (int) $items->sum('cantidad'),
            'total_formateado' => number_format($total, 2, ',', '.') . ' €',
        ];
    }
}
