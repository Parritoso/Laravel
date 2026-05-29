<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Models\Producto;
use App\Models\Descuento;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
                $query->whereHas('categorias', fn ($q) => $q->where('slug', $slug));
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
        $data = $this->validatedData($request);

        // La imagen se valida aparte porque no forma parte de los campos editables básicos.
        if ($request->hasFile('imagen')) {
            $request->validate(['imagen' => ['image', 'mimes:jpg,jpeg,png', 'max:2048']]);
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $product = Producto::create($data);

        // El producto puede aparecer en varias categorías; sync deja la tabla pivote igual que el formulario.
        $product->categorias()->sync($request->categorias);

        if ($request->filled('descuento_id')) {
            $product->descuentos()->sync([$request->descuento_id]);
        }

        return redirect()
            ->route('admin.products.edit', $product)
            ->with('success', __('messages.admin_product_created'));
    }

    public function show(Producto $producto): View
    {
        // Se cargan las relaciones necesarias para calcular el resumen administrativo sin consultas repetidas.
        $producto->load([
            'categorias',
            'descuentos',
            'favoritos',
            'itemsCarrito',
            'lineasPedido.pedido',
        ]);

        $totalVendidas       = $producto->lineasPedido->sum('cantidad');
        $totalIngresos       = $producto->lineasPedido->sum('subtotal');
        $ingresosFormateados = number_format((float) $totalIngresos, 2, ',', '.') . ' €';
        $totalFavoritos      = $producto->favoritos->count();
        $totalCarrito        = $producto->itemsCarrito->sum('cantidad');
        $ultimasVentas       = $producto->lineasPedido->sortByDesc('pedido_id')->take(10);

        $descuentoActivo = $producto->descuentos->first(fn ($d) => $d->esValido());
        $precioFinal     = $descuentoActivo
            ? $descuentoActivo->calcularPrecioDescontado((float) $producto->precio)
            : null;

        return view('admin.products.show', compact(
            'producto',
            'totalVendidas',
            'ingresosFormateados',
            'totalFavoritos',
            'totalCarrito',
            'ultimasVentas',
            'descuentoActivo',
            'precioFinal',
        ));
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
        $oldPrecioFinal = (float) $producto->precio_final;
        $oldStock = (int) $producto->stock;
        $data = $this->validatedData($request);

        if ($request->hasFile('imagen')) {
            $request->validate(['imagen' => ['image', 'mimes:jpg,jpeg,png', 'max:2048']]);
            // Al cambiar la imagen se borra la anterior para no dejar archivos sin usar en storage.
            if ($producto->imagen) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $producto->update($data);

        $producto->categorias()->sync($request->categorias);

        $descuentoId = $request->filled('descuento_id') ? [$request->descuento_id] : [];
        $producto->descuentos()->sync($descuentoId);

        $producto->refresh();
        $producto->procesarAlertasDeFavoritos($oldPrecioFinal, $oldStock);

        return redirect()
            ->route('admin.products.edit', $producto)
            ->with('success', __('messages.admin_product_updated'));
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        // No se eliminan productos ya vendidos para conservar el historial de pedidos y facturas.
        if ($producto->lineasPedido()->exists()) {
            return back()->with('error', __('messages.admin_product_in_use'));
        }

        if ($producto->imagen) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()
            ->route('admin.products.index')
            ->with('success', __('messages.admin_product_deleted'));
    }

    private function validatedData(Request $request): array
    {
        // Reglas compartidas por alta y edición. Las categorías se validan como array
        // porque el modelo usa una relación muchos a muchos.
        $data = $request->validate([
            'nombre'       => ['required', 'string', 'max:255'],
            'precio'       => ['required', 'numeric', 'min:0'],
            'descripcion'  => ['required', 'string', 'max:2000'],
            'stock'        => ['required', 'integer', 'min:0'],
            'destacado'    => ['nullable', 'boolean'],
            'descuento_id' => ['nullable', 'exists:descuentos,id'],
            'categorias'   => ['required', 'array', 'min:1'],
            'categorias.*' => ['exists:categorias,id'],
        ]);

        $data['destacado'] = $request->boolean('destacado');

        return $data;
    }
}
