@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-warning">403</h1>
    <h2>Acceso restringido</h2>
    <p class="lead text-secondary">Lo sentimos, no tienes los permisos necesarios para ver esta sección o realizar esta acción.</p>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-primary">Volver a la tienda</a>
        <button onclick="window.history.back();" class="btn btn-outline-secondary ms-2">Regresar</button>
    </div>
</div>
@endsection