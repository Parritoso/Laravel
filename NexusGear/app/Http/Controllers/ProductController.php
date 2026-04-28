<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:80'],
            'profile' => ['nullable', 'in:gamer,office'],
            'sort' => ['nullable', 'in:featured,price_asc,price_desc,name'],
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
            $query->where('perfil', $filters['profile']);
        }

        match ($filters['sort'] ?? 'featured') {
            'price_asc' => $query->orderBy('precio'),
            'price_desc' => $query->orderByDesc('precio'),
            'name' => $query->orderBy('nombre'),
            default => $query->orderByDesc('destacado')->orderBy('nombre'),
        };

        $featuredProducts = Producto::where('destacado', true)
            ->when(! empty($filters['profile']), fn ($query) => $query->where('perfil', $filters['profile']))
            ->orderBy('nombre')
            ->take(3)
            ->get();

        return view('products.index', [
            'products' => $query->paginate(9)->withQueryString(),
            'featuredProducts' => $featuredProducts,
            'filters' => $filters,
        ]);
    }

    public function show(Producto $producto)
    {
        $relatedProducts = Producto::whereKeyNot($producto->id)
            ->where('perfil', $producto->perfil)
            ->orderByDesc('destacado')
            ->orderBy('nombre')
            ->take(3)
            ->get();

        return view('products.show', [
            'product' => $producto,
            'relatedProducts' => $relatedProducts,
        ]);
    }
}
