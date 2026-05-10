@extends('layouts.admin')

@section('title', __('admin/products/index.products'))
@section('page-title', __('admin/products/index.management'))

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="fw-bold mb-0">{{ __('admin/products/index.catalog') }}</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-plus-lg"></i> {{ __('admin/products/index.new_product') }}
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mt-3">
            <div class="col-md-6">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="{{ __('admin/products/index.search') }}">
            </div>
            <div class="col-md-3">
                <select name="profile" class="form-select">
                    <option value="">{{ __('admin/products/index.all_profiles') }}</option>
                    <option value="office" @selected(($filters['profile'] ?? '') === 'office')>{{ __('admin/products/index.office') }}</option>
                    <option value="gamer" @selected(($filters['profile'] ?? '') === 'gamer')>{{ __('admin/products/index.gamer') }}</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark" type="submit">{{ __('admin/products/index.filter') }}</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">{{ __('admin/products/index.clear') }}</a>
            </div>
        </form>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>{{ __('admin/products/index.product') }}</th>
                    <th>{{ __('admin/products/index.profile') }}</th>
                    <th>{{ __('admin/products/index.stock') }}</th>
                    <th>{{ __('admin/products/index.price') }}</th>
                    <th>{{ __('admin/products/index.featured') }}</th>
                    <th class="text-end">{{ __('admin/products/index.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="admin-product-icon">
                                    @if ($product->imagen)
                                        <img src="{{ asset('storage/' . $product->imagen) }}" alt="{{ $product->nombre }}" class="w-100 h-100" style="object-fit: cover; border-radius: inherit;">
                                    @else
                                        <i class="bi {{ $product->icono }}"></i>
                                    @endif
                                </div>
                                <div>
                                    <div class="fw-bold">{{ $product->nombre }}</div>
                                    <small class="text-muted">{{ \Illuminate\Support\Str::limit($product->descripcion, 72) }}</small>
                                </div>
                            </div>
                        </td>
                        <td>{{ $product->perfil_nombre }}</td>
                        <td>
                            <span class="fw-bold {{ $product->stock <= 5 ? 'text-danger' : 'text-success' }}">
                                {{ $product->stock }}
                            </span>
                        </td>
                        <td>{{ $product->precio_formateado }}</td>
                        <td>
                            @if ($product->destacado)
                                <span class="badge bg-primary">{{ __('admin/products/index.yes') }}</span>
                            @else
                                <span class="badge text-bg-light">{{ __('admin/products/index.no') }}</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-dark">
                                    <i class="bi bi-eye me-1"></i> {{ __('admin/products/index.view') }}
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> {{ __('admin/products/index.edit') }}
                                </a>
                                {{--<form method="POST" action="{{ route('admin.products.destroy', $product) }}">
                                    @csrf
                                    @method('DELETE')--}}
                                    <button class="btn btn-sm btn-outline-danger" data-bs-toggle="modal" 
                                        data-bs-target="#deleteProductModal"
                                        data-nombre="{{ $product->nombre }}"
                                        data-action="{{ route('admin.products.destroy', $product) }}">
                                        <i class="bi bi-trash me-1"></i> {{ __('admin/products/index.delete') }}
                                    </button>
                                {{-- </form> --}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">{{ __('admin/products/index.no_results') }}</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
{{-- MODAL DE ELIMINACIÓN DE PRODUCTO --}}
<x-delete-modal 
    id="deleteProductModal" 
    formId="deleteProductForm"
    :title="__('admin/products/index.delete_confirm_title')"
    :message="__('admin/products/index.delete_confirm_msg')"
    :buttonText="__('admin/products/index.delete')"
    icon="bi-box-seam-fill"
/>
@endsection
