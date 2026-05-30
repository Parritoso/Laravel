@extends('layouts.app')

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-danger">500</h1>
    <h2>Algo ha salido mal en nuestros servidores</h2>
    <p class="lead text-secondary">Estamos experimentando problemas técnicos intermitentes. Nuestro equipo de desarrollo ya ha sido notificado.</p>
    
    {{-- El código de rastreo para soporte técnico --}}
    @if(isset($globalErrorRef))
        <div class="mt-4">
            <span class="badge bg-dark text-monospace px-3 py-2 fs-6">
                Código de soporte: {{ $globalErrorRef }}
            </span>
        </div>
    @endif

    <a href="{{ url('/') }}" class="btn btn-secondary mt-4">
        Regresar al inicio
    </a>
</div>
@endsection