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
                <article class="product-card h-100 border shadow-sm rounded position-relative d-flex flex-column bg-white">
                    
                    <a href="{{ route('products.show', $product) }}" class="product-card__media d-block overflow-hidden bg-light text-center py-4 text-secondary">
                        @if (!is_null($product->imagen))
                            <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="img-fluid" style="height: 200px; object-fit: contain;">
                        @else
                            <i class="bi {{ $product->icono }} display-4"></i>
                        @endif
                    </a>

                    <div class="product-card__body p-3 flex-grow-1 d-flex flex-column justify-content-between">
                        <div>
                            <h3 class="h6 fw-bold mb-1">
                                <a href="{{ route('products.show', $product) }}" class="text-reset text-decoration-none">
                                    {{ $product->nombre }}
                                </a>
                            </h3>
                            <p class="text-muted small text-truncate mb-2">{{ $product->descripcion }}</p>
                        </div>

                        <div class="alert-config-zone border-top pt-2 mt-2 bg-light p-2 rounded">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small fw-semibold text-dark"><i class="bi bi-bell me-1 text-primary"></i> {{ __('favorites/index.active_alerts') }}</span>
                                <button class="btn btn-sm btn-link text-decoration-none p-0 x-small text-secondary" 
                                        type="button" 
                                        data-bs-toggle="collapse" 
                                        data-bs-target="#alert-form-{{ $product->id }}" 
                                        aria-expanded="false">
                                    <i class="bi bi-gear-fill"></i> {{ __('favorites/index.config') }}
                                </button>
                            </div>

                            <div class="d-flex gap-2 mb-1">
                                <span class="badge {{ $favorite->alerta_precio ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary' }} rounded-pill" style="font-size: 0.7rem;">
                                    <i class="bi bi-tag-fill"></i> {{ __('favorites/index.prices') }}
                                </span>
                                <span class="badge {{ $favorite->alerta_stock_bajo ? 'bg-warning-subtle text-warning-emphasis' : 'bg-secondary-subtle text-secondary' }} rounded-pill" style="font-size: 0.7rem;">
                                    <i class="bi bi-box-seam-fill"></i> {{ __('favorites/index.stock_alert', ['threshold' => $favorite->umbral_stock]) }}
                                </span>
                            </div>

                            <div class="collapse mt-2" id="alert-form-{{ $product->id }}">
                                <form action="{{ route('favorites.updateSettings', $product) }}" method="POST" class="m-0 bg-white p-3 border rounded shadow-sm">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="alerta_precio" id="price-{{ $product->id }}" value="1" {{ $favorite->alerta_precio ? 'checked' : '' }}>
                                        <label class="form-check-label small text-secondary" for="price-{{ $product->id }}">{{ __('favorites/index.alerts.price_label') }}</label>
                                    </div>

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="alerta_stock_bajo" id="low-{{ $product->id }}" value="1" {{ $favorite->alerta_stock_bajo ? 'checked' : '' }}>
                                        <label class="form-check-label small text-secondary" for="low-{{ $product->id }}">{{ __('favorites/index.alerts.low_stock_label') }}</label>
                                    </div>

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="alerta_stock_agotado" id="out-{{ $product->id }}" value="1" {{ $favorite->alerta_stock_agotado ? 'checked' : '' }}>
                                        <label class="form-check-label small text-secondary" for="out-{{ $product->id }}">{{ __('favorites/index.alerts.out_of_stock_label') }}</label>
                                    </div>

                                    <div class="form-check form-switch mb-2">
                                        <input class="form-check-input" type="checkbox" name="alerta_stock_disponible" id="back-{{ $product->id }}" value="1" {{ $favorite->alerta_stock_disponible ? 'checked' : '' }}>
                                        <label class="form-check-label small text-secondary" for="back-{{ $product->id }}">{{ __('favorites/index.alerts.in_stock_label') }}</label>
                                    </div>

                                    <div class="mb-2 mt-2 pt-2 border-top">
                                        <label for="umbral-{{ $product->id }}" class="form-label text-muted x-small mb-1 d-block" style="font-size: 0.75rem;">{{ __('favorites/index.alerts.define_threshold') }}</label>
                                        <div class="input-group input-group-sm" style="max-width: 120px;">
                                            <input type="number" name="umbral_stock" id="umbral-{{ $product->id }}" class="form-control form-control-sm" value="{{ $favorite->umbral_stock }}" min="1" max="50">
                                            <span class="input-group-text text-muted" style="font-size: 0.7rem;">{{ __('favorites/index.alerts.units') }}</span>
                                        </div>
                                    </div>

                                    <button type="submit" class="btn btn-primary btn-sm w-100 fw-bold py-1 mt-2" style="font-size: 0.75rem;">{{ __('favorites/index.alerts.save_alerts') }}</button>
                                </form>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center gap-2 mt-3 pt-2 border-top">
                            <div>
                                <span class="fw-bold text-dark d-block">{{ number_format($product->precio, 2, ',', '.') }} €</span>
                                <small class="x-small {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                    {{ $product->stock > 0 ? $product->stock.' disponibles' : 'Agotado' }}
                                </small>
                            </div>

                            <div class="d-flex gap-1">
                                <form method="POST" action="{{ route('favorites.destroy', $product) }}" class="m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-outline-danger btn-sm" type="submit" title="Quitar de seguimiento">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('cart.store', $product) }}" class="m-0">
                                    @csrf
                                    <input type="hidden" name="cantidad" value="1">
                                    <button class="btn btn-primary btn-sm" type="submit" @disabled($product->stock <= 0)>
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
