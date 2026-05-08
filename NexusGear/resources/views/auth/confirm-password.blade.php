@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 text-center">
                <div class="mb-3 text-primary">
                    <i class="bi bi-shield-lock display-4"></i>
                </div>
                <h4 class="fw-bold">Área Segura</h4>
                <p class="text-muted small">Por favor, confirma tu contraseña actual para continuar.</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPwd" placeholder="Contraseña" required>
                        <label for="floatingPwd">Tu contraseña actual</label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        Confirmar y Continuar
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection