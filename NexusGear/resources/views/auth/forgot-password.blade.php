@extends('layouts.app')

@section('title', 'Recuperar Contraseña')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="display-4 text-primary mb-3">
                        <i class="bi bi-shield-lock"></i>
                    </div>
                    <h2 class="fw-bold">¿Olvidaste tu clave?</h2>
                    <p class="text-muted">No te preocupes. Introduce tu email y te enviaremos un enlace para restablecerla.</p>
                </div>

                @if (session('status'))
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}">
                    @csrf

                    <div class="form-floating mb-4">
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" id="email" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autofocus>
                        <label for="email">Correo Electrónico</label>
                        @error('email')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow-sm">
                            Enviar enlace de recuperación
                        </button>
                    </div>
                </form>

                <div class="text-center mt-4">
                    <a href="{{ route('login') }}" class="text-decoration-none text-muted small">
                        <i class="bi bi-arrow-left"></i> Volver al inicio de sesión
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection