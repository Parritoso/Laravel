<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Descuento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate([
            'q'       => ['nullable', 'string', 'max:80'],
            'profile' => ['nullable', 'string', 'exists:categorias,slug'],
        ]);

        $products = Producto::query()
            ->when($filters['q'] ?? null, function ($query, string $search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            })
            ->when($filters['profile'] ?? null, function ($query, string $slug) {
                $query->whereHas('categoria', fn ($q) => $q->where('slug', $slug));
            })
            ->orderBy('nombre')
            ->paginate(12)
            ->withQueryString();

        return view('admin.products.index', [
            'products' => $products,
            'filters'  => $filters,
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'product'    => new Producto(['stock' => 0]),
            'categorias' => Categoria::orderBy('nombre')->get(),
            'descuentos' => Descuento::active()->orderBy('codigo')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $product = Producto::create($this->validatedData($request));

        // Sincronizamos el descuento si se ha seleccionado uno
        if ($request->filled('descuento_id')) {
            $product->descuentos()->sync([$request->descuento_id]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', 'Producto creado correctamente.');
    }

    public function show(Producto $producto): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $producto);
    }

    public function edit(Producto $producto): View
    {
        $producto->load('descuentos');

        return view('admin.products.edit', [
            'product'    => $producto,
            'categorias' => Categoria::orderBy('nombre')->get(),
            'descuentos' => Descuento::active()->orderBy('codigo')->get(),
        ]);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $producto->update($this->validatedData($request));

        $descuentoId = $request->filled('descuento_id') ? [$request->descuento_id] : [];
        $producto->descuentos()->sync($descuentoId);

        return redirect()
            ->route('admin.products.edit', $producto)
            ->with('success', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if ($producto->lineasPedido()->exists()) {
            return back()->with('error', 'No se puede eliminar un producto que ya aparece en pedidos.');
        }

        $producto->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', 'Producto eliminado correctamente.');
    }

    private function validatedData(Request $request): array
    {
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'precio'       => ['required', 'numeric', 'min:0'],
            'descripcion'  => ['required', 'string', 'max:2000'],
            'stock'        => ['required', 'integer', 'min:0'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'destacado'    => ['nullable', 'boolean'],
            'descuento_id' => ['nullable', 'exists:descuentos,id'],
        ]);

        $data['destacado'] = $request->boolean('destacado');

        return $data;
    }
}
