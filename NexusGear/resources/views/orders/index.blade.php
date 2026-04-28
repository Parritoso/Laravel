@extends('layouts.app')

@section('title', 'Mis pedidos')

@section('content')
<section class="cart-header mb-4">
    <div>
        <span class="catalog-kicker text-primary">Pedidos</span>
        <h1 class="h2 fw-bold mb-1">Mis pedidos</h1>
        <p class="text-muted mb-0">Consulta el estado y el detalle de tus compras.</p>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-bag me-1"></i> Ver catálogo
    </a>
</section>

@if ($orders->isEmpty())
    <section class="empty-state">
        <i class="bi bi-receipt"></i>
        <h2 class="h4 fw-bold">Todavía no tienes pedidos</h2>
        <p class="text-muted mb-3">Cuando tramites una compra, podrás revisar aquí su estado.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Explorar productos</a>
    </section>
@else
    <section class="order-list">
        @foreach ($orders as $order)
            <article class="order-row">
                <div>
                    <span class="text-muted small">Pedido</span>
                    <h2 class="h5 fw-bold mb-0">#{{ $order->id }}</h2>
                </div>
                <div>
                    <span class="text-muted small">Factura</span>
                    <div class="fw-semibold">{{ $order->factura->numero_factura }}</div>
                </div>
                <div>
                    <span class="text-muted small">Fecha</span>
                    <div>{{ $order->fecha_formateada }}</div>
                </div>
                <div>
                    <span class="text-muted small">Estado</span>
                    <div>
                        <span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span>
                    </div>
                </div>
                <div class="text-lg-end">
                    <span class="text-muted small">Total</span>
                    <div class="product-price">{{ $order->factura->total_formateado }}</div>
                </div>
                <div class="text-lg-end">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">Ver detalle</a>
                </div>
            </article>
        @endforeach
    </section>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection
