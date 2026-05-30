@extends('layouts.app')

@section('title', __('errors.maintenance.title'))

@section('content')
<div class="container text-center py-5">
    <h1 class="display-1 text-primary"><i class="bi bi-tools"></i></h1>
    <h2>{{ __('errors.maintenance.title') }}</h2>
    <p class="lead text-secondary">{{ __('errors.maintenance.message') }}</p>
    
    <div class="card bg-light max-w-md mx-auto my-4 p-4 shadow-sm" style="max-width: 500px; margin: 0 auto;">
        <h5>{{ __('errors.maintenance.question') }}</h5>
        <p class="small text-muted mb-0">{{ __('errors.maintenance.answer') }}</p>
    </div>

    <p class="text-secondary">{{ __('errors.maintenance.thanks') }}</p>
</div>
@endsection
