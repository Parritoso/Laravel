@extends('layouts.app')

@section('title', $product->nombre)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Catálogo</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->nombre }}</li>
    </ol>
</nav>

<section class="product-detail mb-5">
    <div class="product-detail__visual">
        <i class="bi {{ $product->icono }}"></i>
    </div>

    <div class="product-detail__info">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge text-bg-light">{{ $product->perfil_nombre }}</span>
            @if ($product->destacado)
                <span class="badge bg-primary">Destacado</span>
            @endif
        </div>

        <h1 class="display-5 fw-bold mb-3">{{ $product->nombre }}</h1>
        <p class="lead text-muted">{{ $product->descripcion }}</p>

        <div class="product-detail__purchase">
            <div>
                <div class="product-detail__price">{{ $product->precio_formateado }}</div>
                <div class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                    {{ $product->disponible ? $product->stock.' unidades disponibles' : 'Producto sin stock' }}
                </div>
            </div>

            <form method="POST" action="{{ route('cart.store', $product) }}" class="add-to-cart-form">
                @csrf
                <label for="cantidad" class="visually-hidden">Cantidad</label>
                <input
                    id="cantidad"
                    type="number"
                    name="cantidad"
                    value="1"
                    min="1"
                    max="{{ max($product->stock, 1) }}"
                    class="form-control form-control-lg"
                    @disabled(! $product->disponible)
                >
                <button class="btn btn-primary btn-lg" type="submit" @disabled(! $product->disponible)>
                    <i class="bi bi-cart-plus me-1"></i> Añadir
                </button>
            </form>
        </div>

        <div class="product-detail__facts">
            <div>
                <span>Uso recomendado</span>
                <strong>{{ $product->perfil_nombre }}</strong>
            </div>
            <div>
                <span>Envío</span>
                <strong>24-48 h</strong>
            </div>
            <div>
                <span>Garantía</span>
                <strong>2 años</strong>
            </div>
        </div>
    </div>
</section>

@if ($relatedProducts->isNotEmpty())
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="catalog-kicker text-primary">También encajan</span>
                <h2 class="h4 fw-bold mb-0">Productos del mismo perfil</h2>
            </div>
            <a href="{{ route('products.index', ['profile' => $product->categoria->slug ?? '']) }}" class="text-primary fw-bold text-decoration-none">
                Ver perfil <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col-md-4">
                    <article class="product-card h-100">
                        <a href="{{ route('products.show', $relatedProduct) }}" class="product-card__media product-card__media--compact">
                            <i class="bi {{ $relatedProduct->icono }}"></i>
                        </a>
                        <div class="product-card__body">
                            <h3 class="h6 fw-bold mb-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="text-reset text-decoration-none">
                                    {{ $relatedProduct->nombre }}
                                </a>
                            </h3>
                            <div class="product-price">{{ $relatedProduct->precio_formateado }}</div>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
