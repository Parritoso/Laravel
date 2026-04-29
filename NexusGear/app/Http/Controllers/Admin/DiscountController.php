<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Descuento;
use Illuminate\Validation\Rule;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscountController extends Controller {
    /**
     * Muestra el listado de todos los descuentos.
     */
    public function index(){
        $descuentos = Descuento::withCount('productos')->get();;
        return view('admin.discounts.index', compact('descuentos'));
    }

    public function create(): View
    {
        return view('admin.discounts.create', ['discount' => new Descuento()]);
    }

    /**
     * Almacena un nuevo descuento en la base de datos.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'codigo' => 'required|unique:descuentos|max:255',
            'tipo' => 'required|in:fijo,porcentaje', // Ejemplo de tipos
            'valor' => 'required|numeric|min:0',
            'fecha_fin' => 'required|date|after:today',
        ]);

        Descuento::create($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Descuento creado correctamente.');
    }

    /**
     * Muestra un descuento específico (y opcionalmente los productos asociados).
     */
    public function show(Descuento $descuento){
        $descuento->load('productos'); 
        return view('admin.discounts.show', compact('descuento'));
    }

    /**
     * Muestra el formulario para editar un descuento.
     */
    public function edit(Descuento $discount){
        return view('admin.discounts.edit', ['discount' => $discount]);
    }

    /**
     * Actualiza el descuento en la base de datos.
     */
    public function update(Request $request,Descuento $discount): RedirectResponse{
        $validated = $request->validate([
            'codigo' => 'required|max:255|unique:descuentos,codigo,' . $discount->id,
            'tipo' => 'required|in:fijo,porcentaje',
            'valor' => 'required|numeric|min:0',
            'fecha_fin' => 'required|date',
        ]);

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')->with('success', 'Descuento actualizado.');
    }

    /**
     * Elimina un descuento específico.
     */
    public function destroy(Descuento $descuento){
        $descuento->delete();
        return redirect()->route('admin.discounts.index')->with('success', 'Descuento eliminado.');
    }
}