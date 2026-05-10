@extends('layouts.admin')

@section('title', __('admin/categorias/show.page_title') . ': ' . $categoria->nombre)
@section('page-title', __('admin/categorias/show.page_title'))

@section('content')

<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <nav aria-label="{{ __('common.breadcrumb') }}">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">{{ __('common.admin') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.categorias.index') }}" class="text-decoration-none">{{ __('admin/categorias/show.breadcrumb') }}</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $categoria->nombre }}</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-pencil me-1"></i> {{ __('admin/categorias/show.edit') }}
        </a>
        <button class="btn btn-danger fw-bold shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#deleteCategoryShowModal"
                data-nombre="{{ $categoria->nombre }}"
                data-action="{{ route('admin.categorias.destroy', $categoria) }}">
            <i class="bi bi-trash me-1"></i> {{ __('admin/categorias/show.delete') }}
        </button>
    </div>
</div>

<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-box-seam fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/categorias/show.stat_products') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $totalProductos }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-success bg-opacity-10 p-3 rounded-3 text-success me-3">
                    <i class="bi bi-cash-stack fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/categorias/show.stat_revenue') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $ingresosFormateados }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                    <i class="bi bi-boxes fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/categorias/show.stat_stock') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $stockTotal }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-info bg-opacity-10 p-3 rounded-3 text-info me-3">
                    <i class="bi bi-bag-check fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">{{ __('admin/categorias/show.stat_sold') }}</h6>
                    <h4 class="fw-bold mb-0">{{ $unidadesVendidas }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">{{ __('admin/categorias/show.products_title') }}</h5>
                <span class="badge text-bg-secondary">{{ $totalProductos }}</span>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>{{ __('admin/categorias/show.col_product') }}</th>
                            <th>{{ __('admin/categorias/show.col_price') }}</th>
                            <th>{{ __('admin/categorias/show.col_stock') }}</th>
                            <th>{{ __('admin/categorias/show.col_sold') }}</th>
                            <th>{{ __('admin/categorias/show.col_revenue') }}</th>
                            <th class="text-end">{{ __('admin/categorias/show.col_actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($productos as $producto)
                            @php
                                $descuentoActivo = $producto->descuentos->first(fn ($descuento) => $descuento->esValido());
                                $precioFinal = $descuentoActivo
                                    ? $descuentoActivo->calcularPrecioDescontado((float) $producto->precio)
                                    : null;
                                $vendidas = $producto->lineasPedido->sum('cantidad');
                                $ingresos = $producto->lineasPedido->sum('subtotal');
                            @endphp
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        @if ($producto->imagen)
                                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" width="40" height="40" class="rounded shadow-sm" style="object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width:40px; height:40px;">
                                                <i class="bi {{ $producto->icono }} text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-bold">{{ $producto->nombre }}</div>
                                            <div class="d-flex gap-1 mt-1">
                                                @if ($producto->destacado)
                                                    <span class="badge text-bg-primary">{{ __('admin/categorias/show.badge_featured') }}</span>
                                                @endif
                                                @if ($descuentoActivo)
                                                    <span class="badge text-bg-success">{{ __('admin/categorias/show.badge_discount') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if ($precioFinal !== null)
                                        <div class="fw-bold text-success">{{ number_format($precioFinal, 2, ',', '.') }} €</div>
                                        <small class="text-muted text-decoration-line-through">{{ $producto->precio_formateado }}</small>
                                    @else
                                        <span class="fw-bold">{{ $producto->precio_formateado }}</span>
                                    @endif
                                </td>
                                <td>
                                    <span class="fw-bold {{ $producto->stock <= 5 ? 'text-danger' : 'text-success' }}">
                                        {{ $producto->stock }}
                                    </span>
                                    @if ($producto->stock <= 5)
                                        <span class="badge text-bg-danger ms-1">{{ __('admin/categorias/show.low_stock') }}</span>
                                    @endif
                                </td>
                                <td>{{ $vendidas }}</td>
                                <td class="fw-bold">{{ number_format((float) $ingresos, 2, ',', '.') }} €</td>
                                <td class="text-end">
                                    <a href="{{ route('admin.products.show', $producto) }}" class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-eye"></i> {{ __('admin/categorias/show.view_product') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    {{ __('admin/categorias/show.no_products') }}
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">{{ __('admin/categorias/show.category_info') }}</h5>
            </div>
            <div class="card-body p-4">
                <div class="mb-3">
                    <label class="text-muted small d-block">{{ __('admin/categorias/show.name_label') }}</label>
                    <span class="fw-bold fs-5 text-primary">{{ $categoria->nombre }}</span>
                </div>

                <div class="mb-3">
                    <label class="text-muted small d-block">{{ __('admin/categorias/show.slug_label') }}</label>
                    <code>{{ $categoria->slug }}</code>
                </div>

                <hr class="text-muted opacity-25">

                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/categorias/show.featured_label') }}</span>
                    <strong>{{ $productosDestacados }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/categorias/show.discounted_label') }}</span>
                    <strong>{{ $productosConDescuento }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/categorias/show.low_stock_label') }}</span>
                    <strong class="{{ $stockBajo > 0 ? 'text-danger' : '' }}">{{ $stockBajo }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">{{ __('admin/categorias/show.avg_price_label') }}</span>
                    <strong>{{ $precioMedioFormateado }}</strong>
                </div>

                <div class="mt-4 p-3 bg-light rounded-3">
                    <small class="text-muted d-block mb-1">{{ __('admin/categorias/show.top_product_label') }}</small>
                    @if ($productoMasVendido && $productoMasVendido->lineasPedido->sum('cantidad') > 0)
                        <div class="fw-bold">{{ $productoMasVendido->nombre }}</div>
                        <small class="text-muted">
                            {{ __('admin/categorias/show.units_sold', ['count' => $productoMasVendido->lineasPedido->sum('cantidad')]) }}
                        </small>
                    @else
                        <p class="small mb-0 text-secondary">{{ __('admin/categorias/show.no_top_product') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-dark w-100 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> {{ __('admin/categorias/show.back') }}
        </a>
    </div>
</div>

<x-delete-modal
    id="deleteCategoryShowModal"
    formId="deleteCategoryShowForm"
    :title="__('admin/categorias/index.delete_confirm_title')"
    :message="__('admin/categorias/index.delete_confirm_msg')"
    :buttonText="__('admin/categorias/index.delete_btn')"
    icon="bi-folder-trash3-fill"
    :showWarning="true"
/>

@endsection
