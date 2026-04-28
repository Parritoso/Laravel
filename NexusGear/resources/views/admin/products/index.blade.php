@extends('layouts.admin')

@section('page-title', 'Gestión de Productos')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Catálogo de Periféricos</h5>
        <a href="#" class="btn btn-primary fw-bold shadow-sm">
            <i class="bi bi-plus-lg"></i> Nuevo Producto
        </a>
    </div>
    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Imagen</th>
                    <th>Nombre</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>#001</td>
                    <td><div class="rounded bg-light" style="width: 40px; height:40px;"></div></td>
                    <td class="fw-bold">Teclado Split 60%</td>
                    <td><span class="text-danger fw-bold">5 unidades</span></td>
                    <td>89.99€</td>
                    <td class="text-end">
                        <button class="btn btn-sm btn-outline-secondary me-1"><i class="bi bi-pencil"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
@endsection