@extends('layouts.app')

@vite(['resources/css/password-view.scss', 'resources/js/password-view.js'])

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('profile') }}" class="btn btn-link text-decoration-none p-0 me-3">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h2 class="fw-bold mb-0">{{ __('auth/perfil/update-password.heading') }}</h2>
            </div>

            <div class="card border-0 shadow-sm">
                <div class="card-body p-4">
                    @if(session('status') === 'password-updated')
                        <div class="alert alert-success border-0 shadow-sm mb-4">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            {{ __('auth/perfil/update-password.success') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('profile.password.update') }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-3 password-container">
                            <label class="form-label fw-bold">{{ __('auth/perfil/update-password.current_password') }}</label>
                            <input type="password" name="current_password" class="form-control @error('current_password', 'updatePassword') is-invalid @enderror" required>
                            <i class="bi bi-eye toggle-password" id="togglePasswordIcon"></i>
                            @error('current_password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 password-container">
                            <label class="form-label fw-bold">{{ __('auth/perfil/update-password.new_password') }}</label>
                            <input type="password" name="password" class="form-control @error('password', 'updatePassword') is-invalid @enderror" required>
                            <i class="bi bi-eye toggle-password" id="toggleNewPasswordIcon"></i>
                            @error('password', 'updatePassword') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>

                        <div class="mb-3 password-container">
                            <label class="form-label fw-bold">{{ __('auth/perfil/update-password.confirm_password') }}</label>
                            <input type="password" name="password_confirmation" class="form-control" required>
                            <i class="bi bi-eye toggle-password" id="toggleNewPasswordConfirmationIcon"></i>
                        </div>

                        <div class="d-grid mt-4">
                            <button type="submit" class="btn btn-primary fw-bold shadow-sm">
                                {{ __('auth/perfil/update-password.submit') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="alert alert-info mt-4 border-0 shadow-sm">
                <i class="bi bi-info-circle-fill me-2"></i>
                {{ __('auth/perfil/update-password.tip') }}
            </div>
        </div>
    </div>
</div>
@endsection
