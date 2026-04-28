@csrf

<div class="row g-4 justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body p-4">

                <div class="mb-3">
                    <label for="nombre" class="form-label fw-semibold">Nombre</label>
                    <input id="nombre" name="nombre" value="{{ old('nombre', $categoria->nombre) }}"
                           class="form-control @error('nombre') is-invalid @enderror" required>
                    @error('nombre') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="mb-4">
                    <label for="slug" class="form-label fw-semibold">Slug</label>
                    <input id="slug" name="slug" value="{{ old('slug', $categoria->slug) }}"
                           class="form-control @error('slug') is-invalid @enderror"
                           placeholder="ej: gamer-pro" required>
                    <div class="form-text">Solo minúsculas, números y guiones. Se usa en las URLs de filtrado.</div>
                    @error('slug') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="d-flex gap-2">
                    <button class="btn btn-primary fw-bold flex-grow-1" type="submit">Guardar</button>
                    <a href="{{ route('admin.categorias.index') }}" class="btn btn-outline-dark">Cancelar</a>
                </div>

            </div>
        </div>
    </div>
</div>
