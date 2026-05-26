@extends('layouts.app')

@section('title', __('auth/two-factor-challenge.2fa_challenge_title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-3">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" style="width: 70px; height: 70px;">
                            <i class="bi bi-shield-lock-fill text-primary fs-3"></i>
                        </div>
                        <h4 class="fw-bold mb-1">{{ __('auth/two-factor-challenge.2fa_challenge_heading') }}</h4>
                    </div>

                    {{-- Formulario para código de aplicación móvil --}}
                    <div id="totp-container">
                        <p class="text-muted small text-center mb-4">{{ __('auth/two-factor-challenge.2fa_challenge_totp_desc') }}</p>
                        
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">{{ __('auth/two-factor-challenge.2fa_auth_code_label') }}</label>
                                <input type="text" name="code" class="form-control form-control-lg text-center font-monospace fs-4" placeholder="000000" inputmode="numeric" autofocus autocomplete="one-time-code">
                                @error('code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">{{ __('auth/two-factor-challenge.2fa_verify_btn') }}</button>
                        </form>
                        
                        <button type="button" class="btn btn-link text-decoration-none small w-100 text-center text-secondary" onclick="toggleChallengeModes()">
                            {{ __('auth/two-factor-challenge.2fa_use_recovery_link') }}
                        </button>
                    </div>

                    {{-- Formulario alternativo para códigos de recuperación --}}
                    <div id="recovery-container" class="d-none">
                        <p class="text-muted small text-center mb-4">{{ __('auth/two-factor-challenge.2fa_challenge_recovery_desc') }}</p>
                        
                        <form method="POST" action="{{ url('/two-factor-challenge') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label small fw-bold text-muted">{{ __('auth/two-factor-challenge.2fa_recovery_code_label') }}</label>
                                <input type="text" name="recovery_code" class="form-control form-control-lg text-center font-monospace small" placeholder="abcde-12345">
                                @error('recovery_code')
                                    <div class="text-danger small mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary w-100 fw-bold py-2 mb-3 shadow-sm">{{ __('auth/two-factor-challenge.2fa_verify_btn') }}</button>
                        </form>
                        
                        <button type="button" class="btn btn-link text-decoration-none small w-100 text-center text-secondary" onclick="toggleChallengeModes()">
                            {{ __('auth/two-factor-challenge.2fa_use_totp_link') }}
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleChallengeModes() {
        const totpDiv = document.getElementById('totp-container');
        const recoveryDiv = document.getElementById('recovery-container');
        
        totpDiv.classList.toggle('d-none');
        recoveryDiv.classList.toggle('d-none');

        // Limpiar y enfocar los inputs correspondientes al cambiar
        if (!totpDiv.classList.contains('d-none')) {
            totpDiv.querySelector('input[name="code"]').focus();
            recoveryDiv.querySelector('input[name="recovery_code"]').value = '';
        } else {
            recoveryDiv.querySelector('input[name="recovery_code"]').focus();
            totpDiv.querySelector('input[name="code"]').value = '';
        }
    }
</script>
@endsection