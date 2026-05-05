<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q'          => ['nullable', 'string', 'max:80'],
            'profile'    => ['nullable', 'string', 'exists:categorias,slug'],
            'sort'       => ['nullable', 'in:featured,price_asc,price_desc,name'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0', 'gte:precio_min'],
            'in_stock'   => ['nullable', 'boolean'],
            'ofertas'    => ['nullable', 'boolean'],
        ]);

        $query = Producto::query();

        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        if (! empty($filters['profile'])) {
            $slug = $filters['profile'];
            $query->whereHas('categoria', fn ($q) => $q->where('slug', $slug));
        }

        if (! empty($filters['in_stock'])) {
            $query->where('stock', '>', 0);
        }

        if (filled($filters['precio_min'] ?? null)) {
            $query->where('precio', '>=', $filters['precio_min']);
        }

        if (filled($filters['precio_max'] ?? null)) {
            $query->where('precio', '<=', $filters['precio_max']);
        }

        if (! empty($filters['ofertas'])) {
            $query->whereHas('descuentos', fn ($q) => $q->active());
        }

        match ($filters['sort'] ?? 'featured') {
            'price_asc'  => $query->orderBy('precio'),
            'price_desc' => $query->orderByDesc('precio'),
            'name'       => $query->orderBy('nombre'),
            default      => $query->orderByDesc('destacado')->orderBy('nombre'),
        };

        $featuredQuery = Producto::where('destacado', true)->orderBy('nombre')->take(3);

        if (! empty($filters['profile'])) {
            $slug = $filters['profile'];
            $featuredQuery->whereHas('categoria', fn ($q) => $q->where('slug', $slug));
        }

        return view('products.index', [
            'products'         => $query->paginate(9)->withQueryString(),
            'featuredProducts' => $featuredQuery->get(),
            'filters'          => $filters,
        ]);
    }

    public function show(Producto $producto)
    {
        $relatedProducts = Producto::whereKeyNot($producto->id)
            ->where('categoria_id', $producto->categoria_id)
            ->orderByDesc('destacado')
            ->orderBy('nombre')
            ->take(3)
            ->get();

        return view('products.show', [
            'product'         => $producto,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
