<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Categoria;
use App\Services\MongoLog\UserAnalyticsService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // Los filtros se validan antes de construir la consulta para evitar parámetros inesperados
        // en la paginación y mantener URLs compartibles del catálogo.
        $filters = $request->validate([
            'q'          => ['nullable', 'string', 'max:80'],
            'profile'    => ['nullable', 'string', 'exists:categorias,slug'],
            'profiles'   => ['nullable', 'array'],
            'profiles.*' => ['nullable', 'string', 'exists:categorias,slug'],
            'sort'       => ['nullable', 'in:featured,price_asc,price_desc,name'],
            'precio_min' => ['nullable', 'numeric', 'min:0'],
            'precio_max' => ['nullable', 'numeric', 'min:0', 'gte:precio_min'],
            'in_stock'   => ['nullable', 'boolean'],
            'ofertas'    => ['nullable', 'boolean'],
        ]);

        $categories = Categoria::orderBy('nombre')->get();

        $query = Producto::query();

        // Búsqueda sencilla sobre nombre y descripción. No usa texto completo porque el catálogo es pequeño.
        if (! empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', "%{$search}%")
                    ->orWhere('descripcion', 'like', "%{$search}%");
            });
        }

        // "profile" se mantiene para enlaces antiguos de una sola categoría.
        if (! empty($filters['profile'])) {
            $slug = $filters['profile'];
            $query->whereHas('categorias', fn ($q) => $q->where('slug', $slug));
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

        // "profiles" permite filtrar por varias categorías desde el formulario del catálogo.
        if (! empty($filters['profiles'])) {
            $query->whereHas('categorias', function ($q) use ($filters) {
                $q->whereIn('slug', $filters['profiles']);
            });
        }

        match ($filters['sort'] ?? 'featured') {
            'price_asc'  => $query->orderBy('precio'),
            'price_desc' => $query->orderByDesc('precio'),
            'name'       => $query->orderBy('nombre'),
            default      => $query->orderByDesc('destacado')->orderBy('nombre'),
        };

        // Los destacados se muestran aparte para dar visibilidad a productos recomendados,
        // respetando el filtro de categoría si el usuario entra desde una familia concreta.
        $featuredQuery = Producto::where('destacado', true)->orderBy('nombre')->take(3);

        if (! empty($filters['profile'])) {
            $slug = $filters['profile'];
            $featuredQuery->whereHas('categorias', fn ($q) => $q->where('slug', $slug));
        }

        $products = $query->paginate(9)->withQueryString();

        UserAnalyticsService::logSearch($filters, $products->total());

        return view('products.index', [
            'products'           => $query->paginate(9)->withQueryString(),
            'featuredProducts'   => $featuredQuery->get(),
            'filters'            => $filters,
            'categories'         => Categoria::orderBy('nombre')->get(),
            'favoriteProductIds' => $this->favoriteProductIds(),
        ]);
    }

    public function show(Producto $producto)
    {
        // Productos relacionados: misma categoría, sin repetir el producto actual.
        $categoryIds = $producto->categorias->pluck('id');
        $relatedProducts = Producto::whereKeyNot($producto->id)
            ->whereHas('categorias', function ($query) use ($categoryIds) {
                $query->whereIn('categorias.id', $categoryIds);
            })
            ->orderByDesc('destacado')
            ->orderBy('nombre')
            ->take(3)
            ->get();

        // Cada usuario puede editar su propia valoración desde la ficha del producto.
        $comentarios = $producto->comentarios()->with('user')->latest()->paginate(5);
        $userReview = Auth::check() 
            ? $producto->comentarios()->where('user_id', Auth::id())->first() 
            : null;

        return view('products.show', [
            'product'            => $producto,
            'relatedProducts'    => $relatedProducts,
            'favoriteProductIds' => $this->favoriteProductIds(),
            'comentarios'        => $comentarios,
            'userReview'         => $userReview,
        ]);
    }

    private function favoriteProductIds(): array
    {
        if (! Auth::check()) {
            return [];
        }

        return Auth::user()
            ->favoritos()
            ->pluck('producto_id')
            ->all();
    }
}
