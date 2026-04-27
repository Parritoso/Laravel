@extends('layouts.app')

@section('title', 'Inicio - Tu Bienestar Postural')

@section('content')
<section class="py-5 mb-5 rounded-4 shadow-sm" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%); border-left: 8px solid var(--bs-primary);">
    <div class="row align-items-center px-4">
        <div class="col-lg-6">
            <span class="badge bg-primary mb-3 px-3 py-2">Nueva Colección 2026</span>
            <h1 class="display-4 fw-bold text-dark">Diseñados para <span class="text-primary">rendir</span>, creados para <span class="text-primary">cuidarte</span>.</h1>
            <p class="lead text-muted mb-4">En NexusGear fusionamos la alta tecnología con la ergonomía avanzada para que tu única preocupación sea alcanzar tus objetivos.</p>
            <div class="d-flex gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 fw-bold">Explorar Todo</a>
                <a href="#perfiles" class="btn btn-outline-dark btn-lg px-4">Personalizar mi setup</a>
            </div>
        </div>
        <div class="col-lg-6 d-none d-lg-block text-center">
            <i class="bi bi-mouse3 text-primary" style="font-size: 10rem; opacity: 0.2;"></i>
        </div>
    </div>
</section>

<section id="perfiles" class="py-5">
    <div class="text-center mb-5">
        <h2 class="fw-bold">¿Cómo es tu jornada?</h2>
        <p class="text-muted">Elige tu perfil y te mostraremos los periféricos que mejor se adaptan a ti.</p>
    </div>
    
    <div class="row g-4">
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover">
                <div class="card-body p-0 d-flex">
                    <div class="bg-dark text-white p-5 w-100">
                        <i class="bi bi-controller display-3 mb-3"></i>
                        <h3 class="fw-bold">Gamer Pro</h3>
                        <p class="opacity-75">Busco precisión milimétrica y switches mecánicos sin sacrificar la salud de mis muñecas.</p>
                        <a href="{{ route('products.index', ['profile' => 'gamer']) }}" class="btn btn-primary mt-3 px-4">Ver Selección Gamer</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card h-100 border-0 shadow-sm overflow-hidden card-hover">
                <div class="card-body p-0 d-flex">
                    <div class="bg-light p-5 w-100 border-start border-primary border-5">
                        <i class="bi bi-briefcase display-3 text-primary mb-3"></i>
                        <h3 class="fw-bold">Office & Focus</h3>
                        <p class="text-muted">Necesito silencio, confort absoluto y periféricos que reduzcan la fatiga tras 8 horas de trabajo.</p>
                        <a href="{{ route('products.index', ['profile' => 'office']) }}" class="btn btn-outline-primary mt-3 px-4">Ver Selección Oficina</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section class="py-5 bg-white rounded-4 shadow-sm my-5">
    <div class="row text-center g-4">
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-shield-check text-primary display-5 mb-3"></i>
                <h4 class="fw-bold">Postura Natural</h4>
                <p class="text-muted">Nuestros ratones verticales mantienen el antebrazo en una posición neutra de "apretón de manos".</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-lightning-charge text-primary display-5 mb-3"></i>
                <h4 class="fw-bold">Rendimiento</h4>
                <p class="text-muted">Teclados compactos (60%) que reducen el recorrido de tus brazos, aumentando la velocidad.</p>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3">
                <i class="bi bi-heart-pulse text-primary display-5 mb-3"></i>
                <h4 class="fw-bold">Salud a largo plazo</h4>
                <p class="text-muted">Materiales viscoelásticos en reposamuñecas para prevenir el síndrome del túnel carpiano.</p>
            </div>
        </div>
    </div>
</section>

<section class="py-5">
    <div class="d-flex justify-content-between align-items-end mb-4">
        <div>
            <h2 class="fw-bold">Los favoritos de la comunidad</h2>
            <p class="text-muted mb-0">Periféricos que ya están cambiando la forma de trabajar y jugar.</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-primary fw-bold text-decoration-none">Ver todo <i class="bi bi-arrow-right"></i></a>
    </div>

    <div class="row g-4">
        <div class="col-6 col-lg-3">
            <div class="card border-0 shadow-sm h-100 p-2">
                <div class="bg-light rounded-3 mb-3" style="height: 200px; display: flex; align-items: center; justify-content: center;">
                     <i class="bi bi-mouse2 display-4 text-muted"></i>
                </div>
                <div class="card-body p-2">
                    <span class="text-muted small">Ratón Vertical</span>
                    <h6 class="fw-bold mb-1">Nexus Vertical Pro</h6>
                    <div class="text-primary fw-bold mb-3">59.99€</div>
                    <button class="btn btn-primary w-100 btn-sm rounded-pill shadow-sm">
                        <i class="bi bi-cart-plus"></i> Añadir
                    </button>
                </div>
            </div>
        </div>
        </div>
</section>
@endsection