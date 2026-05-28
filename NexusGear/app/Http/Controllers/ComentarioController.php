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
        // Seguridad: Verificar que el comentario pertenece al usuario logueado
        if ($comentario->user_id !== Auth::id()) {
            abort(403, __('produtcs/show.non_delete'));
        }

        $comentario->delete();

        return back()->with('success', __('products/show.review_deleted'));
    }
}