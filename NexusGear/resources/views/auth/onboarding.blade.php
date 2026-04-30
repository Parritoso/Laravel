@extends('layouts.app')

@section('title', 'Configura tu Experiencia')
@vite(['resources/css/onboarding.scss', 'resources/js/onboarding.js'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="progress mb-5" style="height: 6px;">
                <div id="onboarding-progress" class="progress-bar bg-primary" role="progressbar" style="width: 25%;"></div>
            </div>

            <form action="{{ route('onboarding.store') }}" method="POST" id="onboarding-form">
                @csrf
                
                <div class="onboarding-step" id="step-1">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Selecciona tu idioma</h2>
                        <p class="text-muted">Personalizaremos la interfaz para ti.</p>
                    </div>
                    <div class="row g-3">
                        @foreach(['es' => 'Español', 'en' => 'English', 'pt' => 'Português', 'ja' => '日本語'] as $code => $lang)
                        <div class="col-6 col-md-3 text-center">
                            <input type="radio" class="btn-check" name="language" id="lang-{{ $code }}" value="{{ $code }}" {{ $code == 'es' ? 'checked' : '' }}>
                            <label class="btn btn-outline-primary w-100 py-3 shadow-sm border-2" for="lang-{{ $code }}">
                                <div class="fw-bold">{{ strtoupper($code) }}</div>
                                <small>{{ $lang }}</small>
                            </label>
                        </div>
                        @endforeach
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <a href="/" class="text-muted text-decoration-none small">Saltar configuración</a>
                        <button type="button" class="btn btn-primary px-5 fw-bold shadow-sm" onclick="nextStep(2)">Siguiente</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-2">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">¿A dónde enviamos tus pedidos?</h2>
                        <p class="text-muted">Añade tu dirección principal para agilizar tus compras.</p>
                    </div>
                    <div class="card border-0 shadow-sm p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">Calle y Número</label>
                                <input type="text" name="address" class="form-control" placeholder="Ej. Calle Ergo, 24, 2ºB">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Ciudad</label>
                                <input type="text" name="city" class="form-control" placeholder="Sevilla">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Código Postal</label>
                                <input type="text" name="zip_code" class="form-control" placeholder="41012">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(1)">Atrás</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold" onclick="nextStep(3)">Continuar</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-3">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">¿Qué te interesa mejorar primero?</h2>
                        <p class="text-muted">Marcando tus metas posturales.</p>
                    </div>
                    <div class="card border-0 shadow-sm p-4">
                        <div class="list-group list-group-flush">
                            <label class="list-group-item d-flex gap-3 py-3 pointer">
                                <input class="form-check-input flex-shrink-0" type="checkbox" name="interests[]" value="wrist_health">
                                <span>
                                    <strong class="d-block">Salud de las muñecas</strong>
                                    <small class="text-muted">Enfoque en ratones verticales y reposamuñecas ergonómicos.</small>
                                </span>
                            </label>
                            <label class="list-group-item d-flex gap-3 py-3 pointer">
                                <input class="form-check-input flex-shrink-0" type="checkbox" name="interests[]" value="speed">
                                <span>
                                    <strong class="d-block">Velocidad y Precisión</strong>
                                    <small class="text-muted">Teclados mecánicos de alto rendimiento y switches optimizados.</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(2)">Atrás</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold" onclick="nextStep(4)">Siguiente</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">Define tu hábitat natural</h2>
                        <p class="text-muted">Último paso para completar tu perfil.</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="habitat" id="gamer" value="gamer">
                            <label class="btn btn-outline-primary w-100 p-4 shadow-sm h-100 border-2" for="gamer">
                                <i class="bi bi-controller display-4 d-block mb-2"></i>
                                <span class="fw-bold">Gamer Pro</span>
                                <p class="small mt-2 opacity-75">Busco rendimiento y baja latencia.</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="habitat" id="office" value="office">
                            <label class="btn btn-outline-primary w-100 p-4 shadow-sm h-100 border-2" for="office">
                                <i class="bi bi-laptop display-4 d-block mb-2"></i>
                                <span class="fw-bold">Oficina / WFH</span>
                                <p class="small mt-2 opacity-75">Busco confort total para largas jornadas.</p>
                            </label>
                        </div>
                    </div>
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                            ¡Listo! Ir a NexusGear <i class="bi bi-rocket-takeoff ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(3)">Atrás</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection