@extends('layouts.app')

@section('title', __('orders/index.title'))

@section('content')
<section class="cart-header mb-4">
    <div>
        <span class="catalog-kicker text-primary">{{ __('orders/index.kicker') }}</span>
        <h1 class="h2 fw-bold mb-1">{{ __('orders/index.heading') }}</h1>
        <p class="text-muted mb-0">{{ __('orders/index.subtitle') }}</p>
    </div>

    <a href="{{ route('products.index') }}" class="btn btn-outline-dark">
        <i class="bi bi-bag me-1"></i> {{ __('orders/index.view_catalog') }}
    </a>
</section>

@if ($orders->isEmpty())
    <section class="empty-state">
        <i class="bi bi-receipt"></i>
        <h2 class="h4 fw-bold">{{ __('orders/index.empty_title') }}</h2>
        <p class="text-muted mb-3">{{ __('orders/index.empty_desc') }}</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">{{ __('orders/index.explore') }}</a>
    </section>
@else
    <section class="order-list">
        @foreach ($orders as $order)
            <article class="order-row">
                <div>
                    <span class="text-muted small">{{ __('orders/index.col_order') }}</span>
                    <h2 class="h5 fw-bold mb-0">#{{ $order->id }}</h2>
                </div>
                <div>
                    <span class="text-muted small">{{ __('orders/index.col_invoice') }}</span>
                    <div class="fw-semibold">{{ $order->factura->numero_factura }}</div>
                </div>
                <div>
                    <span class="text-muted small">{{ __('orders/index.col_date') }}</span>
                    <div>{{ $order->fecha_formateada }}</div>
                </div>
                <div>
                    <span class="text-muted small">{{ __('orders/index.col_status') }}</span>
                    <div>
                        <span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span>
                    </div>
                </div>
                <div class="text-lg-end">
                    <span class="text-muted small">{{ __('orders/index.col_total') }}</span>
                    <div class="product-price">{{ $order->factura->total_formateado }}</div>
                </div>
                <div class="text-lg-end">
                    <a href="{{ route('orders.show', $order) }}" class="btn btn-outline-primary">{{ __('orders/index.view_detail') }}</a>
                </div>
            </article>
        @endforeach
    </section>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endif
@endsection
