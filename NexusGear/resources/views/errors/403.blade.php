@extends('layouts.app')

@section('title', __('errors.forbidden.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-warning">403</h1>
    <h2>{{ __('errors.forbidden.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.forbidden.message') }}</p>
    <div class="mt-4">
        <a href="{{ url('/') }}" class="btn btn-primary">{{ __('errors.back_to_store') }}</a>
        <button onclick="window.history.back();" class="btn btn-outline-secondary ms-2">{{ __('errors.go_back') }}</button>
    </div>
</div>
@endsection
