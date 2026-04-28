@extends('layouts.admin')

@section('title', 'Resumen')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Productos</h6>
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
                    <h6 class="text-muted mb-0">Stock bajo</h6>
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
                    <h6 class="text-muted mb-0">Pedidos</h6>
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
                    <h6 class="text-muted mb-0">En curso</h6>
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
                <h5 class="fw-bold mb-0">Pedidos recientes</h5>
                <a href="{{ route('admin.orders.index') }}" class="text-primary fw-bold text-decoration-none">Ver todos</a>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pedido</th>
                            <th>Cliente</th>
                            <th>Estado</th>
                            <th class="text-end">Total</th>
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
                                <td colspan="4" class="text-center py-4 text-muted">No hay pedidos todavía.</td>
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
                <h5 class="fw-bold mb-0">Control de stock</h5>
                <a href="{{ route('admin.products.index') }}" class="text-primary fw-bold text-decoration-none">Gestionar</a>
            </div>
            <div class="p-3">
                @forelse ($lowStockProducts as $product)
                    <div class="d-flex justify-content-between align-items-center py-2 border-bottom">
                        <div>
                            <div class="fw-bold">{{ $product->nombre }}</div>
                            <small class="text-muted">{{ $product->perfil_nombre }}</small>
                        </div>
                        <span class="badge {{ $product->stock <= 5 ? 'text-bg-danger' : 'text-bg-light' }}">{{ $product->stock }} uds.</span>
                    </div>
                @empty
                    <div class="text-center py-4 text-muted">No hay productos registrados.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
