@extends('layouts.app')

@section('title', 'Nueva Contraseña')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <h2 class="fw-bold text-center mb-4">Actualizar Contraseña</h2>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Confirmar Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $request->email ?? old('email') }}" required>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Nueva Contraseña</label>
                        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror" required placeholder="Mínimo 8 caracteres">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Confirmar Nueva Contraseña</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            Restablecer Contraseña
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection