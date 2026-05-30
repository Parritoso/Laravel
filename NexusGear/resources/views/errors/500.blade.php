@extends('layouts.app')

@section('title', __('errors.server_error.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-danger">500</h1>
    <h2>{{ __('errors.server_error.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.server_error.message') }}</p>
    
    @if(isset($globalErrorRef))
        <div class="mt-4">
            <span class="badge bg-dark text-monospace px-3 py-2 fs-6">
                {{ __('errors.support_code', ['code' => $globalErrorRef]) }}
            </span>
        </div>
    @endif

    <a href="{{ url('/') }}" class="btn btn-secondary mt-4">
        {{ __('errors.go_home') }}
    </a>
</div>
@endsection
