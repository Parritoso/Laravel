<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Categoria;
use App\Services\MongoLog\AdminAuditService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::withCount('productos')->orderBy('nombre')->get();

        return view('admin.categorias.index', compact('categorias'));
    }

    public function create(): View
    {
        return view('admin.categorias.create', ['categoria' => new Categoria]);
    }

    public function store(Request $request): RedirectResponse
    {
        $categoria = Categoria::create($this->validatedData($request));

        AdminAuditService::track('store', 'Categoria', $categoria->id, null, $categoria->toArray());

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', __('messages.admin_category_created'));
    }

    public function show(Categoria $categoria): View
    {
        // La ficha de categoría resume ventas, stock y descuentos de todos sus productos.
        // Por eso se cargan aquí las relaciones que alimentan esos indicadores.
        $categoria->load([
            'productos.descuentos',
            'productos.favoritos',
            'productos.itemsCarrito',
            'productos.lineasPedido.pedido',
        ]);

        $productos = $categoria->productos->sortBy('nombre')->values();

        $totalProductos = $productos->count();
        $stockTotal = $productos->sum('stock');
        $stockBajo = $productos->where('stock', '<=', 5)->count();
        $productosDestacados = $productos->where('destacado', true)->count();
        $unidadesVendidas = $productos->sum(fn ($producto) => $producto->lineasPedido->sum('cantidad'));
        $ingresosTotales = $productos->sum(fn ($producto) => $producto->lineasPedido->sum('subtotal'));
        $productosConDescuento = $productos->filter(fn ($producto) => $producto->descuentos->contains(fn ($descuento) => $descuento->esValido()))->count();
        $precioMedio = $totalProductos > 0 ? $productos->avg(fn ($producto) => (float) $producto->precio) : 0;
        $productoMasVendido = $productos
            ->sortByDesc(fn ($producto) => $producto->lineasPedido->sum('cantidad'))
            ->first();

        return view('admin.categorias.show', [
            'categoria' => $categoria,
            'productos' => $productos,
            'totalProductos' => $totalProductos,
            'stockTotal' => $stockTotal,
            'stockBajo' => $stockBajo,
            'productosDestacados' => $productosDestacados,
            'unidadesVendidas' => $unidadesVendidas,
            'ingresosFormateados' => number_format((float) $ingresosTotales, 2, ',', '.') . ' €',
            'productosConDescuento' => $productosConDescuento,
            'precioMedioFormateado' => number_format((float) $precioMedio, 2, ',', '.') . ' €',
            'productoMasVendido' => $productoMasVendido,
        ]);
    }

    public function edit(Categoria $categoria): View
    {
        return view('admin.categorias.edit', compact('categoria'));
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $oldValues = $categoria->toArray();

        $categoria->update($this->validatedData($request, $categoria->id));

        AdminAuditService::track('update', 'Categoria', $categoria->id, $oldValues, $categoria->refresh()->toArray());

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', __('messages.admin_category_updated'));
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        // Se bloquea el borrado si aún hay productos asociados para no dejar productos sin clasificar.
        if ($categoria->productos()->exists()) {
            return back()->with('error', __('messages.admin_category_in_use'));
        }

        $oldValues = $categoria->toArray();
        $categoria->delete();

        AdminAuditService::track('destroy', 'Categoria', $categoria->id, $oldValues, null);

        return redirect()
            ->route('admin.categorias.index')
            ->with('success', __('messages.admin_category_deleted'));
    }

    private function validatedData(Request $request, ?int $ignoreId = null): array
    {
        // El slug queda limitado a minúsculas, números y guiones para usarlo sin problemas en URLs.
        return $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'slug'   => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-z0-9]+(?:-[a-z0-9]+)*$/',
                'unique:categorias,slug'.($ignoreId ? ','.$ignoreId : ''),
            ],
        ]);
    }
}
