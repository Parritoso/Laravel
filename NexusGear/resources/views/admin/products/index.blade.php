@extends('layouts.admin')

@section('title', 'Productos')
@section('page-title', 'Gestión de productos')

@section('content')
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-0 py-3">
        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3">
            <h5 class="fw-bold mb-0">Catálogo</h5>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary fw-bold shadow-sm">
                <i class="bi bi-plus-lg"></i> Nuevo producto
            </a>
        </div>

        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-2 mt-3">
            <div class="col-md-6">
                <input type="search" name="q" value="{{ $filters['q'] ?? '' }}" class="form-control" placeholder="Buscar producto">
            </div>
            <div class="col-md-3">
                <select name="profile" class="form-select">
                    <option value="">Todos los perfiles</option>
                    <option value="office" @selected(($filters['profile'] ?? '') === 'office')>Office & Focus</option>
                    <option value="gamer" @selected(($filters['profile'] ?? '') === 'gamer')>Gamer Pro</option>
                </select>
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button class="btn btn-dark" type="submit">Filtrar</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Limpiar</a>
            </div>
        </form>
    </div>

    <div class="table-responsive p-3">
        <table class="table table-hover align-middle">
            <thead>
                <tr>
                    <th>Producto</th>
                    <th>Perfil</th>
                    <th>Stock</th>
                    <th>Precio</th>
                    <th>Destacado</th>
                    <th class="text-end">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($products as $product)
                    <tr>
                        <td>
                            <div class="d-flex align-items-center gap-3">
                                <div class="admin-product-icon">
                                    <i class="bi {{ $product->icono }}"></i>
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
                                <span class="badge bg-primary">Sí</span>
                            @else
                                <span class="badge text-bg-light">No</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-outline-dark" target="_blank">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger" type="submit" onclick="return confirm('¿Eliminar este producto?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">No hay productos con esos filtros.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="mt-4">
    {{ $products->links() }}
</div>
@endsection
