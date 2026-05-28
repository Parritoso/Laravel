<?php

namespace App\Http\Controllers;

use App\Models\Carrito;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cart = Carrito::where('usuario_id', $user->id)->with('items.producto')->first();

        // El checkout solo tiene sentido con un carrito persistido y con productos cargados.
        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('messages.checkout_empty_cart'));
        }

        // Si el stock cambió mientras el usuario compraba, se le devuelve al carrito para corregir cantidades.
        if ($cart->hasStockIssues()) {
            return redirect()->route('cart.index')->with(
                'error', 
                __('cart/index.checkout_stock_insufficient')
            );
        }

        return view('auth.checkout.index', [
            'user' => $user,
            'cart' => $cart
        ]);
    }
}
