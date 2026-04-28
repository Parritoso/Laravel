@csrf

<div class="row g-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $product->nombre) }}" class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="descripcion" class="form-label fw-semibold">Descripción</label>
                    <textarea id="descripcion" name="descripcion" rows="6" class="form-control @error('descripcion') is-invalid @enderror" required>{{ old('descripcion', $product->descripcion) }}</textarea>
                    @error('descripcion') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">
                <div class="mb-3">
                    <label for="precio" class="form-label fw-semibold">Precio</label>
                    <div class="input-group">
                        <input id="precio" type="number" step="0.01" min="0" name="precio" value="{{ old('precio', $product->precio) }}" class="form-control @error('precio') is-invalid @enderror" required>
                        <span class="input-group-text">€</span>
                        @error('precio') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="stock" class="form-label fw-semibold">Stock</label>
                    <input id="stock" type="number" min="0" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control @error('stock') is-invalid @enderror" required>
                    @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-3">
                    <label for="perfil" class="form-label fw-semibold">Perfil</label>
                    <select id="perfil" name="perfil" class="form-select @error('perfil') is-invalid @enderror" required>
                        <option value="office" @selected(old('perfil', $product->perfil) === 'office')>Office & Focus</option>
                        <option value="gamer" @selected(old('perfil', $product->perfil) === 'gamer')>Gamer Pro</option>
                    </select>
                    @error('perfil') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="form-check form-switch mb-4">
                    <input class="form-check-input" type="checkbox" role="switch" id="destacado" name="destacado" value="1" @checked(old('destacado', $product->destacado))>
                    <label class="form-check-label" for="destacado">Producto destacado</label>
                </div>

                <button class="btn btn-primary w-100 fw-bold" type="submit">
                    Guardar producto
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-dark w-100 mt-2">Cancelar</a>
            </div>
        </div>
    </div>
</div>
