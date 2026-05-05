@extends('layouts.admin')

@section('title', $producto->nombre)
@section('page-title', $producto->nombre)

@section('content')

{{-- Cabecera: breadcrumb + botones --}}
<div class="d-flex flex-wrap justify-content-between align-items-start gap-3 mb-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb mb-0">
            <li class="breadcrumb-item">
                <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">Admin</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('admin.products.index') }}" class="text-decoration-none">Productos</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">{{ $producto->nombre }}</li>
        </ol>
    </nav>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.products.edit', $producto) }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-pencil me-1"></i> Editar
        </a>
        <button class="btn btn-danger fw-bold shadow-sm"
                data-bs-toggle="modal"
                data-bs-target="#deleteProductShowModal"
                data-nombre="{{ $producto->nombre }}"
                data-action="{{ route('admin.products.destroy', $producto) }}">
            <i class="bi bi-trash me-1"></i> Eliminar
        </button>
    </div>
</div>

{{-- Stat cards --}}
<div class="row g-4 mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-bag-check fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Unidades vendidas</h6>
                    <h4 class="fw-bold mb-0">{{ $totalVendidas }}</h4>
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
                    <h6 class="text-muted mb-0">Ingresos generados</h6>
                    <h4 class="fw-bold mb-0">{{ $ingresosFormateados }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-danger bg-opacity-10 p-3 rounded-3 text-danger me-3">
                    <i class="bi bi-heart fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">En favoritos</h6>
                    <h4 class="fw-bold mb-0">{{ $totalFavoritos }}</h4>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 p-3 rounded-3 text-warning me-3">
                    <i class="bi bi-cart3 fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">En carritos ahora</h6>
                    <h4 class="fw-bold mb-0">{{ $totalCarrito }}</h4>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Layout 2 columnas --}}
<div class="row g-4">

    {{-- Columna principal --}}
    <div class="col-lg-8">

        {{-- Información del producto --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Información del producto</h5>
                @if ($producto->destacado)
                    <span class="badge bg-primary">Destacado</span>
                @endif
            </div>
            <div class="card-body p-4">
                <div class="d-flex align-items-center gap-3 mb-4">
                    <div class="admin-product-icon" style="width:64px;height:64px;font-size:1.8rem;flex-shrink:0;">
                        @if ($producto->imagen)
                            <img src="{{ asset('storage/' . $producto->imagen) }}" alt="{{ $producto->nombre }}" class="w-100 h-100" style="object-fit: cover; border-radius: inherit;">
                        @else
                            <i class="bi {{ $producto->icono }}"></i>
                        @endif
                    </div>
                    <div>
                        <h4 class="fw-bold mb-1">{{ $producto->nombre }}</h4>
                        <span class="text-muted">{{ $producto->perfil_nombre }}</span>
                    </div>
                </div>

                <p class="text-muted mb-4">{{ $producto->descripcion }}</p>

                <div class="row g-3">
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Precio base</div>
                            <div class="fw-bold fs-5">{{ $producto->precio_formateado }}</div>
                        </div>
                    </div>
                    @if ($precioFinal !== null)
                        <div class="col-sm-4">
                            <div class="border border-success rounded-3 p-3 text-center">
                                <div class="text-success small mb-1">Precio con descuento</div>
                                <div class="fw-bold fs-5 text-success">
                                    {{ number_format($precioFinal, 2, ',', '.') }} €
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-sm-4">
                        <div class="border rounded-3 p-3 text-center">
                            <div class="text-muted small mb-1">Stock</div>
                            <div class="fw-bold fs-5 {{ $producto->stock <= 5 ? 'text-danger' : 'text-success' }}">
                                {{ $producto->stock }} uds
                                @if ($producto->stock <= 5)
                                    <span class="badge text-bg-danger ms-1">Bajo</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3 pt-3 border-top">
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-muted">Categoría</span>
                        <strong>{{ $producto->perfil_nombre }}</strong>
                    </div>
                    <div class="d-flex justify-content-between">
                        <span class="text-muted">Disponible en tienda</span>
                        @if ($producto->disponible)
                            <span class="badge text-bg-success">Sí</span>
                        @else
                            <span class="badge text-bg-danger">Sin stock</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Últimas ventas --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Últimas ventas</h5>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Pedido</th>
                            <th>Fecha</th>
                            <th>Cantidad</th>
                            <th>Precio ud.</th>
                            <th class="text-end">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($ultimasVentas as $linea)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $linea->pedido) }}"
                                       class="fw-bold text-decoration-none">
                                        #{{ $linea->pedido_id }}
                                    </a>
                                </td>
                                <td>{{ $linea->pedido->fecha->format('d/m/Y') }}</td>
                                <td>{{ $linea->cantidad }}</td>
                                <td>{{ $linea->precio_unitario_formateado }}</td>
                                <td class="text-end fw-bold">{{ $linea->subtotal_formateado }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-3 d-block mb-2"></i>
                                    Este producto aún no tiene ventas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    {{-- Columna lateral --}}
    <div class="col-lg-4">

        {{-- Descuentos asociados --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
                <h5 class="fw-bold mb-0">Descuentos</h5>
                <span class="badge text-bg-secondary">{{ $producto->descuentos->count() }}</span>
            </div>
            <div class="card-body p-4">
                @forelse ($producto->descuentos as $desc)
                    <div class="d-flex justify-content-between align-items-start mb-3 pb-3 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div>
                            <div class="fw-bold">{{ $desc->codigo }}</div>
                            <small class="text-muted">
                                @if ($desc->tipo === 'porcentaje')
                                    {{ $desc->valor }}% de descuento
                                @else
                                    {{ number_format($desc->valor, 2, ',', '.') }} € de descuento
                                @endif
                            </small>
                            <div class="text-muted small mt-1">
                                Caduca: {{ $desc->fecha_fin->format('d/m/Y') }}
                            </div>
                        </div>
                        @if ($desc->esValido())
                            <span class="badge text-bg-success">Activo</span>
                        @else
                            <span class="badge text-bg-danger">Caducado</span>
                        @endif
                    </div>
                @empty
                    <p class="text-muted mb-0">No hay descuentos asociados.</p>
                @endforelse
            </div>
        </div>

        {{-- Volver --}}
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark w-100 fw-semibold">
            <i class="bi bi-arrow-left me-1"></i> Volver al listado
        </a>

    </div>
</div>

{{-- Modal de eliminación --}}
<x-delete-modal
    id="deleteProductShowModal"
    formId="deleteProductShowForm"
    :title="__('admin/products/index.delete_confirm_title')"
    :message="__('admin/products/index.delete_confirm_msg')"
    :buttonText="__('admin/products/index.delete')"
    icon="bi-box-seam-fill"
/>

@endsection
