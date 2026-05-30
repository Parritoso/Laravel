@extends('layouts.app')

@section('title', __('errors.too_many_requests.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-info">429</h1>
    <h2>{{ __('errors.too_many_requests.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.too_many_requests.message') }}</p>
    <p class="text-muted font-italic">{{ __('errors.too_many_requests.hint') }}</p>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-outline-primary">{{ __('errors.retry') }}</a>
    </div>
</div>
@endsection
