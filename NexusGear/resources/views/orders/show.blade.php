@extends('layouts.app')

@section('title', 'Pedido #'.$order->id)

@section('content')
<nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ route('orders.index') }}">Mis pedidos</a></li>
        <li class="breadcrumb-item active" aria-current="page">Pedido #{{ $order->id }}</li>
    </ol>
</nav>

<section class="order-detail mb-4">
    <div>
        <span class="catalog-kicker text-primary">Pedido confirmado</span>
        <h1 class="h2 fw-bold mb-2">Pedido #{{ $order->id }}</h1>
        <p class="text-muted mb-0">{{ $order->fecha_formateada }}</p>
    </div>

    <div class="order-status">
        <span class="text-muted small">Estado</span>
        <span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span>
    </div>
</section>

<section class="cart-layout">
    <div class="cart-items">
        @foreach ($order->lineas as $line)
            <article class="cart-item">
                <a href="{{ route('products.show', $line->producto) }}" class="cart-item__media">
                    @if ($line->producto->imagen)
                        <img src="{{ asset('storage/' . $line->producto->imagen) }}" alt="{{ $line->producto->nombre }}" class="w-100 h-100" style="object-fit: cover; border-radius: inherit;">
                    @else
                        <i class="bi {{ $line->producto->icono }}"></i>
                    @endif
                </a>

                <div class="cart-item__content order-line-content">
                    <div>
                        <span class="badge text-bg-light mb-2">{{ $line->producto->perfil_nombre }}</span>
                        <h2 class="h5 fw-bold mb-1">{{ $line->producto->nombre }}</h2>
                        <p class="text-muted mb-0">
                            @if($line->descuento_total > 0)
                                <small class="text-decoration-line-through me-1">{{ $line->precio_original_formateado }}</small>
                            @endif
                            <span class="fw-bold text-dark">{{ $line->precio_unitario_formateado }}</span> 
                            <small>unidad</small>
                        </p>
                    </div>

                    <div>
                        <span class="text-muted small">Cantidad</span>
                        <div class="fw-semibold">{{ $line->cantidad }}</div>
                    </div>

                    <div class="cart-item__subtotal">
                        <span>Subtotal</span>
                        <strong>{{ $line->subtotal_formateado }}</strong>
                        @if($line->descuento_total > 0)
                            <span class="badge text-bg-success-subtle text-success small">
                                Ahorraste {{ $line->descuento_total_formateado }}
                            </span>
                        @endif
                    </div>
                </div>
            </article>
        @endforeach
    </div>

    <aside class="cart-summary">
        <h2 class="h5 fw-bold mb-3">Factura</h2>
        <div class="cart-summary__line">
            <span>Número</span>
            <strong>{{ $order->factura->numero_factura }}</strong>
        </div>
        <div class="cart-summary__line">
            <span>Subtotal</span>
            <strong>{{ $order->factura->subtotal_formateado }}</strong>
        </div>
        <div class="cart-summary__line">
            <span>IVA 21%</span>
            <strong>{{ $order->factura->iva_formateado }}</strong>
        </div>
        @php $totalAhorro = $order->lineas->sum('descuento_total'); @endphp
        
        @if($totalAhorro > 0)
            <div class="cart-summary__line text-success">
                <span>Tu ahorro total</span>
                <strong>- {{ number_format($totalAhorro, 2, ',', '.') }} €</strong>
            </div>
        @endif
        <hr>
        <div class="cart-summary__total">
            <span>Total</span>
            <strong>{{ $order->factura->total_formateado }}</strong>
        </div>

        <a href="{{ route('orders.index') }}" class="btn btn-outline-dark w-100 mt-3">Volver a mis pedidos</a>
    </aside>
</section>
@endsection
