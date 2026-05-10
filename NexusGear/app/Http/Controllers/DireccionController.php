<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Direccion;
use Illuminate\Http\Request;

class DireccionController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:10',
        ]);

        $user = auth()->user();
        
        $user->direcciones()->create([
            'calle' => $validated['calle'],
            'numero' => $validated['numero'],
            'ciudad' => $validated['ciudad'],
            'codigo_postal' => $validated['codigo_postal'],
            'es_predeterminada' => $user->direcciones()->count() === 0
        ]);

        return back()->with('success', __('messages.address_added'));
    }

    public function destroy(Direccion $direccion)
    {
        if ($direccion->usuario_id !== auth()->id()) abort(403);
        
        $direccion->delete();
        return back()->with('success', __('messages.address_deleted'));
    }

    public function update(Request $request, Direccion $direccion)
    {
        // 1. Validar que la dirección le pertenece al usuario autenticado
        if ($direccion->usuario_id !== auth()->id()) {
            abort(403);
        }

        // 2. Validar los datos
        $validated = $request->validate([
            'calle' => 'required|string|max:255',
            'numero' => 'required|string|max:20',
            'ciudad' => 'required|string|max:100',
            'codigo_postal' => 'required|string|max:10',
        ]);

        // 3. Actualizar
        $direccion->update($validated);

        return back()->with('success', __('messages.address_updated'));
    }
}
