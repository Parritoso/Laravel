@extends('layouts.app')

@section('title', 'Carrito')

@section('content')
<section class="cart-header mb-4">
    <div>
        <span class="catalog-kicker text-primary">Carrito</span>
        <h1 class="h2 fw-bold mb-1">Tu selección</h1>
        <p class="text-muted mb-0">{{ $cart->cantidad_total }} producto{{ $cart->cantidad_total === 1 ? '' : 's' }} en el carrito</p>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-arrow-left me-1"></i> Seguir comprando
    </a>
</section>

@if ($cart->items->isEmpty())
    <section class="empty-state">
        <i class="bi bi-cart3"></i>
        <h2 class="h4 fw-bold">El carrito está vacío</h2>
        <p class="text-muted mb-3">Añade algún periférico del catálogo para preparar tu pedido.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Ver catálogo</a>
    </section>
@else
    <section class="cart-layout">
        <div class="cart-items">
            @foreach ($cart->items as $item)
                <article class="cart-item">
                    <a href="{{ route('products.show', $item->producto) }}" class="cart-item__media">
                        <i class="bi {{ $item->producto->icono }}"></i>
                    </a>

                    <div class="cart-item__content">
                        <div>
                            <span class="badge text-bg-light mb-2">{{ $item->producto->perfil_nombre }}</span>
                            <h2 class="h5 fw-bold mb-1">
                                <a href="{{ route('products.show', $item->producto) }}" class="text-reset text-decoration-none">
                                    {{ $item->producto->nombre }}
                                </a>
                            </h2>
                            <p class="text-muted mb-0">{{ $item->precio_actual_formateado }} unidad</p>
                        </div>

                        <div class="cart-item__actions">
                            <form method="POST" action="{{ route('cart.update', $item->producto) }}" class="cart-quantity-form">
                                @csrf
                                @method('PATCH')
                                <label for="cantidad-{{ $item->producto_id }}" class="form-label small text-muted mb-1">Cantidad</label>
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
                                    <button class="btn btn-outline-primary" type="submit">Actualizar</button>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('cart.destroy', $item->producto) }}">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-link text-danger text-decoration-none px-0" type="submit">
                                    <i class="bi bi-trash me-1"></i> Eliminar
                                </button>
                            </form>
                        </div>

                        <div class="cart-item__subtotal">
                            <span>Subtotal</span>
                            <strong>{{ $item->subtotal_formateado }}</strong>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>

        <aside class="cart-summary">
            <h2 class="h5 fw-bold mb-3">Resumen</h2>
            <div class="cart-summary__line">
                <span>Productos</span>
                <strong>{{ $cart->cantidad_total }}</strong>
            </div>
            <div class="cart-summary__line">
                <span>Subtotal</span>
                <strong>{{ $cart->total_formateado }}</strong>
            </div>
            <div class="cart-summary__line text-muted">
                <span>Envío</span>
                <span>Se calcula al tramitar</span>
            </div>
            <hr>
            <div class="cart-summary__total">
                <span>Total estimado</span>
                <strong>{{ $cart->total_formateado }}</strong>
            </div>

            <button class="btn btn-primary btn-lg w-100 mt-3" type="button" disabled>
                Tramitar pedido
            </button>
            <a href="{{ route('products.index') }}" class="btn btn-outline-dark w-100 mt-2">Añadir más productos</a>

            <form method="POST" action="{{ route('cart.clear') }}" class="mt-3">
                @csrf
                @method('DELETE')
                <button class="btn btn-link text-danger text-decoration-none w-100" type="submit">
                    Vaciar carrito
                </button>
            </form>
        </aside>
    </section>
@endif
@endsection
