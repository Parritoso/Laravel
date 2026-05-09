<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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

    public function store(Request $request): RedirectResponse
    {
        $cart = $this->currentCart()->load('items.producto');

        if ($cart->items->isEmpty()) {
            return redirect()
                ->route('cart.index')
                ->with('error', __('messages.cart_empty'));
        }

        foreach ($cart->items as $item) {
            if ($item->cantidad > $item->producto->stock) {
                return redirect()
                    ->route('cart.index')
                    ->with('error', __('messages.order_stock_product', ['product' => $item->producto->nombre]));
            }
        }

        $request->validate([
            'direccion_id' => 'required',
            'address' => 'required_if:direccion_id,new|nullable|string|max:255',
            'city' => 'required_if:direccion_id,new|nullable|string|max:100',
            'zip_code' => 'required_if:direccion_id,new|nullable|string|max:10',
        ]);

        try {
            $pedido = DB::transaction(function () use ($cart, $request) {
                $user = Auth::user();

                $dir = null;
                if ($request->direccion_id === 'new') {
                    $finalAddress = $request->address;
                    $finalCity = $request->city;
                    $finalZip = $request->zip_code;
                    $finalNumber = $request->number;

                    if ($request->has('save_address')) {
                        $dir = $user->direcciones()->create([
                            'calle' => $finalAddress,
                            'numero' => $finalNumber,
                            'ciudad' => $finalCity,
                            'codigo_postal' => $finalZip,
                        ]);
                        $dir = $dir->id;
                    }
                } else {
                    $dir = $user->direcciones()->findOrFail($request->direccion_id);
                    $finalAddress = $dir->calle;
                    $finalNumber = $dir->numero;
                    $finalCity = $dir->ciudad;
                    $finalZip = $dir->codigo_postal;
                    $dir = $dir->id;
                }

                $pedido = Pedido::create([
                    'usuario_id' => Auth::id(),
                    'estado' => 'pendiente',
                    'fecha' => now(),
                    'envio_calle' => $finalAddress,
                    'envio_numero' => $finalNumber,
                    'envio_ciudad' => $finalCity,
                    'envio_codigo_postal' => $finalZip,
                    'direccion_id' => $dir,
                ]);

                $subtotal = 0.0;

                foreach ($cart->items as $item) {
                    $producto = Producto::whereKey($item->producto_id)->lockForUpdate()->firstOrFail();

                    if ($item->cantidad > $producto->stock) {
                        throw new \RuntimeException(__('messages.order_stock_product', ['product' => $producto->nombre]));
                    }

                    $precioOriginal = (float) $producto->precio;
                    $precioFinalUnitario = (float) $producto->precio_final;
                    $descuentoPorUnidad = $precioOriginal - $precioFinalUnitario;    

                    $lineSubtotal = round($item->cantidad * (float) $precioFinalUnitario, 2);
                    $subtotal += $lineSubtotal;

                    $pedido->lineas()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $item->cantidad,
                        'precio_original' => $precioOriginal,
                        'precio_unitario' => $precioFinalUnitario,
                        'descuento_total' => $descuentoPorUnidad * $item->cantidad,
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
            ->with('success', __('messages.order_completed'));
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
