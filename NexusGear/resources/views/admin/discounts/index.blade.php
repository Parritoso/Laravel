@extends('layouts.admin')

@section('title', __('admin/discounts/index.title'))
@section('page-title', __('admin/discounts/index.management'))

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">{{ __('admin/discounts/index.card_title') }}</h5>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.discounts.create') }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-plus-lg"></i> {{ __('admin/discounts/index.new_discount') }}
            </a>
        </div>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>{{ __('admin/discounts/index.col_code') }}</th>
                    <th>{{ __('admin/discounts/index.col_type') }}</th>
                    <th>{{ __('admin/discounts/index.col_value') }}</th>
                    <th>{{ __('admin/discounts/index.col_status') }}</th>
                    <th>{{ __('admin/discounts/index.col_products') }}</th>
                    <th class="text-end">{{ __('admin/discounts/index.col_actions') }}</th>
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
                                <span class="text-primary"><i class="bi bi-percent"></i> {{ __('admin/discounts/index.type_percentage') }}</span>
                            @else
                                <span class="text-success"><i class="bi bi-cash-stack"></i> {{ __('admin/discounts/index.type_fixed') }}</span>
                            @endif
                        </td>
                        <td class="fw-bold">
                            {{ $descuento->valor }}{{ $descuento->tipo === 'porcentaje' ? '%' : '€' }}
                        </td>
                        <td>
                            @if($descuento->esValido())
                                <small class="d-block text-muted">
                                    {{ __('admin/discounts/index.expires', ['date' => $descuento->fecha_fin->format('d/m/Y')]) }}
                                </small>
                                <span class="badge bg-success-subtle text-success">{{ __('admin/discounts/index.active') }}</span>
                            @else
                                <small class="d-block text-muted">
                                    {{ __('admin/discounts/index.expired_on', ['date' => $descuento->fecha_fin->format('d/m/Y')]) }}
                                </small>
                                <span class="badge bg-danger-subtle text-danger">{{ __('admin/discounts/index.expired') }}</span>
                            @endif
                        </td>
                        <td>
                            <span class="badge text-bg-light border">
                                {{ __('admin/discounts/index.products_count', ['count' => $descuento->productos_count ?? $descuento->productos->count()]) }}
                            </span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.discounts.show', $descuento) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-eye me-1"></i> {{ __('admin/discounts/index.view') }}
                                </a>
                                <a href="{{ route('admin.discounts.edit', $descuento) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> {{ __('admin/discounts/index.edit') }}
                                </a>
                                {{--<form method="POST" action="{{ route('admin.discounts.destroy', $descuento) }}">
                                    @csrf
                                    @method('DELETE')--}}
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal"
                                        data-bs-target="#deleteDiscountsModal"
                                        data-codigo="{{ $descuento->codigo }}"
                                        data-action="{{ route('admin.discounts.destroy', $descuento) }}">
                                        <i class="bi bi-trash me-1"></i> {{ __('admin/discounts/index.delete') }}
                                    </button>
                                {{--</form>--}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-tag d-block mb-2 fs-2"></i>
                            {{ __('admin/discounts/index.empty') }}
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
    formId="deleteDiscountForm"
    :title="__('admin/discounts/index.delete_confirm_title')"
    :message="__('admin/discounts/index.delete_confirm_msg')"
    :buttonText="__('admin/discounts/index.delete_btn')"
    icon="bi-exclamation-octagon"
    :showWarning="false" 
/>
@endsection
