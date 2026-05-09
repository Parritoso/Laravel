@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card border-0 shadow-sm p-4 text-center">
                <div class="mb-3 text-primary">
                    <i class="bi bi-shield-lock display-4"></i>
                </div>
                <h4 class="fw-bold">{{ __('auth/confirm-password.heading') }}</h4>
                <p class="text-muted small">{{ __('auth/confirm-password.subtitle') }}</p>

                <form method="POST" action="{{ route('password.confirm') }}">
                    @csrf
                    <div class="form-floating mb-3">
                        <input type="password" name="password" class="form-control" id="floatingPwd" placeholder="{{ __('auth/confirm-password.password_label') }}" required>
                        <label for="floatingPwd">{{ __('auth/confirm-password.password_label') }}</label>
                    </div>

                    <button type="submit" class="btn btn-primary w-100 fw-bold shadow-sm">
                        {{ __('auth/confirm-password.submit') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
