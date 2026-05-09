@extends('layouts.app')

@section('title', __('cart/index.title'))

@section('content')
<section class="cart-header mb-4">
    <div>
        <span class="catalog-kicker text-primary">{{ __('cart/index.title') }}</span>
        <h1 class="h2 fw-bold mb-1">{{ __('cart/index.heading') }}</h1>
        <p class="text-muted mb-0">{{ $cart->cantidad_total }} producto{{ $cart->cantidad_total === 1 ? '' : 's' }} en el carrito</p>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-arrow-left me-1"></i> {{ __('cart/index.continue_shopping') }}
    </a>
</section>

@if ($cart->items->isEmpty())
    <section class="empty-state">
        <i class="bi bi-cart3"></i>
        <h2 class="h4 fw-bold">{{ __('cart/index.empty_title') }}</h2>
        <p class="text-muted mb-3">{{ __('cart/index.empty_desc') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('cart/index.view_catalog') }}</a>
    </section>
@else
    <section class="cart-layout">
        <div class="cart-items">
            @foreach ($cart->items as $item)
                @php
                    $producto = $item->producto;
                    $tieneDescuento = $producto->precio_final < $producto->precio;
                    $ahorroUnitario = $producto->precio - $producto->precio_final;
                @endphp
                <article class="cart-item">
                    <a href="{{ route('products.show', $item->producto) }}" class="cart-item__media">
                        @if ($item->producto->imagen)
                        <img src="{{ asset('storage/' . $item->producto->imagen) }}" alt="{{ $item->producto->nombre }}" class="w-100 h-100" style="object-fit: cover; border-radius: inherit;">
                    @else
                        <i class="bi {{ $item->producto->icono }}"></i>
                    @endif
                    </a>

                    <div class="cart-item__content">
                        <div>
                            <span class="badge text-bg-light mb-2">{{ $item->producto->perfil_nombre }}</span>
                            <h2 class="h5 fw-bold mb-1">
                                <a href="{{ route('products.show', $item->producto) }}" class="text-reset text-decoration-none">
                                    {{ $item->producto->nombre }}
                                </a>
                            </h2>
                            <p class="mb-0">
                                @if($tieneDescuento)
                                    <span class="text-muted text-decoration-line-through small me-1">
                                        {{ number_format($producto->precio, 2, ',', '.') }} €
                                    </span>
                                    <span class="fw-bold text-success">
                                        {{ number_format($producto->precio_final, 2, ',', '.') }} €
                                    </span>
                                @else
                                    <span class="text-muted">{{ $item->precio_actual_formateado }}</span>
                                @endif
                                <small class="text-muted">{{ __('cart/index.per_unit') }}</small>
                            </p>
                        </div>

                        <div class="cart-item__actions">
                            <form method="POST" action="{{ route('cart.update', $item->producto) }}" class="cart-quantity-form">
                                @csrf
                                @method('PATCH')
                                <label for="cantidad-{{ $item->producto_id }}" class="form-label small text-muted mb-1">{{ __('cart/index.quantity') }}</label>
                                <div class="input-group">
                                    <input
                                        id="cantidad-{{ $item->producto_id }}"
                                        type="number"
                                        name="cantidad"
                                        value="{{ $item->cantidad }}"
                                        min="1"
                                        max="{{ max($item->producto->stock, 1) }}"
                                        class="form-control"
                                    >
                                    <button class="btn btn-outline-primary" type="submit">{{ __('cart/index.update') }}</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('cart.destroy', $item->producto) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link text-danger text-decoration-none px-0" type="submit">
                                    <i class="bi bi-trash me-1"></i> {{ __('cart/index.remove') }}
                                </button>
                            </form>
                        </div>

                        <div class="cart-item__subtotal">
                            <span>{{ __('cart/index.subtotal') }}</span>
                            <strong>{{ $item->subtotal_formateado }}</strong>
                            @if($tieneDescuento)
                                <small class="text-success fw-medium">
                                    {{ __('cart/index.savings', ['amount' => number_format($ahorroUnitario * $item->cantidad, 2, ',', '.')]) }}
                                </small>
                            @endif
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <aside class="cart-summary">
            <h2 class="h5 fw-bold mb-3">{{ __('cart/index.summary') }}</h2>
            <div class="cart-summary__line">
                <span>{{ __('cart/index.items_count') }}</span>
                <strong>{{ $cart->cantidad_total }}</strong>
            </div>
            <div class="cart-summary__line">
                <span>{{ __('cart/index.subtotal') }}</span>
                <strong>{{ $cart->total_formateado }}</strong>
            </div>
            @php
                $ahorroTotal = $cart->items->reduce(function($carry, $item) {
                    $ahorro = $item->producto->precio - $item->producto->precio_final;
                    return $carry + ($ahorro * $item->cantidad);
                }, 0);
            @endphp

            @if($ahorroTotal > 0)
                <div class="cart-summary__line text-success">
                    <span>{{ __('cart/index.discounts') }}</span>
                    <strong>- {{ number_format($ahorroTotal, 2, ',', '.') }} €</strong>
                </div>
            @endif
            <div class="cart-summary__line text-muted">
                <span>{{ __('cart/index.shipping') }}</span>
                <span>{{ __('cart/index.shipping_calc') }}</span>
            </div>
            <hr>
            <div class="cart-summary__total">
                <span>{{ __('cart/index.estimated_total') }}</span>
                <strong>{{ $cart->total_formateado }}</strong>
            </div>

            @auth
                <form method="GET" action="{{ route('checkout.index') }}">
                    @csrf
                    <button class="btn btn-primary btn-lg w-100 mt-3" type="submit">
                        {{ __('cart/index.checkout') }}
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="btn btn-primary btn-lg w-100 mt-3">
                    <i class="bi bi-box-arrow-in-right me-1"></i> {{ __('cart/index.login_to_checkout') }}
                </a>
                <p class="text-muted small text-center mt-2 mb-0">{{ __('cart/index.cart_persist') }}</p>
            @endauth
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100 mt-2">{{ __('cart/index.add_more') }}</a>

            <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                @csrf
                @method('DELETE')
                <button class="btn btn-link text-danger text-decoration-none w-100" type="submit">
                    {{ __('cart/index.clear') }}
                </button>
            </form>
        </aside>
    </section>
@endif
@endsection
