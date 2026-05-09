@extends('layouts.admin')

@section('title', __('admin/orders/index.title'))
@section('page-title', __('admin/orders/index.title'))

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="fw-bold mb-0">{{ __('admin/orders/index.card_title') }}</h5>
            <form method="GET" action="{{ route('admin.orders.index') }}" class="d-flex gap-2">
                <select name="estado" class="form-select">
                    <option value="">{{ __('admin/orders/index.all_statuses') }}</option>
                    @foreach ($statuses as $value => $label)
                        <option value="{{ $value }}" @selected(($filters['estado'] ?? '') === $value)>{{ $label }}</option>
                    @endforeach
                </select>
                <button class="btn btn-dark" type="submit">{{ __('admin/orders/index.filter') }}</button>
            </form>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>{{ __('admin/orders/index.col_order') }}</th>
                    <th>{{ __('admin/orders/index.col_client') }}</th>
                    <th>{{ __('admin/orders/index.col_invoice') }}</th>
                    <th>{{ __('admin/orders/index.col_date') }}</th>
                    <th>{{ __('admin/orders/index.col_status') }}</th>
                    <th>{{ __('admin/orders/index.col_total') }}</th>
                    <th class="text-end">{{ __('admin/orders/index.col_actions') }}</th>
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
                                {{ __('admin/orders/index.view') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">{{ __('admin/orders/index.empty') }}</td>
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
