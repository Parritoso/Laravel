<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Comentario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ComentarioController extends Controller
{
    public function store(Request $request, Producto $producto)
    {
        $data = $request->validate([
            'puntuacion' => 'required|numeric|between:1,5',
            'contenido'  => 'nullable|string|max:1000',
        ]);

        // Un usuario solo puede tener una valoración por producto; si vuelve a comentar, se actualiza.
        $producto->comentarios()->updateOrCreate(
            ['user_id' => Auth::id()],
            [
                'puntuacion' => $data['puntuacion'],
                'contenido'  => $data['contenido'],
            ]
        );

        return back()->with('success', __('products/show.review_saved'));
    }

    public function destroy(Comentario $comentario)
    {
        // Solo el autor puede borrar su valoración.
        if ($comentario->user_id !== Auth::id()) {
            abort(403, __('produtcs/show.non_delete'));
        }

        $comentario->delete();

        return back()->with('success', __('products/show.review_deleted'));
    }
}
