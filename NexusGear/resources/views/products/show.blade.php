@extends('layouts.app')

@section('title', $product->nombre)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('products.index') }}">{{ __('products/show.breadcrumb') }}</a></li>
        <li class="breadcrumb-item active" aria-current="page">{{ $product->nombre }}</li>
    </ol>
</nav>

<section class="product-detail mb-5">
    <div class="product-detail__visual">
        @if ($product->imagen)
            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="img-fluid w-100 rounded">
        @else
            <i class="bi {{ $product->icono }}"></i>
        @endif
    </div>

    <div class="product-detail__info">
        <div class="d-flex flex-wrap gap-2 mb-3">
            <span class="badge text-bg-light">{{ $product->perfil_nombre }}</span>
            @if ($product->destacado)
                <span class="badge bg-primary">{{ __('products/show.badge_featured') }}</span>
            @endif
            @if($product->precio_final < $product->precio)
                <span class="badge bg-danger animate__animated animate__flash animate__infinite">
                    <i class="bi bi-patch-check-fill me-1"></i> {{ __('products/show.badge_offer') }}
                </span>
            @endif
        </div>

        <h1 class="display-5 fw-bold mb-3">{{ $product->nombre }}</h1>
        <p class="lead text-muted">{{ $product->descripcion }}</p>

        <div class="product-detail__purchase">
            <div>
                @if($product->precio_final < $product->precio)
                    <div class="d-flex align-items-baseline gap-2">
                        <div class="product-detail__price text-danger">
                            {{ number_format($product->precio_final, 2, ',', '.') }} €
                        </div>
                        <span class="text-muted text-decoration-line-through fs-5">
                            {{ $product->precio_formateado }}
                        </span>
                    </div>
                @else
                    <div class="product-detail__price">{{ $product->precio_formateado }}</div>
                @endif
                <div class="{{ $product->disponible ? 'text-success' : 'text-danger' }}">
                    {{ $product->disponible ? __('products/show.units_available', ['stock' => $product->stock]) : __('products/show.out_of_stock') }}
                </div>
            </div>

            <form method="POST" action="{{ route('cart.store', $product) }}" class="add-to-cart-form">
                @csrf
                <label for="cantidad" class="visually-hidden">{{ __('products/show.qty_label') }}</label>
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
                    <i class="bi bi-cart-plus me-1"></i> {{ __('products/show.add_to_cart') }}
                </button>
            </form>
        </div>

        <div class="product-detail__facts">
            <div>
                <span>{{ __('products/show.use_label') }}</span>
                <strong>{{ $product->perfil_nombre }}</strong>
            </div>
            <div>
                <span>{{ __('products/show.shipping_label') }}</span>
                <strong>{{ __('products/show.shipping_time') }}</strong>
            </div>
            <div>
                <span>{{ __('products/show.warranty_label') }}</span>
                <strong>{{ __('products/show.warranty_time') }}</strong>
            </div>
        </div>
    </div>
</section>

@if ($relatedProducts->isNotEmpty())
    <section class="mb-5">
        <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
            <div>
                <span class="catalog-kicker text-primary">{{ __('products/show.related_kicker') }}</span>
                <h2 class="h4 fw-bold mb-0">{{ __('products/show.related_title') }}</h2>
            </div>
            <a href="{{ route('products.index', ['profile' => $product->categoria->slug ?? '']) }}" class="text-primary fw-bold text-decoration-none">
                {{ __('products/show.view_profile') }} <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @foreach ($relatedProducts as $relatedProduct)
                <div class="col-md-4">
                    <article class="product-card h-100">
                        <a href="{{ route('products.show', $relatedProduct) }}" class="product-card__media product-card__media--compact">
                            @if($relatedProduct->precio_final < $relatedProduct->precio)
                                <span class="badge bg-danger position-absolute top-0 start-0 m-2">{{ __('products/show.badge_offer') }}</span>
                            @endif
                            @if ($relatedProduct->imagen)
                                <img src="{{ asset('storage/' . $relatedProduct->imagen) }}" alt="{{ $relatedProduct->nombre }}">
                            @else
                                <i class="bi {{ $relatedProduct->icono }}"></i>
                            @endif
                        </a>
                        <div class="product-card__body">
                            <h3 class="h6 fw-bold mb-2">
                                <a href="{{ route('products.show', $relatedProduct) }}" class="text-reset text-decoration-none">
                                    {{ $relatedProduct->nombre }}
                                </a>
                            </h3>
                            @if($relatedProduct->precio_final < $relatedProduct->precio)
                                <div class="d-flex align-items-center gap-2">
                                    <span class="fw-bold text-danger">{{ number_format($relatedProduct->precio_final, 2, ',', '.') }} €</span>
                                    <span class="text-muted text-decoration-line-through x-small" style="font-size: 0.75rem;">{{ $relatedProduct->precio_formateado }}</span>
                                </div>
                            @else
                                <div class="product-price">{{ $relatedProduct->precio_formateado }}</div>
                            @endif
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
@endif
@endsection
