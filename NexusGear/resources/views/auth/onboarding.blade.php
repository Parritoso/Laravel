@extends('layouts.app')

@section('title', __('auth/onboarding.title'))
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
                    <div class="d-grid gap-2 mt-5">
                        <button type="submit" class="btn btn-primary btn-lg fw-bold shadow">
                            {{ __('auth/onboarding.finish') }} <i class="bi bi-rocket-takeoff ms-2"></i>
                        </button>
                        <button type="button" class="btn btn-link text-muted" onclick="nextStep(3)">{{ __('auth/onboarding.back') }}</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

@endsection