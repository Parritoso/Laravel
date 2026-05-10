@extends('layouts.admin')

@section('title', __('admin/dashboard.summary'))
@section('page-title', __('admin/dashboard.dashboard'))

@section('content')
<style>
    .hover-primary:hover {
        color: #0d6efd !important;
        text-decoration: underline !important;
    }
</style>
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/dashboard.products') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $productCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                    <i class="bi bi-exclamation-triangle fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/dashboard.low_stock') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $lowStockCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info me-3">
                    <i class="bi bi-receipt fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/dashboard.orders') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $orderCount }}</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success me-3">
                    <i class="bi bi-hourglass-split fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/dashboard.in_progress') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $pendingOrderCount }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">{{ __('admin/dashboard.recent_orders') }}</h5>
                <a href="{{ route('admin.orders.index') }}" class="text-primary fw-bold text-decoration-none">{{ __('admin/dashboard.view_all') }}</a>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admin/dashboard.order') }}</th>
                            <th>{{ __('admin/dashboard.customer') }}</th>
                            <th>{{ __('admin/dashboard.status') }}</th>
                            <th class="text-end">{{ __('admin/dashboard.total') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentOrders as $order)
                            <tr>
                                <td><a href="{{ route('admin.orders.show', $order) }}" class="fw-bold text-decoration-none">#{{ $order->id }}</a></td>
                                <td>{{ $order->usuario->name }}</td>
                                <td><span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span></td>
                                <td class="text-end fw-bold">{{ $order->factura->total_formateado }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center py-4 text-muted">{{ __('admin/dashboard.no_orders') }}</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">{{ __('admin/dashboard.stock_control') }}</h5>
                <a href="{{ route('admin.products.index') }}" class="text-primary fw-bold text-decoration-none">{{ __('admin/dashboard.manage') }}</a>
            </div>
            <div class="p-3">
                @forelse ($lowStockProducts as $product)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-bold">{{ $product->nombre }}</div>
                            <small class="text-muted">{{ $product->perfil_nombre }}</small>
                        </div>
                        <span class="badge {{ $product->stock <= 5 ? 'text-bg-danger' : 'text-bg-light' }}">{{ __('admin/dashboard.units', ['count' => $product->stock]) }}</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">{{ __('admin/dashboard.no_products') }}</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm h-100">
        <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0">
                <i class="bi bi-heart-fill text-danger me-2"></i>{{ __('admin/dashboard.most_wanted') }}
            </h5>
            <span class="badge rounded-pill bg-light text-dark border">Top 5</span>
        </div>
        <div class="card-body">
        @php
            // Obtenemos el valor máximo de favoritos para calcular el porcentaje relativo
            $maxFavorites = $topFavorites->first()->favoritos_count ?? 1;
        @endphp

        @forelse ($topFavorites as $product)
            @php
                $percentage = ($product->favoritos_count / $maxFavorites) * 100;
            @endphp
            <div class="mb-4">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <div class="me-2 text-truncate">
                        <!-- Enlace en el nombre para acceso rápido -->
                        <a href="{{ route('admin.products.edit', $product) }}" class="fw-bold small text-decoration-none text-dark hover-primary">
                            {{ $product->nombre }}
                        </a>
                        <div class="text-muted" style="font-size: 0.7rem;">{{ $product->perfil_nombre }}</div>
                    </div>
                    
                    <div class="d-flex align-items-center">
                        <span class="badge bg-danger bg-opacity-10 text-danger small me-2">
                            {{ $product->favoritos_count }} <i class="bi bi-heart-fill ms-1" style="font-size: 0.7rem;"></i>
                        </span>
                        <!-- Botón de edición tipo icono -->
                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-light p-1 lh-1 rounded-circle" title="{{ __('admin/dashboard.manage') }}">
                            <i class="bi bi-pencil-square text-primary" style="font-size: 0.85rem;"></i>
                        </a>
                    </div>
                </div>

                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-danger rounded-pill" 
                        role="progressbar" 
                        style="width: {{ $percentage }}%" 
                        aria-valuenow="{{ $percentage }}" 
                        aria-valuemin="0" 
                        aria-valuemax="100">
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-5">
                <i class="bi bi-heartbreak text-muted display-4"></i>
                <p class="text-muted mt-2">{{ __('admin/dashboard.no_favorites_yet') }}</p>
            </div>
        @endforelse
    </div>
</div>
@endsection
