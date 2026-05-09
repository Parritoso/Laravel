<?php
namespace App\Http\Controllers;
use App\Models\Carrito;
use App\Models\Producto;
class checkoutController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $cart = Carrito::where('usuario_id', $user->id)->with('items.producto')->first();

        if (!$cart || $cart->items->isEmpty()) {
            return redirect()->route('cart.index')->with('error', __('messages.checkout_empty_cart'));
        }

        return view('auth.checkout.index', [
            'user' => $user,
            'cart' => $cart
        ]);
    }
}
