@extends('layouts.app')

@section('title', __('errors.session_expired.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">419</h1>
    <h2>{{ __('errors.session_expired.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.session_expired.message') }}</p>
    <div class="mt-4">
        <button onclick="window.location.reload();" class="btn btn-success px-4 py-2">
            <i class="bi bi-arrow-clockwise"></i> {{ __('errors.refresh_page') }}
        </button>
        <a href="{{ url('/') }}" class="btn btn-link text-secondary ms-2">{{ __('errors.go_home') }}</a>
    </div>
</div>
@endsection
