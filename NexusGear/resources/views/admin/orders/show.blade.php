@extends('layouts.admin')

@section('title', 'Pedido #'.$order->id)
@section('page-title', 'Pedido #'.$order->id)

@section('content')
<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Productos del pedido</h5>
                <span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead>
                        <tr>
                            <th>Producto</th>
                            <th>Cantidad</th>
                            <th>Precio</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->lineas as $line)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="admin-product-icon">
                                            <i class="bi {{ $line->producto->icono }}"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $line->producto->nombre }}</div>
                                            <small class="text-muted">{{ $line->producto->perfil_nombre }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>{{ $line->cantidad }}</td>
                                <td>{{ $line->precio_unitario_formateado }}</td>
                                <td class="text-end fw-bold">{{ $line->subtotal_formateado }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Cliente</h5>
                <div class="fw-semibold">{{ $order->usuario->name }}</div>
                <div class="text-muted">{{ $order->usuario->email }}</div>
                <hr>
                <div class="d-flex justify-content-between mb-2">
                    <span>Fecha</span>
                    <strong>{{ $order->fecha_formateada }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>Factura</span>
                    <strong>{{ $order->factura->numero_factura }}</strong>
                </div>
            </div>
        </div>

        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Estado</h5>
                <form method="POST" action="{{ route('admin.orders.update', $order) }}">
                    @csrf
                    @method('PATCH')
                    <select name="estado" class="form-select mb-3">
                        @foreach ($statuses as $value => $label)
                            <option value="{{ $value }}" @selected($order->estado === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100 fw-bold" type="submit">Actualizar estado</button>
                </form>
            </div>
        </div>

        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <h5 class="fw-bold mb-3">Factura</h5>
                <div class="d-flex justify-content-between mb-2">
                    <span>Subtotal</span>
                    <strong>{{ $order->factura->subtotal_formateado }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span>IVA 21%</span>
                    <strong>{{ $order->factura->iva_formateado }}</strong>
                </div>
                <hr>
                <div class="d-flex justify-content-between fs-5">
                    <span>Total</span>
                    <strong class="text-primary">{{ $order->factura->total_formateado }}</strong>
                </div>
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-dark w-100 mt-3">Volver a pedidos</a>
            </div>
        </div>
    </div>
</div>
@endsection
