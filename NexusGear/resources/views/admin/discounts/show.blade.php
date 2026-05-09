@extends('layouts.admin')

@section('title', __('admin/discounts/show.page_title') . ': ' . $discount->codigo)
@section('page-title', __('admin/discounts/show.page_title'))

@section('content')

{{-- Cabecera: breadcrumb + botones --}}
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <nav aria-label="{{ __('common.breadcrumb') }}">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">{{ __('common.admin') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.discounts.index') }}" class="text-decoration-none">{{ __('admin/discounts/show.breadcrumb') }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $discount->codigo }}</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.discounts.edit', $discount) }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-pencil me-1"></i> {{ __('admin/discounts/show.edit') }}
        </a>
        <button class="btn btn-danger fw-bold shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#deleteDiscountShowModal"
                data-nombre="{{ $discount->codigo }}"
                data-action="{{ route('admin.discounts.destroy', $discount) }}">
            <i class="bi bi-trash me-1"></i> {{ __('admin/discounts/show.delete') }}
        </button>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/discounts/show.stat_products') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $discount->productos->count() }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                @php $estaCaducado = \Carbon\Carbon::parse($discount->fecha_fin)->isPast(); @endphp
                <div class="bg-{{ $estaCaducado ? 'danger' : 'success' }} bg-opacity-10 p-3 rounded-3 text-{{ $estaCaducado ? 'danger' : 'success' }} me-3">
                    <i class="bi bi-calendar-event fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/discounts/show.stat_status') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $estaCaducado ? __('admin/discounts/show.stat_expired') : __('admin/discounts/show.stat_active') }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                    <i class="bi bi-percent fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/discounts/show.stat_value') }}</h6>
                    <h4 class="fw-bold mb-0">
                        {{ $discount->tipo === 'porcentaje' ? $discount->valor . '%' : number_format($discount->valor, 2, ',', '.') . ' €' }}
                    </h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    {{-- Columna principal: Productos afectados --}}
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">{{ __('admin/discounts/show.products_title') }}</h5>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admin/discounts/show.col_product') }}</th>
                            <th>{{ __('admin/discounts/show.col_category') }}</th>
                            <th>{{ __('admin/discounts/show.col_base_price') }}</th>
                            <th>{{ __('admin/discounts/show.col_final_price') }}</th>
                            <th class="text-end">{{ __('admin/discounts/show.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($discount->productos as $producto)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" width="40" height="40" class="rounded shadow-sm" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <span class="fw-bold">{{ $producto->nombre }}</span>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark border">
                                        {{ $producto->getPerfilNombreAttribute() }}
                                    </span>
                                </td>
                                <td>{{ number_format($producto->precio, 2, ',', '.') }} €</td>
                                <td class="text-success fw-bold">
                                    @php
                                        $precioFinal = $discount->tipo === 'porcentaje'
                                            ? $producto->precio * (1 - ($discount->valor / 100))
                                            : max(0, $producto->precio - $discount->valor);
                                    @endphp
                                    {{ number_format($precioFinal, 2, ',', '.') }} €
                                </td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.show', $producto) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> {{ __('admin/discounts/show.view') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-slash-circle fs-3 d-block mb-2"></i>
                                    {{ __('admin/discounts/show.no_products') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    {{-- Columna lateral: Detalles técnicos del descuento --}}
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">{{ __('admin/discounts/show.coupon_info') }}</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small d-block">{{ __('admin/discounts/show.promo_code') }}</label>
                    <span class="fw-bold fs-5 text-primary">{{ $discount->codigo }}</span>
                </div>

                <hr class="text-muted opacity-25">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/discounts/show.type_label') }}:</span>
                    <span class="badge bg-info text-dark text-capitalize">{{ $discount->tipo }}</span>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/discounts/show.expiry_label') }}:</span>
                    <strong class="{{ $estaCaducado ? 'text-danger' : '' }}">
                        {{ \Carbon\Carbon::parse($discount->fecha_fin)->format('d/m/Y H:i') }}
                    </strong>
                </div>

                <div class="mt-4 p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">{{ __('admin/discounts/show.internal_note_label') }}:</small>
                    <p class="small mb-0 italic text-secondary">
                        {{ __('admin/discounts/show.internal_note') }}
                    </p>
                </div>
            </div>
        </div>

        <a href="{{ route('admin.discounts.index') }}" class="btn btn-outline-dark w-100 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> {{ __('admin/discounts/show.back') }}
        </a>
    </div>
</div>

{{-- Modal de eliminación --}}
<x-delete-modal
    id="deleteDiscountShowModal"
    formId="deleteDiscountShowForm"
    :title="__('admin/discounts/show.delete_confirm_title')"
    :message="__('admin/discounts/index.delete_confirm_msg')"
    :buttonText="__('admin/discounts/show.delete_btn')"
    icon="bi-tag-fill"
/>

@endsection
