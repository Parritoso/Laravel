@extends('layouts.app')

@section('title', __('home.title'))

@vite(['resources/css/home.scss', 'resources/js/app.js'])

@section('content')
<div class="home-shell">
    <section class="home-hero mb-5">
        <div class="home-hero__inner">
            <span class="home-kicker">NexusGear</span>
            <h1 class="fw-bold">{{ __('home.hero_title') }}</h1>
            <p class="lead mb-4">{{ __('home.hero_subtitle') }}</p>
            <div class="d-flex flex-wrap gap-3">
                <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg px-4 fw-bold">{{ __('home.view_catalog') }}</a>
                <a href="#setup" class="btn btn-outline-light btn-lg px-4">{{ __('home.choose_by_use') }}</a>
            </div>
        </div>
    </section>

    <section id="setup" class="home-band">
        <div>
            <span class="home-kicker text-primary">{{ __('home.ways_to_buy') }}</span>
            <h2 class="fw-bold mb-2">{{ __('home.ways_desc_1') }}</h2>
            <p class="mb-0">{{ __('home.ways_desc_2') }}</p>
        </div>
        <a href="{{ route('products.index') }}" class="text-primary fw-bold text-decoration-none">
            {{ __('home.view_all') }} <i class="bi bi-arrow-right"></i>
        </a>
    </section>

    <div class="row g-4 py-4">
        <div class="col-md-6">
            <a href="{{ route('products.index', ['profile' => 'office']) }}" class="profile-link">
                <i class="bi bi-briefcase mb-3"></i>
                <h3 class="h4 fw-bold">{{ __('home.office_title') }}</h3>
                <p class="text-muted mb-0">{{ __('home.office_desc') }}</p>
            </a>
        </div>
        <div class="col-md-6">
            <a href="{{ route('products.index', ['profile' => 'gamer']) }}" class="profile-link">
                <i class="bi bi-controller mb-3"></i>
                <h3 class="h4 fw-bold">{{ __('home.gamer_title') }}</h3>
                <p class="text-muted mb-0">{{ __('home.gamer_desc') }}</p>
            </a>
        </div>
    </div>

    <section class="principle-list">
        <article>
            <span>01</span>
            <h3 class="h5 fw-bold">{{ __('home.feat_01_title') }}</h3>
            <p class="text-muted mb-0">{{ __('home.feat_01_desc') }}</p>
        </article>
        <article>
            <span>02</span>
            <h3 class="h5 fw-bold">{{ __('home.feat_02_title') }}</h3>
            <p class="text-muted mb-0">{{ __('home.feat_02_desc') }}</p>
        </article>
        <article>
            <span>03</span>
            <h3 class="h5 fw-bold">{{ __('home.feat_03_title') }}</h3>
            <p class="text-muted mb-0">{{ __('home.feat_03_desc') }}</p>
        </article>
    </section>

    <section class="pb-5">
        <div class="d-flex flex-wrap justify-content-between align-items-end gap-3 mb-4">
            <div>
                <span class="home-kicker text-primary">{{ __('home.initial_sel') }}</span>
                <h2 class="fw-bold mb-1">{{ __('home.initial_title') }}</h2>
                <p class="text-muted mb-0">{{ __('home.initial_desc') }}</p>
            </div>
            <a href="{{ route('products.index') }}" class="text-primary fw-bold text-decoration-none">
                Ver catálogo <i class="bi bi-arrow-right"></i>
            </a>
        </div>

        <div class="row g-4">
            @forelse (($featuredProducts ?? collect()) as $product)
                <div class="col-6 col-lg-3">
                    <a href="{{ route('products.show', $product) }}" class="home-product-card">
                        <span class="home-product-visual">
                            <i class="bi {{ $product->icono }}"></i>
                        </span>
                        <span class="home-product-body">
                            <span class="text-muted small d-block mb-1">{{ $product->perfil_nombre }}</span>
                            <strong class="d-block mb-2">{{ $product->nombre }}</strong>
                            <span class="text-primary fw-bold">{{ $product->precio_formateado }}</span>
                        </span>
                    </a>
                </div>
            @empty
                <div class="col-12">
                    <div class="empty-state">
                        <i class="bi bi-box-seam"></i>
                        <h3 class="h5 fw-bold">{{ __('home.no_products') }}</h3>
                    </div>
                </div>
            @endforelse
        </div>
    </section>
</div>
@endsection
