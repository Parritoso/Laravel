@extends('layouts.app')

@section('title', __('about.title'))

@section('content')
<div class="about-page">
    <section class="mb-5">
        <span class="text-primary fw-bold">{{ __('about.kicker') }}</span>
        <h1 class="fw-bold mt-2">{{ __('about.hero_title') }}</h1>
        <p class="lead text-muted">{{ __('about.hero_subtitle') }}</p>

        <a href="{{ __('about.github_url') }}" target="_blank" rel="noopener" class="btn btn-dark">
            <i class="bi bi-github me-1"></i> {{ __('about.github_link') }}
        </a>
    </section>

    <section class="row g-4 mb-5">
        <div class="col-md-4">
            <h2 class="h4 fw-bold">{{ __('about.brand_title') }}</h2>
            <p class="text-muted">{{ __('about.brand_text') }}</p>
        </div>
        <div class="col-md-4">
            <h2 class="h4 fw-bold">{{ __('about.mission_title') }}</h2>
            <p class="text-muted">{{ __('about.mission_text') }}</p>
        </div>
        <div class="col-md-4">
            <h2 class="h4 fw-bold">{{ __('about.trust_title') }}</h2>
            <p class="text-muted">{{ __('about.trust_text') }}</p>
        </div>
    </section>

    <section class="mb-5">
        <h2 class="fw-bold mb-3">{{ __('about.profiles_title') }}</h2>
        <div class="row g-4">
            <div class="col-md-6">
                <div class="border rounded p-4 h-100">
                    <i class="bi bi-briefcase text-primary fs-2"></i>
                    <h3 class="h5 fw-bold mt-3">{{ __('about.office_title') }}</h3>
                    <p class="text-muted mb-0">{{ __('about.office_text') }}</p>
                </div>
            </div>
            <div class="col-md-6">
                <div class="border rounded p-4 h-100">
                    <i class="bi bi-controller text-primary fs-2"></i>
                    <h3 class="h5 fw-bold mt-3">{{ __('about.gamer_title') }}</h3>
                    <p class="text-muted mb-0">{{ __('about.gamer_text') }}</p>
                </div>
            </div>
        </div>
    </section>

    <section>
        <h2 class="fw-bold mb-3">{{ __('about.team_title') }}</h2>
        <div class="row g-4">
            @foreach (__('about.team') as $member)
                <div class="col-md-4">
                    <article class="border rounded p-4 h-100">
                        <h3 class="h5 fw-bold">{{ $member['name'] }}</h3>
                        <p class="text-primary fw-semibold mb-2">{{ $member['role'] }}</p>
                        <p class="text-muted mb-0">{{ $member['description'] }}</p>
                    </article>
                </div>
            @endforeach
        </div>
    </section>
</div>
@endsection