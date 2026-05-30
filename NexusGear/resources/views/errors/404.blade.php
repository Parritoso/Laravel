@extends('layouts.app')

@section('title', __('errors.not_found.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-muted">404</h1>
    <h2>{{ __('errors.not_found.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.not_found.message') }}</p>
    <a href="{{ url('/') }}" class="btn btn-primary mt-3">
        {{ __('errors.back_to_store') }}
    </a>
</div>
@endsection
