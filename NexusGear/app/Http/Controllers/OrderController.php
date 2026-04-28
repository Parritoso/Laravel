<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function index(): View
    {
        $orders = Auth::user()
            ->pedidos()
            ->with('factura')
            ->latest('fecha')
            ->paginate(10);

        return view('orders.index', [
            'orders' => $orders,
        ]);
    }

    public function store(): RedirectResponse
    {
        $cart = $this->currentCart()->load('items.producto');

        if ($cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', 'El carrito está vacío.');
        }

        foreach ($cart->items as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', "No hay suficiente stock de {$item->producto->nombre}.");
            }
        }

        try {
            $pedido = DB::transaction(function () use ($cart) {
                $pedido = Pedido::create([
                    'usuario_id' => Auth::id(),
                    'estado' => 'pendiente',
                    'fecha' => now(),
                ]);

                $subtotal = 0.0;

                foreach ($cart->items as $item) {
                    $producto = Producto::whereKey($item->producto_id)->lockForUpdate()->firstOrFail();

                    if ($item->cantidad > $producto->stock) {
                        throw new \RuntimeException("No hay suficiente stock de {$producto->nombre}.");
                    }

                    $lineSubtotal = round($item->cantidad * (float) $producto->precio, 2);
                    $subtotal += $lineSubtotal;

                    $pedido->lineas()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $item->cantidad,
                        'precio_unitario' => $producto->precio,
                        'subtotal' => $lineSubtotal,
                    ]);

                    $producto->decrement('stock', $item->cantidad);
                }

                $iva = round($subtotal * 0.21, 2);

                $pedido->factura()->create([
                    'numero_factura' => $this->invoiceNumber($pedido),
                    'subtotal' => $subtotal,
                    'iva' => $iva,
                    'total_factura' => round($subtotal + $iva, 2),
                    'fecha_emision' => now(),
                ]);

                $cart->items()->delete();

                return $pedido->load('lineas.producto', 'factura', 'usuario');
            });
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        }

        Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($pedido));

        return redirect()
            ->route('orders.show', $pedido)
            ->with('success', 'Pedido realizado correctamente. Te hemos enviado la confirmación por correo.');
    }

    public function show(Pedido $pedido): View
    {
        abort_unless($pedido->usuario_id === Auth::id(), 403);

        return view('orders.show', [
            'order' => $pedido->load('lineas.producto', 'factura'),
        ]);
    }

    private function currentCart(): Carrito
    {
        return Carrito::firstOrCreate([
            'usuario_id' => Auth::id(),
        ]);
    }

    private function invoiceNumber(Pedido $pedido): string
    {
        return 'NG-'.now()->format('Ymd').'-'.str_pad((string) $pedido->id, 6, '0', STR_PAD_LEFT);
    }
}
