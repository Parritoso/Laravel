@extends('layouts.app')

@section('title', 'Editar Mi Perfil')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('profile') }}" class="btn btn-link text-decoration-none p-0 me-3">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h2 class="fw-bold mb-0">Configuración de Perfil</h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">Información de Cuenta</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Nombre Completo</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">Idioma de Preferencia</label>
                                <select name="language" class="form-select">
                                    <option value="es" {{ $user->language == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="pt" {{ $user->language == 'pt' ? 'selected' : '' }}>Português</option>
                                    <option value="ja" {{ $user->language == 'ja' ? 'selected' : '' }}>日本語</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">Email (No editable)</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                                <div class="form-text">Para cambiar el email, contacta con soporte técnico.</div>
                            </div>
                        </div>
                    </div>
                </div>

{{--                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">Dirección de Envío</h5>
                    </div>
                    @foreach ($user->direcciones as $dir )
                        <div class="card-body p-4">
                            <div class="row g-3">
                                <div class="col-10">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Calle</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $dir->calle) }}" placeholder="Calle, número, piso...">
                                </div>
                                <div class="col-2">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Número</label>
                                    <input type="text" name="number" class="form-control" value="{{ old('number', $dir->numero) }}">
                                </div>
                                <div class="col-md-8">
                                    <label class="form-label small fw-bold text-muted text-uppercase">Ciudad</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $dir->ciudad) }}">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label small fw-bold text-muted text-uppercase">C.P.</label>
                                    <input type="text" name="zip_code" class="form-control" value="{{ old('zip_code', $dir->codigo_postal) }}">
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>--}}

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('profile') }}" class="btn btn-light px-4 fw-bold">Cancelar</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection