@extends('layouts.app')

@section('title', 'Catálogo')

@section('content')
<section class="catalog-hero mb-5">
    <div class="catalog-hero__content">
        <span class="catalog-kicker">Catálogo NexusGear</span>
        <h1 class="display-5 fw-bold mb-3">Periféricos ergonómicos para trabajar y jugar mejor.</h1>
        <p class="lead mb-4">Explora productos pensados para mantener el rendimiento sin renunciar a una postura cómoda.</p>
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('products.index', ['profile' => 'office']) }}" class="btn btn-light btn-lg">Office & Focus</a>
            <a href="{{ route('products.index', ['profile' => 'gamer']) }}" class="btn btn-outline-light btn-lg">Gamer Pro</a>
        </div>
    </div>
</section>

<section class="mb-4">
    <form method="GET" action="{{ route('products.index') }}" class="catalog-toolbar">
        <div class="catalog-toolbar__search">
            <i class="bi bi-search"></i>
            <input
                type="search"
                name="q"
                value="{{ $filters['q'] ?? '' }}"
                class="form-control"
                placeholder="Buscar por nombre o descripción"
            >
        </div>

        <select name="profile" class="form-select">
            <option value="">Todos los perfiles</option>
            <option value="office" @selected(($filters['profile'] ?? '') === 'office')>Office & Focus</option>
            <option value="gamer" @selected(($filters['profile'] ?? '') === 'gamer')>Gamer Pro</option>
        </select>

        <select name="sort" class="form-select">
            <option value="featured" @selected(($filters['sort'] ?? 'featured') === 'featured')>Destacados</option>
            <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Precio menor</option>
            <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Precio mayor</option>
            <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Nombre</option>
        </select>

        <button class="btn btn-dark" type="submit">Filtrar</button>

        @if (! empty($filters))
            <a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Limpiar</a>
        @endif
    </form>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">Productos disponibles</h2>
            <p class="text-muted mb-0">{{ $products->total() }} resultado{{ $products->total() === 1 ? '' : 's' }}</p>
        </div>
    </div>

    <div class="row g-4">
        @forelse ($products as $product)
            <div class="col-md-6 col-xl-4">
                <article class="product-card h-100">
                    <a href="{{ route('products.show', $product) }}" class="product-card__media">
                        <i class="bi {{ $product->icono }}"></i>
                    </a>

                    <div class="product-card__body">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <span class="badge text-bg-light">{{ $product->perfil_nombre }}</span>
                            @if ($product->destacado)
                                <span class="badge bg-primary">Destacado</span>
                            @endif
                        </div>

                        <h3 class="h5 fw-bold mb-2">
                            <a href="{{ route('products.show', $product) }}" class="text-reset text-decoration-none">
                                {{ $product->nombre }}
                            </a>
                        </h3>

                        <p class="product-card__description">{{ $product->descripcion }}</p>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <div class="product-price">{{ $product->precio_formateado }}</div>
                                <small class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                                    {{ $product->disponible ? $product->stock.' en stock' : 'Sin stock' }}
                                </small>
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                Ver detalle
                            </a>
                        </div>
                    </div>
                </article>
            </div>
        @empty
            <div class="col-12">
                <div class="empty-state">
                    <i class="bi bi-search"></i>
                    <h2 class="h4 fw-bold">No hay productos con esos filtros</h2>
                    <p class="text-muted mb-3">Prueba con otro perfil o elimina la búsqueda.</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">Ver todo el catálogo</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $products->links() }}
    </div>
</section>

@if ($featuredProducts->isNotEmpty())
    <section class="featured-strip">
        <div>
            <span class="catalog-kicker text-primary">Selección destacada</span>
            <h2 class="h4 fw-bold mb-0">Los básicos para empezar un setup ergonómico</h2>
        </div>

        <div class="row g-3 mt-3">
            @foreach ($featuredProducts as $featuredProduct)
                <div class="col-md-4">
                    <a href="{{ route('products.show', $featuredProduct) }}" class="featured-link">
                        <i class="bi {{ $featuredProduct->icono }}"></i>
                        <span>{{ $featuredProduct->nombre }}</span>
                        <strong>{{ $featuredProduct->precio_formateado }}</strong>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
