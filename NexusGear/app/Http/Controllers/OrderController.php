<?php

namespace App\Http\Controllers;

use App\Mail\OrderConfirmationMail;
use App\Models\Carrito;
use App\Models\Pedido;
use App\Models\Producto;
use App\Services\Payments\PaymentGateway;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Stripe\Exception\ApiErrorException;

class OrderController extends Controller
{
    public function __construct(private PaymentGateway $payments)
    {
    }

    public function index(): View
    {
        $orders = Auth::user()
            ->pedidos()
            ->whereHas('factura')
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

        if ($cart->hasStockIssues()) {
            return redirect()->route('cart.index')->with('error', __('cart/index.checkout_stock_changed'));
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
            'number' => 'required_if:direccion_id,new|nullable|string|max:20',
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

                foreach ($cart->items as $item) {
                    $producto = Producto::whereKey($item->producto_id)->lockForUpdate()->firstOrFail();

                    if ($item->cantidad > $producto->stock) {
                        throw new \RuntimeException(__('messages.order_stock_product', ['product' => $producto->nombre]));
                    }

                    $precioOriginal = (float) $producto->precio;
                    $precioFinalUnitario = (float) $producto->precio_final;
                    $descuentoPorUnidad = $precioOriginal - $precioFinalUnitario;
                    $lineSubtotal = round($item->cantidad * $precioFinalUnitario, 2);

                    $pedido->lineas()->create([
                        'producto_id' => $producto->id,
                        'cantidad' => $item->cantidad,
                        'precio_original' => $precioOriginal,
                        'precio_unitario' => $precioFinalUnitario,
                        'descuento_total' => $descuentoPorUnidad * $item->cantidad,
                        'subtotal' => $lineSubtotal,
                    ]);
                }

                return $pedido->load('lineas');
            });

            $checkoutSession = $this->payments->createCheckoutSession(
                $pedido,
                Auth::id(),
                route('checkout.success', [], true).'?session_id={CHECKOUT_SESSION_ID}&order_id='.$pedido->id,
                route('checkout.cancel', $pedido, true),
            );

            return redirect()->away($checkoutSession->url);
        } catch (\RuntimeException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', $exception->getMessage());
        } catch (ApiErrorException $exception) {
            return redirect()
                ->route('cart.index')
                ->with('error', __('messages.payment_session_error'));
        }
    }

    public function success(Request $request): RedirectResponse
    {
        try {
            $sessionId = (string) $request->query('session_id', '');

            if ($sessionId === '') {
                return redirect()->route('cart.index')->with('error', __('messages.payment_not_confirmed'));
            }

            $session = $this->payments->retrieveCheckoutSession($sessionId);

            if ($session->payment_status !== 'paid') {
                return redirect()->route('cart.index')->with('error', __('messages.payment_not_confirmed'));
            }

            $pedido = Pedido::where('id', $request->query('order_id'))
                ->where('usuario_id', Auth::id())
                ->with('lineas.producto', 'factura', 'usuario')
                ->firstOrFail();

            if ($pedido->factura) {
                return redirect()->route('orders.show', $pedido)->with('success', __('messages.order_completed'));
            }

            DB::transaction(function () use ($pedido) {
                foreach ($pedido->lineas as $linea) {
                    $producto = Producto::whereKey($linea->producto_id)->lockForUpdate()->firstOrFail();

                    if ($linea->cantidad > $producto->stock) {
                        throw new \RuntimeException(__('messages.order_stock_product', ['product' => $producto->nombre]));
                    }

                    $producto->decrement('stock', $linea->cantidad);
                }

                $subtotal = (float) $pedido->lineas->sum('subtotal');
                $iva = round($subtotal * 0.21, 2);

                $pedido->factura()->create([
                    'numero_factura' => $this->invoiceNumber($pedido),
                    'subtotal' => $subtotal,
                    'iva' => $iva,
                    'total_factura' => round($subtotal + $iva, 2),
                    'fecha_emision' => now(),
                ]);

                $pedido->update(['estado' => 'procesando']);

                $this->currentCart()->items()->delete();
            });

            $pedido->load('lineas.producto', 'factura', 'usuario');

            Mail::to(Auth::user()->email)->send(new OrderConfirmationMail($pedido));

            return redirect()
                ->route('orders.show', $pedido)
                ->with('success', __('messages.order_completed'));
        } catch (\RuntimeException $exception) {
            return redirect()->route('cart.index')->with('error', $exception->getMessage());
        } catch (ApiErrorException $exception) {
            return redirect()->route('cart.index')->with('error', __('messages.payment_processing_error'));
        }
    }

    public function cancel(Pedido $pedido): RedirectResponse
    {
        abort_unless($pedido->usuario_id === Auth::id(), 403);

        if ($pedido->estado === 'pendiente' && ! $pedido->factura()->exists()) {
            $pedido->update(['estado' => 'cancelado']);
        }

        return redirect()
            ->route('cart.index')
            ->with('error', __('messages.payment_cancelled'));
    }

    public function show(Pedido $pedido): View
    {
        abort_unless($pedido->usuario_id === Auth::id(), 403);
        abort_unless($pedido->factura()->exists(), 404);

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
