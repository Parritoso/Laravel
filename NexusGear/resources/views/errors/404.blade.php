@extends('layouts.app') {{-- Reemplaza por tu layout principal --}}

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <h2>¡Ups! Página no encontrada</h2>
    <p class="lead text-secondary">El producto o sección que buscas no existe o ha sido movido.</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">
        Volver a la tienda
    </a>
</div>
@endsection