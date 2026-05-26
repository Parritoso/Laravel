<?php

namespace App\Http\Controllers;

use App\Models\Carrito;

class CheckoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cart = Carrito::where('usuario_id', $user->id)->with('items.producto')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('messages.checkout_empty_cart'));
        }

        if ($cart->hasStockIssues()) {
            return redirect()->route('cart.index')->with(
                'error', 
                __('Algunos artículos de tu carrito ya no disponen del stock suficiente. Por favor, revísalos.')
            );
        }

        return view('auth.checkout.index', [
            'user' => $user,
            'cart' => $cart
        ]);
    }
}
