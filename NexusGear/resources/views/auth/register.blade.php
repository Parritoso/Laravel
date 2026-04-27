@extends('layouts.app')

@section('title', __('auth/register.title'))

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-sm border-0">
            <div class="card-body p-5">
                <div class="mb-4">
                    <h2 class="fw-bold text-primary">{{__('auth/register.welcome_title')}}</h2>
                    <p class="text-muted">{{__('auth/register.description')}}</p>
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

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">{{__('auth/register.full_name')}}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-person"></i></span>
                                <input type="text" name="name" class="form-control border-start-0" value="{{ old('name') }}" required placeholder="Ej. Juan Pérez">
                            </div>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label fw-semibold">{{__('auth/register.email')}}</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email" class="form-control border-start-0" value="{{ old('email') }}" required placeholder="juan@ejemplo.com">
                            </div>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label fw-semibold">{{__('auth/register.password')}}</label>
                            <input type="password" name="password" class="form-control" required placeholder="Mín. 8 caracteres">
                        </div>

                        <div class="col-md-6 mb-4">
                            <label class="form-label fw-semibold">{{__('auth/register.confirm_password')}}</label>
                            <input type="password" name="password_confirmation" class="form-control" required placeholder="Repite la clave">
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary btn-lg shadow-sm fw-bold">
                            {{__('auth/register.finish_registration')}} <i class="bi bi-check-circle ms-1"></i>
                        </button>
                    </div>
                </form>

                <div class="mt-4 text-center">
                    <p class="text-muted small">{{__('auth/register.already_member')}} 
                        <a href="{{ route('login') }}" class="text-primary fw-bold text-decoration-none">{{__('auth/register.login_link')}} </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection