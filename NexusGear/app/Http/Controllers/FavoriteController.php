<?php

namespace App\Http\Controllers;

use App\Models\Favorito;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class FavoriteController extends Controller
{
    public function index(): View
    {
        $favorites = Auth::user()
            ->favoritos()
            ->with(['producto.categorias', 'producto.descuentos'])
            ->orderByDesc('agregado_el')
            ->paginate(9);

        return view('favorites.index', [
            'favorites' => $favorites,
        ]);
    }

    public function store(Producto $producto): RedirectResponse
    {
        Favorito::firstOrCreate(
            [
                'usuario_id' => Auth::id(),
                'producto_id' => $producto->id,
            ],
            ['agregado_el' => now()],
        );

        return back()->with('success', __('favorites/index.added', ['product' => $producto->nombre]));
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        Auth::user()
            ->favoritos()
            ->where('producto_id', $producto->id)
            ->delete();

        return back()->with('success', __('favorites/index.removed', ['product' => $producto->nombre]));
    }
}
