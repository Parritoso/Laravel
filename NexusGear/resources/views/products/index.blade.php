@extends('layouts.app')

@section('title', __('products/index.title'))

@section('content')
<section class="catalog-hero mb-5">
    <div class="catalog-hero__content">
        <span class="catalog-kicker">{{ __('products/index.kicker') }}</span>
        <h1 class="display-5 fw-bold mb-3">{{ __('products/index.hero_title') }}</h1>
        <p class="lead mb-4">{{ __('products/index.hero_subtitle') }}</p>
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
                placeholder="{{ __('products/index.search_placeholder') }}"
            >
        </div>

        <div class="dropdown">
            <button class="btn btn-outline-dark dropdown-toggle w-100 text-start" type="button" id="filterCategories" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                <i class="bi bi-filter me-2"></i>
                {{ __('products/index.categories_filter') }}
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
                    <button type="submit" class="btn btn-sm btn-primary w-100">{{ __('products/index.apply') }}</button>
                </li>
            </ul>
        </div>

        <select name="sort" class="form-select">
            <option value="featured" @selected(($filters['sort'] ?? 'featured') === 'featured')>{{ __('products/index.sort_featured') }}</option>
            <option value="price_asc" @selected(($filters['sort'] ?? '') === 'price_asc')>{{ __('products/index.sort_price_asc') }}</option>
            <option value="price_desc" @selected(($filters['sort'] ?? '') === 'price_desc')>{{ __('products/index.sort_price_desc') }}</option>
            <option value="name" @selected(($filters['sort'] ?? '') === 'name')>{{ __('products/index.sort_name') }}</option>
        </select>

        <button class="btn btn-dark" type="submit">{{ __('products/index.filter') }}</button>

        @if (collect($filters)->filter()->isNotEmpty())
            <a href="{{ route('products.index') }}" class="btn btn-link text-decoration-none">{{ __('products/index.clear') }}</a>
        @endif

        {{-- Segunda fila: rango de precio, disponibilidad y ofertas --}}
        <div class="catalog-toolbar__extra">
            <div class="catalog-toolbar__price">
                <span class="text-muted small fw-semibold">{{ __('products/index.price_label') }}</span>
                <input
                    type="number" name="precio_min" step="0.01" min="0"
                    value="{{ $filters['precio_min'] ?? '' }}"
                    class="form-control form-control-sm"
                    placeholder="{{ __('products/index.price_min') }}"
                >
                <span class="text-muted small">—</span>
                <input
                    type="number" name="precio_max" step="0.01" min="0"
                    value="{{ $filters['precio_max'] ?? '' }}"
                    class="form-control form-control-sm"
                    placeholder="{{ __('products/index.price_max') }}"
                >
            </div>

            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="in_stock" name="in_stock" value="1"
                       @checked(! empty($filters['in_stock']))>
                <label class="form-check-label small" for="in_stock">{{ __('products/index.in_stock_only') }}</label>
            </div>

            <div class="form-check form-switch mb-0">
                <input class="form-check-input" type="checkbox" role="switch"
                       id="ofertas" name="ofertas" value="1"
                       @checked(! empty($filters['ofertas']))>
                <label class="form-check-label small" for="ofertas">{{ __('products/index.on_sale_only') }}</label>
            </div>
        </div>
    </form>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">{{ __('products/index.available_title') }}</h2>
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
                        <div class="d-flex justify-content-between align-items-start gap-2 mb-2">
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($product->categorias as $cat)
                                    <span class="badge text-bg-light border" style="font-size: 0.7rem;">
                                        {{ $cat->nombre }}
                                    </span>
                                @endforeach
                            </div>
                            @if ($product->destacado)
                                <span class="badge bg-primary" style="font-size: 0.7rem;">{{ __('products/index.featured_badge') }}</span>
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
                                @if($product->precio_final < $product->precio)
                                    <div class="d-flex flex-column">
                                        <span class="text-muted text-decoration-line-through small" style="font-size: 0.85rem;">
                                            {{ number_format($product->precio, 2, ',', '.') }} €
                                        </span>
                                        <div class="product-price text-danger fw-bold">
                                            {{ number_format($product->precio_final, 2, ',', '.') }} €
                                        </div>
                                    </div>
                                @else
                                    <div class="product-price">
                                        {{ number_format($product->precio, 2, ',', '.') }} €
                                    </div>
                                @endif
                                <small class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                                    {{ $product->disponible ? $product->stock.' '.__('products/index.in_stock') : __('products/index.out_of_stock') }}
                                </small>
                            </div>
                            <div class="d-flex gap-2">
                                <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary">
                                    {{ __('products/index.view_detail') }}
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
                    <h2 class="h4 fw-bold">{{ __('products/index.empty_title') }}</h2>
                    <p class="text-muted mb-3">{{ __('products/index.empty_desc') }}</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('products/index.view_all') }}</a>
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
            <span class="catalog-kicker text-primary">{{ __('products/index.featured_kicker') }}</span>
            <h2 class="h4 fw-bold mb-0">{{ __('products/index.featured_title') }}</h2>
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
                        <span>{{ $featuredProduct->nombre }}</span>
                        <strong>{{ $featuredProduct->precio_formateado }}</strong>
                    </a>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
