@extends('layouts.admin')

@section('title', 'Pedidos')
@section('page-title', 'Pedidos')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="fw-bold mb-0">Pedidos de clientes</h5>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
                <select name="estado" class="form-select">
                    <option value="">Todos los estados</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['estado'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-dark" type="submit">Filtrar</button>
            </form>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Pedido</th>
                    <th>Cliente</th>
                    <th>Factura</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Total</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td class="fw-bold">#{{ $order->id }}</td>
                        <td>
                            <div class="fw-semibold">{{ $order->usuario->name }}</div>
                            <small class="text-muted">{{ $order->usuario->email }}</small>
                        </td>
                        <td>{{ $order->factura->numero_factura }}</td>
                        <td>{{ $order->fecha_formateada }}</td>
                        <td><span class="badge text-bg-{{ $order->estado_badge }}">{{ $order->estado_label }}</span></td>
                        <td class="fw-bold">{{ $order->factura->total_formateado }}</td>
                        <td class="text-end">
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                Ver detalle
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No hay pedidos para mostrar.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $orders->links() }}
</div>
@endsection
