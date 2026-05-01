@extends('layouts.admin')

@section('title', 'Categorías')
@section('page-title', 'Gestión de categorías')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Categorías</h5>
        <a href="{{ route('admin.categorias.create') }}" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg"></i> Nueva categoría
        </a>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Productos</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categorias as $categoria)
                    <tr>
                        <td class="fw-bold">{{ $categoria->nombre }}</td>
                        <td><code>{{ $categoria->slug }}</code></td>
                        <td>
                            <span class="badge text-bg-light">{{ $categoria->productos_count }}</span>
                        </td>
                        <td class="text-end">
                            <div class="d-flex gap-1 justify-content-end">
                                <a href="{{ route('admin.categorias.edit', $categoria) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-pencil me-1"></i> Editar
                                </a>
                                {{--<form method="POST" action="{{ route('admin.categorias.destroy', $categoria) }}">
                                    @csrf
                                    @method('DELETE')--}}
                                    <button class="btn btn-sm btn-outline-danger"
                                            data-bs-toggle="modal" 
                                            data-bs-target="#deleteCategoryModal"
                                            data-nombre="{{ $categoria->nombre }}"
                                            data-action="{{ route('admin.categorias.destroy', $categoria) }}">
                                        <i class="bi bi-trash me-1"></i> Eliminar
                                    </button>
                                {{--</form>--}}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-5 text-muted">No hay categorías creadas.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
{{-- MODAL DE ELIMINACIÓN --}}
<x-delete-modal 
    id="deleteCategoryModal" 
    formId="deleteCategoryForm"
    :title="__('admin/categorias/index.delete_confirm_title')"
    :message="__('admin/categorias/index.delete_confirm_msg')"
    :buttonText="__('admin/categorias/index.delete_btn')"
    icon="bi-folder-trash3-fill"
    :showWarning="true" 
/>
{{--<div class="modal fade" id="deleteCategoryModal" tabindex="-1" aria-labelledby="deleteCategoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="deleteCategoryModalLabel">Confirmar eliminación</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center py-4">
                <div class="text-danger mb-3">
                    <i class="bi bi-trash3-fill" style="font-size: 3rem;"></i>
                </div>
                <p class="mb-1">¿Estás seguro de que deseas eliminar la categoría?</p>
                <h4 class="fw-bold" id="categoryNameDisplay"></h4>
                <div class="alert alert-warning mx-3 mt-3 mb-0" style="font-size: 0.85rem;">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>
                    Ten en cuenta que esto podría afectar a los productos asociados.
                </div>
            </div>
            <div class="modal-footer border-0 bg-light d-flex justify-content-center">
                <button type="button" class="btn btn-secondary px-4 fw-semibold" data-bs-dismiss="modal">Cancelar</button>
                <form id="deleteCategoryForm" method="POST" action="">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger px-4 fw-bold">Sí, eliminar</button>
                </form>
            </div>
        </div>
    </div>
</div>--}}
@endsection
