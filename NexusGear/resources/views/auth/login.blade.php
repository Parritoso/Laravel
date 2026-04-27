@extends('layouts.app')

@section('title', __('auth/login.title'))

@section('content')
<div class="row justify-content-center align-items-center" style="min-height: 70vh;">
    <div class="col-md-5">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="text-center mb-4">
                    <div class="display-4 text-primary mb-2">
                        <i class="bi bi-person-circle"></i>
                    </div>
                    <h2 class="fw-bold">{{__('auth/login.welcome')}}</h2>
                    <p class="text-muted">{{__('auth/login.subtitle')}}</p>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger border-0 shadow-sm">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="email" name="email" class="form-control" id="email" placeholder="nombre@ejemplo.com" value="{{ old('email') }}" required autofocus>
                        <label for="email">{{__('auth/login.email')}}</label>
                    </div>

                    <div class="form-floating mb-2">
                        <input type="password" name="password" class="form-control" id="password" placeholder="Contraseña" required>
                        <label for="password">{{__('auth/login.password')}}</label>
                    </div>

                    <div class="text-end mb-4">
                        <a href="{{ route('password.request') }}" class="text-primary small fw-semibold text-decoration-none">
                            {{__('auth/login.forgot_password')}}
                        </a>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
                            {{__('auth/login.submit')}} <i class="bi bi-box-arrow-in-right ms-1"></i>
                        </button>
                    </div>
                </form>

                <hr class="my-4 text-muted">

                <div class="text-center">
                    <p class="mb-0 text-muted small">{{__('auth/login.no_account')}} 
                        <a href="{{ route('register') }}" class="text-primary fw-bold text-decoration-none">{{__('auth/login.register_link')}}</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection