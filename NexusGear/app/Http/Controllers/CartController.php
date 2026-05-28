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

        // No se permite añadir un producto agotado aunque llegue una petición manual al controlador.
        if (!$producto->disponible) {
            return back()->with('error', __('messages.cart_out_of_stock'));
        }

        if (Auth::check()) {
            $cart        = $this->currentCart();
            $item        = $cart->items()->where('producto_id', $producto->id)->first();
            $newQuantity = ($item?->cantidad ?? 0) + $data['cantidad'];

            // Se valida la cantidad final, no solo la cantidad enviada en esta petición.
            if ($newQuantity > $producto->stock) {
                return back()->with('error', __('messages.cart_not_enough_stock'));
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

            // El carrito invitado usa cookie, pero aplica las mismas reglas de stock que el carrito en BD.
            if ($newQuantity > $producto->stock) {
                return back()->with('error', __('messages.cart_not_enough_stock'));
            }

            $guest->add($producto->id, $data['cantidad'], (float) $producto->precio);
        }

        return redirect()->route('cart.index')->with('success', __('messages.cart_added'));
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $data = $request->validate(['cantidad' => ['required', 'integer', 'min:1']]);

        if ($data['cantidad'] > $producto->stock) {
            return back()->with('error', __('messages.cart_quantity_stock'));
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

        return back()->with('success', __('messages.cart_updated'));
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if (Auth::check()) {
            $this->currentCart()->items()->where('producto_id', $producto->id)->delete();
        } else {
            app(GuestCartService::class)->remove($producto->id);
        }

        return back()->with('success', __('messages.cart_removed'));
    }

    public function clear(): RedirectResponse
    {
        if (Auth::check()) {
            $this->currentCart()->items()->delete();
        } else {
            app(GuestCartService::class)->clear();
        }

        return back()->with('success', __('messages.cart_cleared'));
    }

    private function currentCart(): Carrito
    {
        return Carrito::firstOrCreate(['usuario_id' => Auth::id()]);
    }

    /**
     * Adapta el carrito guardado en cookie al mismo formato que consume la vista del carrito.
     * La cookie guarda solo datos mínimos; los productos se vuelven a consultar para evitar
     * mostrar información obsoleta si el catálogo cambió desde la última visita.
     */
    private function buildGuestCartView(): object
    {
        $rawItems   = app(GuestCartService::class)->items();
        $productIds = array_column($rawItems, 'producto_id');
        $products   = $productIds
            ? Producto::whereIn('id', $productIds)->get()->keyBy('id')
            : collect();

        $items = collect($rawItems)
            // Si un producto fue eliminado del catálogo, no se muestra aunque siga en la cookie.
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

        $hasStockIssues = $items->contains(fn($i) => $i->cantidad > $i->producto->stock);

        return (object) [
            'items'            => $items,
            'cantidad_total'   => (int) $items->sum('cantidad'),
            'total_formateado' => number_format($total, 2, ',', '.') . ' €',
            'has_stock_issues' => $hasStockIssues,
        ];
    }
}
