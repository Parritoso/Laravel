@extends('layouts.admin')

@section('title', 'Descuentos')
@section('page-title', 'Gestión de descuentos')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Listado de Descuentos</h5>
        <div class="d-flex gap-2">

            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-plus-lg"></i> Nuevo descuento
            </a>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Estado / Expira</th>
                    <th>Productos</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($descuentos as $descuento)
                    <tr>
                        <td>
                            <span class="badge bg-light text-dark border fw-bold px-3">
                                {{ $descuento->codigo }}
                            </span>
                        </td>
                        <td>
                            @if($descuento->tipo === 'porcentaje')
                                <span class="text-primary"><i class="bi bi-percent"></i> Porcentaje</span>
                            @else
                                <span class="text-success"><i class="bi bi-cash-stack"></i> Fijo</span>
                            @endif
                        </td>
                        <td class="fw-bold">
                            {{ $descuento->valor }}{{ $descuento->tipo === 'porcentaje' ? '%' : '€' }}
                        </td>
                        <td>
                            @if($descuento->esValido())
                                <small class="d-block text-muted">
                                    Vence el {{ $descuento->fecha_fin->format('d/m/Y') }}
                                </small>
                                <span class="badge bg-success-subtle text-success">Activo</span>
                            @else
                                <small class="d-block text-muted">
                                    Expiró el {{ $descuento->fecha_fin->format('d/m/Y') }}
                                </small>
                                <span class="badge bg-danger-subtle text-danger">Caducado</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-light border">
                                {{ $descuento->productos_count ?? $descuento->productos->count() }} prod.
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.discounts.edit', $descuento) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> Editar
                                </a>
                                {{--<form method="POST" action="{{ route('admin.discounts.destroy', $descuento) }}">
                                    @csrf
                                    @method('DELETE')--}}
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                                        data-bs-target="#deleteDiscountsModal"
                                        data-codigo="{{ $descuento->codigo }}"
                                        data-action="{{ route('admin.discounts.destroy', $descuento) }}">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                {{--</form>--}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-tag d-block mb-2 fs-2"></i>
                            No hay descuentos configurados actualmente.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{-- MODAL DE ELIMINACIÓN --}}
<x-delete-modal 
    id="deleteDiscountsModal" 
    formId="deleteCategoryForm"
    :title="__('admin/discounts/index.delete_confirm_title')"
    :message="__('admin/discounts/index.delete_confirm_msg')"
    :buttonText="__('admin/discounts/index.delete_btn')"
    icon="bi-exclamation-octagon"
    :showWarning="false" 
/>
{{--<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="deleteModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-exclamation-octagon" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-1">¿Estás seguro de que deseas eliminar el descuento?</p>
                <h4 class="fw-bold" id="discountCodeDisplay"></h4>
                <p class="text-muted small">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold">Eliminar permanentemente</button>
                </form>
            </div>
        </div>
    </div>
</div>--}}
@endsection
