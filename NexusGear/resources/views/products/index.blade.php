@extends('layouts.app')

@section('title', 'Catálogo')

@section('content')
<section class="catalog-hero mb-5">
    <div class="catalog-hero__content">
        <span class="catalog-kicker">Catálogo NexusGear</span>
        <h1 class="display-5 fw-bold mb-3">Periféricos ergonómicos para trabajar y jugar mejor.</h1>
        <p class="lead mb-4">Explora productos pensados para mantener el rendimiento sin renunciar a una postura cómoda.</p>
        <!--<div class="d-flex flex-wrap gap-2">
            <a href="{{ route('products.index', ['profile' => 'office']) }}" class="btn btn-light btn-lg">Office & Focus</a>
            <a href="{{ route('products.index', ['profile' => 'gamer']) }}" class="btn btn-outline-light btn-lg">Gamer Pro</a>
        </div>-->
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

        {{-- <select name="profile" class="form-select">
            <option value="">Todos los perfiles</option>
            @foreach ($categories as $category)
                <option 
                    value="{{ $category->slug }}" 
                    @selected(($filters['profile'] ?? '') === $category->slug)
                >
                    {{ $category->nombre }}
                </option>
            @endforeach
        </select> --}}
        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle w-100 text-start" type="button" id="filterCategories" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <i class="bi bi-filter me-2"></i> 
                Categorías 
                @if(!empty($filters['profiles'])) 
                    <span class="badge bg-dark ms-1">{{ count($filters['profiles']) }}</span>
                @endif
            </button>
            <ul class="dropdown-menu w-100 shadow-sm" aria-labelledby="filterCategories">
                @foreach ($categories as $category)
                    <li class="px-3 py-1">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" 
                                name="profiles[]" 
                                value="{{ $category->slug }}" 
                                id="filter_{{ $category->slug }}"
                                @checked(in_array($category->slug, (array)($filters['profiles'] ?? [])))>
                            <label class="form-check-label small" for="filter_{{ $category->slug }}">
                                {{ $category->nombre }}
                            </label>
                        </div>
                    </li>
                @endforeach
                <li><hr class="dropdown-divider"></li>
                <li class="px-3 py-1">
                    <button type="submit" class="btn btn-sm btn-primary w-100">Aplicar</button>
                </li>
            </ul>
        </div>

        <select name="sort" class="form-select">
            <option value="featured" @selected(($filters['sort'] ?? 'featured') === 'featured')>Destacados</option>
            <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>Precio: menor a mayor</option>
            <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>Precio: mayor a menor</option>
            <option value="name" @selected(($filters['sort'] ?? '') === 'name')>Nombre</option>
        </select>

        <button class="btn btn-dark" type="submit">Filtrar</button>

        @if (collect($filters)->filter()->isNotEmpty())
            <a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">Limpiar</a>
        @endif

        {{-- Segunda fila: rango de precio, disponibilidad y ofertas --}}
        <div class="catalog-toolbar__extra">
            <div class="catalog-toolbar__price">
                <span class="text-muted small fw-semibold">Precio:</span>
                <input
                    type="number" name="precio_min" step="0.01" min="0"
                    value="{{ $filters['precio_min'] ?? '' }}"
                    class="form-control form-control-sm"
                    placeholder="Mín €"
                >
                <span class="text-muted small">—</span>
                <input
                    type="number" name="precio_max" step="0.01" min="0"
                    value="{{ $filters['precio_max'] ?? '' }}"
                    class="form-control form-control-sm"
                    placeholder="Máx €"
                >
            </div>

            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="in_stock" name="in_stock" value="1"
                       @checked(! empty($filters['in_stock']))>
                <label class="form-check-label small" for="in_stock">Solo disponibles</label>
            </div>

            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="ofertas" name="ofertas" value="1"
                       @checked(! empty($filters['ofertas']))>
                <label class="form-check-label small" for="ofertas">Solo ofertas</label>
            </div>
        </div>
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
                        @if (!is_null($product->imagen))
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}">
                        @else
                            <i class="bi {{ $product->icono }}"></i>
                        @endif
                    </a>

                    <div class="product-card__body">
                        {{-- <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            {{ <span class="badge text-bg-light">{{ $product->perfil_nombre }}</span> }}
                            @foreach($product->categorias as $cat)
                                <span class="badge text-bg-light border" style="font-size: 0.7rem;">{{ $cat->nombre }}</span>
                            @endforeach
                            @if ($product->destacado)
                                <span class="badge bg-primary">Destacado</span>
                            @endif
                        </div> --}}
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            {{-- Contenedor para las categorías (agrupadas a la izquierda) --}}
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($product->categorias as $cat)
                                    <span class="badge text-bg-light border" style="font-size: 0.7rem;">
                                        {{ $cat->nombre }}
                                    </span>
                                @endforeach
                            </div>

                            {{-- Badge de destacado (sola a la derecha) --}}
                            @if ($product->destacado)
                                <span class="badge bg-primary" style="font-size: 0.7rem;">Destacado</span>
                            @endif
                        </div>

                        <h3 class="h5 fw-bold mb-2">
                            <a href="{{ route('products.show', $product) }}" class="text-reset text-decoration-none">
                                {{ $product->nombre }}
                            </a>
                        </h3>

                        <p class="product-card__description">{{ $product->descripcion }}</p>

                        <div class="d-flex justify-content-between align-items-center gap-3 mt-4">
                            <div>
                                <div class="product-price">{{ $product->precio_formateado }}</div>
                                <small class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                                    {{ $product->disponible ? $product->stock.' en stock' : 'Sin stock' }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                    Ver detalle
                                </a>
                                <form method="POST" action="{{ route('cart.store', $product) }}">
                                    @csrf
                                    <input type="hidden" name="cantidad" value="1">
                                    <button class="btn btn-primary" type="submit" @disabled(! $product->disponible) aria-label="Añadir {{ $product->nombre }} al carrito">
                                        <i class="bi bi-cart-plus"></i>
                                    </button>
                                </form>
                            </div>
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
                        @if (!is_null($featuredProduct->imagen))
                            <img src="{{ asset('storage/' . $featuredProduct->imagen) }}" alt="{{ $featuredProduct->nombre }}">
                        @else
                            <i class="bi {{ $featuredProduct->icono }}"></i>
                        @endif
                        <!---->
                        <span>{{ $featuredProduct->nombre }}</span>
                        <strong>{{ $featuredProduct->precio_formateado }}</strong>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
