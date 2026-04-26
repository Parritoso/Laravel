@extends('layouts.app')

@section('title', 'Verificar Cuenta')

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-6">
        <div class="card shadow-sm border-0 text-center">
            <div class="card-body p-5">
                <div class="display-1 text-primary mb-4">
                    <i class="bi bi-envelope-check"></i>
                </div>
                <h2 class="fw-bold mb-3">¡Casi hemos terminado!</h2>
                <p class="text-muted mb-4 fs-5">
                    Gracias por unirte a <strong>NexusGear</strong>. Para empezar a comprar tus periféricos ergonómicos, por favor verifica tu correo haciendo clic en el enlace que te acabamos de enviar.
                </p>

                @if (session('status') == 'verification-link-sent')
                    <div class="alert alert-success border-0 shadow-sm mb-4">
                        Se ha enviado un nuevo enlace de verificación a tu dirección de correo.
                    </div>
                @endif

                <div class="d-flex justify-content-center gap-3">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary fw-bold px-4">
                            Reenviar email de verificación
                        </button>
                    </form>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-secondary fw-bold px-4">
                            Cerrar Sesión
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection