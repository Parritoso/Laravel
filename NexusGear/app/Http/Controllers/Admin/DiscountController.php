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
     * Lista los descuentos junto al número de productos que los tienen asignados.
     */
    public function index(){
        $descuentos = Descuento::withCount('productos')->get();
        return view('admin.discounts.index', compact('descuentos'));
    }

    public function create(): View
    {
        return view('admin.discounts.create', ['discount' => new Descuento()]);
    }

    /**
     * Crea un descuento. La fecha final debe ser futura para que no nazca caducado.
     */
    public function store(Request $request){
        $validated = $request->validate([
            'codigo' => 'required|unique:descuentos|max:255',
            'tipo' => 'required|in:fijo,porcentaje',
            'valor' => 'required|numeric|min:0',
            'fecha_fin' => 'required|date|after:today',
        ]);

        Descuento::create($validated);

        return redirect()->route('admin.discounts.index')->with('success', __('messages.admin_discount_created'));
    }

    /**
     * Muestra el descuento con los productos asociados desde la tabla pivote.
     */
    public function show(Descuento $discount){
        $discount->load('productos'); 
        return view('admin.discounts.show', compact('discount'));
    }

    /**
     * Muestra el formulario para editar un descuento.
     */
    public function edit(Descuento $discount){
        return view('admin.discounts.edit', ['discount' => $discount]);
    }

    /**
     * Actualiza el descuento manteniendo el código único excepto para el propio registro.
     */
    public function update(Request $request,Descuento $discount): RedirectResponse{
        $validated = $request->validate([
            'codigo' => 'required|max:255|unique:descuentos,codigo,' . $discount->id,
            'tipo' => 'required|in:fijo,porcentaje',
            'valor' => 'required|numeric|min:0',
            'fecha_fin' => 'required|date',
        ]);

        $discount->update($validated);

        return redirect()->route('admin.discounts.index')->with('success', __('messages.admin_discount_updated'));
    }

    /**
     * Elimina el descuento; las relaciones pivote se limpian por las claves foráneas.
     */
    public function destroy(Descuento $discount){
        $discount->delete();
        return redirect()->route('admin.discounts.index')->with('success', __('messages.admin_discount_deleted'));
    }
}
