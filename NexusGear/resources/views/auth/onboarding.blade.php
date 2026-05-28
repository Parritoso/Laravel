@extends('layouts.app')

@section('title', __('auth/onboarding.title'))
@vite(['resources/css/onboarding.scss', 'resources/js/onboarding.js'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-7">
            
            <div class="progress mb-5" style="height: 6px;">
                <div id="onboarding-progress" class="progress-bar bg-primary" role="progressbar" style="width: 20%;"></div>
            </div>

            <form action="{{ route('onboarding.store') }}" method="POST" id="onboarding-form">
                @csrf
                
                <div class="onboarding-step" id="step-1">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">{{ __('auth/onboarding.step_lang_title') }}</h2>
                        <p class="text-muted">{{ __('auth/onboarding.step_lang_desc') }}</p>
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
                        <a href="/" class="text-muted text-decoration-none small">{{ __('auth/onboarding.skip') }}</a>
                        <button type="button" class="btn btn-primary px-5 fw-bold shadow-sm" onclick="nextStep(2)">{{ __('auth/onboarding.next') }}</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-2">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">{{ __('auth/onboarding.step_address_title') }}</h2>
                        <p class="text-muted">{{ __('auth/onboarding.step_address_desc') }}</p>
                    </div>
                    <div class="card border-0 shadow-sm p-4">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label fw-semibold">{{ __('auth/onboarding.street_label') }}</label>
                                <input type="text" name="address" class="form-control" placeholder="{{ __('auth/onboarding.street_placeholder') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('auth/onboarding.city_label') }}</label>
                                <input type="text" name="city" class="form-control" placeholder="{{ __('auth/onboarding.city_placeholder') }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">{{ __('auth/onboarding.zip_label') }}</label>
                                <input type="text" name="zip_code" class="form-control" placeholder="{{ __('auth/onboarding.zip_placeholder') }}">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(1)">{{ __('auth/onboarding.back') }}</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold" onclick="nextStep(3)">{{ __('auth/onboarding.continue') }}</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-3">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">{{ __('auth/onboarding.step_goal_title') }}</h2>
                        <p class="text-muted">{{ __('auth/onboarding.step_goal_desc') }}</p>
                    </div>
                    <div class="card border-0 shadow-sm p-4">
                        <div class="list-group list-group-flush">
                            <label class="list-group-item d-flex gap-3 py-3 pointer">
                                <input class="form-check-input flex-shrink-0" type="checkbox" name="interests[]" value="wrist_health">
                                <span>
                                    <strong class="d-block">{{ __('auth/onboarding.goal_wrist_title') }}</strong>
                                    <small class="text-muted">{{ __('auth/onboarding.goal_wrist_desc') }}</small>
                                </span>
                            </label>
                            <label class="list-group-item d-flex gap-3 py-3 pointer">
                                <input class="form-check-input flex-shrink-0" type="checkbox" name="interests[]" value="speed">
                                <span>
                                    <strong class="d-block">{{ __('auth/onboarding.goal_speed_title') }}</strong>
                                    <small class="text-muted">{{ __('auth/onboarding.goal_speed_desc') }}</small>
                                </span>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(2)">{{ __('auth/onboarding.back') }}</button>
                        <button type="button" class="btn btn-primary px-5 fw-bold" onclick="nextStep(4)">{{ __('auth/onboarding.next') }}</button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-4">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">{{ __('auth/onboarding.step_habitat_title') }}</h2>
                        <p class="text-muted">{{ __('auth/onboarding.step_habitat_desc') }}</p>
                    </div>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="habitat" id="gamer" value="gamer">
                            <label class="btn btn-outline-primary w-100 p-4 shadow-sm h-100 border-2" for="gamer">
                                <i class="bi bi-controller display-4 d-block mb-2"></i>
                                <span class="fw-bold">{{ __('auth/onboarding.habitat_gamer_title') }}</span>
                                <p class="small mt-2 opacity-75">{{ __('auth/onboarding.habitat_gamer_desc') }}</p>
                            </label>
                        </div>
                        <div class="col-md-6">
                            <input type="radio" class="btn-check" name="habitat" id="office" value="office">
                            <label class="btn btn-outline-primary w-100 p-4 shadow-sm h-100 border-2" for="office">
                                <i class="bi bi-laptop display-4 d-block mb-2"></i>
                                <span class="fw-bold">{{ __('auth/onboarding.habitat_office_title') }}</span>
                                <p class="small mt-2 opacity-75">{{ __('auth/onboarding.habitat_office_desc') }}</p>
                            </label>
                        </div>
                    </div>
                    <div class="d-flex justify-content-between align-items-center mt-5">
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(3)">{{ __('auth/onboarding.back') }}</button>
                        <!-- AHORA VA AL PASO 5 -->
                        <button type="button" class="btn btn-primary px-5 fw-bold shadow-sm" onclick="nextStep(5)">
                            {{ __('auth/onboarding.next') }}
                        </button>
                    </div>
                </div>

                <div class="onboarding-step d-none" id="step-5">
                    <div class="text-center mb-4">
                        <h2 class="fw-bold">{{ __('auth/onboarding.step_2fa_title') }}</h2>
                        <p class="text-muted">{{ __('auth/onboarding.step_2fa_desc') }}</p>
                    </div>

                    <div class="card border-0 shadow-sm p-4 text-center">
                        {{-- SUB-ESTADO A: Pregunta inicial --}}
                        <div id="2fa-init-section">
                            <i class="bi bi-shield-lock text-primary display-3 d-block mb-3"></i>
                            <h5>{{ __('auth/onboarding.2fa_prompt') }}</h5>
                            <p class="small text-muted mb-4">{{ __('auth/onboarding.2fa_prompt_desc') }}</p>
                            
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="button" class="btn btn-outline-secondary px-4" onclick="skip2FAAndFinish()">
                                    {{ __('auth/onboarding.2fa_skip_btn') }}
                                </button>
                                <button type="button" class="btn btn-primary px-4 fw-bold" onclick="initialize2FA()">
                                    <span id="2fa-spinner" class="spinner-border spinner-border-sm d-none me-2" role="status"></span>
                                    {{ __('auth/onboarding.2fa_enable_btn') }}
                                </button>
                            </div>
                        </div>

                        {{-- SUB-ESTADO B: Configuración activa (Oculto por defecto) --}}
                        <div id="2fa-setup-section" class="d-none">
                            <div class="row g-3 align-items-center text-start">
                                <div class="col-md-5 text-center">
                                    <div id="2fa-qr-container" class="bg-white p-2 border rounded d-inline-block">
                                        <!-- Aquí se inyectará el SVG por JS -->
                                    </div>
                                </div>
                                <div class="col-md-7">
                                    <div class="alert alert-warning py-2 px-3 small border-0 mb-3">
                                        <i class="bi bi-info-circle-fill me-1"></i> {{ __('auth/onboarding.2fa_scan_notice') }}
                                    </div>
                                    <p class="small mb-3">
                                        <strong>{{ __('auth/onboarding.2fa_manual_key') }}</strong> <code id="2fa-secret-key" class="d-block mt-1 fs-6 text-primary"></code>
                                    </p>
                                    
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-muted">{{ __('auth/onboarding.2fa_code_label') }}</label>
                                        <div class="input-group">
                                            <input type="text" id="2fa-verification-code" class="form-control" placeholder="123456" maxlength="6">
                                            <button class="btn btn-success fw-bold" type="button" onclick="confirm2FA()">{{ __('auth/onboarding.2fa_verify_btn') }}</button>
                                        </div>
                                        <div id="2fa-error-msg" class="text-danger small mt-1 d-none"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- SUB-ESTADO C: Éxito total (Oculto por defecto) --}}
                        <div id="2fa-success-section" class="d-none py-3">
                            <i class="bi bi-check-circle-fill text-success display-4 d-block mb-2"></i>
                            <h5 class="fw-bold text-success">{{ __('auth/onboarding.2fa_active_title') }}</h5>
                            <p class="small text-muted">{{ __('auth/onboarding.2fa_active_desc') }}</p>
                        </div>
                    </div>

                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" id="btn-finish-onboarding" class="btn btn-primary btn-lg fw-bold shadow">
                            {{ __('auth/onboarding.finish') }} <i class="bi bi-rocket-takeoff ms-2"></i>
                        </button>
                        <button type="button" id="btn-back-to-4" class="btn btn-link text-muted" onclick="nextStep(4)">{{ __('auth/onboarding.back') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection