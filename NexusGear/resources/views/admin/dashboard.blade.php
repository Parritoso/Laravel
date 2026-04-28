@extends('layouts.admin')

@section('title', 'Resumen')
@section('page-title', 'Dashboard')

@section('content')
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm p-3">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 p-3 rounded-3 text-primary me-3">
                    <i class="bi bi-currency-euro fs-3"></i>
                </div>
                <div>
                    <h6 class="text-muted mb-0">Ventas Mes</h6>
                    <h4 class="fw-bold mb-0">12.450€</h4>
                </div>
            </div>
        </div>
    </div>
    </div>

<div class="row g-4">
    <div class="col-md-7">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Productos más deseados <i class="bi bi-heart-fill text-danger"></i></h5>
            </div>
            <div class="table-responsive p-3">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Producto</th>
                            <th>Categoría</th>
                            <th class="text-center">Total Favoritos</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Nexus Vertical Mouse</td>
                            <td><span class="badge bg-light text-dark">Ratones</span></td>
                            <td class="text-center fw-bold text-primary">142</td>
                        </tr>
                        </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-md-5">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-header bg-white border-0 py-3">
                <h5 class="fw-bold mb-0">Pedidos Recientes</h5>
            </div>
            <div class="p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="mb-0 fw-bold">Pedido #4521</h6>
                        <small class="text-muted">Juan Pérez · Hace 5 min</small>
                    </div>
                    <span class="badge bg-success bg-opacity-10 text-success">Pagado</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection