@extends('layouts.app')

@section('title', __('auth/perfil/edit.title'))

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex align-items-center mb-4">
                <a href="{{ route('profile') }}" class="btn btn-link text-decoration-none p-0 me-3">
                    <i class="bi bi-arrow-left fs-4"></i>
                </a>
                <h2 class="fw-bold mb-0">{{ __('auth/perfil/edit.heading') }}</h2>
            </div>

            <form action="{{ route('profile.update') }}" method="POST">
                @csrf
                @method('PATCH')

                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0 pt-4 px-4">
                        <h5 class="fw-bold">{{ __('auth/perfil/edit.account_info') }}</h5>
                    </div>
                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">{{ __('auth/perfil/edit.full_name') }}</label>
                                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $user->name) }}">
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted text-uppercase">{{ __('auth/perfil/edit.preferred_language') }}</label>
                                <select name="language" class="form-select">
                                    <option value="es" {{ $user->language == 'es' ? 'selected' : '' }}>Español</option>
                                    <option value="en" {{ $user->language == 'en' ? 'selected' : '' }}>English</option>
                                    <option value="pt" {{ $user->language == 'pt' ? 'selected' : '' }}>Português</option>
                                    <option value="ja" {{ $user->language == 'ja' ? 'selected' : '' }}>日本語</option>
                                </select>
                            </div>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase">{{ __('auth/perfil/edit.email_label') }}</label>
                                <input type="email" class="form-control bg-light" value="{{ $user->email }}" readonly disabled>
                                <div class="form-text">{{ __('auth/perfil/edit.email_note') }}</div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2 mb-5">
                    <a href="{{ route('profile') }}" class="btn btn-light px-4 fw-bold">{{ __('auth/perfil/edit.cancel') }}</a>
                    <button type="submit" class="btn btn-primary px-5 fw-bold shadow-sm">
                        {{ __('auth/perfil/edit.save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
