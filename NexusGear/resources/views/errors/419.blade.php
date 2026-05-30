@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">419</h1>
    <h2>La sesión ha expirado</h2>
    <p class="lead text-secondary">Por motivos de seguridad, los formularios tienen un tiempo límite. No te preocupes, tus datos no se han perdido, solo debes refrescar la pantalla.</p>
    <div class="mt-4">
        {{-- Forzamos la recarga limpia de la página --}}
        <button onclick="window.location.reload();" class="btn btn-success px-4 py-2">
            <i class="bi bi-arrow-clockwise"></i> Actualizar página
        </button>
        <a href="{{ url('/') }}" class="btn btn-link text-secondary ms-2">Ir al inicio</a>
    </div>
</div>
@endsection