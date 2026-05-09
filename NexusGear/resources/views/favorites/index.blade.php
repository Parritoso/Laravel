@extends('layouts.app')

@section('title', __('favorites/index.title'))

@section('content')
<section class="catalog-hero mb-5">
    <div class="catalog-hero__content">
        <span class="catalog-kicker">{{ __('favorites/index.kicker') }}</span>
        <h1 class="display-5 fw-bold mb-3">{{ __('favorites/index.hero_title') }}</h1>
        <p class="lead mb-4">{{ __('favorites/index.hero_subtitle') }}</p>
    </div>
</section>

<section class="mb-5">
    <div class="d-flex justify-content-between align-items-end gap-3 mb-3">
        <div>
            <h2 class="h4 fw-bold mb-1">{{ __('favorites/index.list_title') }}</h2>
            <p class="text-muted mb-0">{{ trans_choice('favorites/index.count', $favorites->total(), ['count' => $favorites->total()]) }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-grid me-1"></i> {{ __('favorites/index.browse_catalog') }}
        </a>
    </div>

    <div class="row g-4">
        @forelse ($favorites as $favorite)
            @php($product = $favorite->producto)
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
                                <span class="badge bg-primary" style="font-size: 0.7rem;">{{ __('favorites/index.featured_badge') }}</span>
                            @endif
                        </div>

                        <h3 class="h5 fw-bold mb-2">
                            <a href="{{ route('products.show', $product) }}" class="text-reset text-decoration-none">
                                {{ $product->nombre }}
                            </a>
                        </h3>

                        <p class="product-card__description">{{ $product->descripcion }}</p>

                        <small class="text-muted mt-3">
                            <i class="bi bi-heart-fill text-danger me-1"></i>
                            {{ __('favorites/index.added_on', ['date' => $favorite->agregado_el->format('d/m/Y')]) }}
                        </small>

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
                                    {{ $product->disponible ? $product->stock.' '.__('favorites/index.in_stock') : __('favorites/index.out_of_stock') }}
                                </small>
                            </div>

                            <div class="d-flex gap-2">
                                <form method="POST" action="{{ route('favorites.destroy', $product) }}" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger favorite-action" type="submit" title="{{ __('favorites/index.remove') }}" aria-label="{{ __('favorites/index.remove') }}">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cart.store', $product) }}">
                                    @csrf
                                    <input type="hidden" name="cantidad" value="1">
                                    <button class="btn btn-primary" type="submit" @disabled(! $product->disponible) aria-label="{{ __('favorites/index.add_to_cart') }}">
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
                    <i class="bi bi-heart"></i>
                    <h2 class="h4 fw-bold">{{ __('favorites/index.empty_title') }}</h2>
                    <p class="text-muted mb-3">{{ __('favorites/index.empty_desc') }}</p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('favorites/index.browse_catalog') }}</a>
                </div>
            </div>
        @endforelse
    </div>

    <div class="mt-4">
        {{ $favorites->links() }}
    </div>
</section>
@endsection
