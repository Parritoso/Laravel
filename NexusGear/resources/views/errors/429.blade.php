@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-info">429</h1>
    <h2>Demasiadas solicitudes</h2>
    <p class="lead text-secondary">Has realizado demasiadas peticiones en muy poco tiempo. Nuestro sistema ha activado el escudo de protección temporal.</p>
    <p class="text-muted font-italic">Por favor, espera unos minutos e inténtalo de nuevo.</p>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-outline-primary">Intentar acceder ahora</a>
    </div>
</div>
@endsection