@extends('layouts.app')

@section('title', __('home.title'))

@section('content')
<style>
    .home-shell {
        --ng-ink: #1f2937;
        --ng-muted: #64748b;
        --ng-line: rgba(45, 55, 72, .1);
    }

    .home-hero {
        padding: clamp(2rem, 6vw, 4rem) 0;
        border-radius: 1.25rem;
        color: white;
        background:
            linear-gradient(135deg, #16202c 0%, #2D3748 48%, #117864 100%);
        box-shadow: 0 24px 55px rgba(15, 23, 42, .18);
    }

    .home-hero__inner {
        width: 100%;
        padding: 0 clamp(1.25rem, 5vw, 4rem);
    }

    .home-kicker {
        display: inline-flex;
        margin-bottom: 1rem;
        color: #A2D9CE;
        font-size: .78rem;
        font-weight: 800;
        letter-spacing: .08em;
        text-transform: uppercase;
    }

    .home-hero h1 {
        margin-bottom: 1rem;
        font-size: clamp(2.2rem, 5vw, 4.4rem);
        line-height: 1.04;
        letter-spacing: 0;
    }

    .home-hero p {
        color: rgba(255,255,255,.78);
    }

    .home-band {
        display: grid;
        grid-template-columns: minmax(0, 1fr) minmax(260px, 380px);
        gap: 2rem;
        align-items: end;
        padding: 3rem 0 1.5rem;
        border-bottom: 1px solid var(--ng-line);
    }

    .home-band p {
        color: var(--ng-muted);
    }

    .profile-link {
        display: block;
        height: 100%;
        padding: 1.35rem;
        border: 1px solid var(--ng-line);
        border-radius: 1rem;
        background: white;
        color: var(--ng-ink);
        text-decoration: none;
        transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
    }

    .profile-link:hover {
        transform: translateY(-3px);
        border-color: rgba(79, 209, 197, .55);
        box-shadow: 0 18px 38px rgba(15,23,42,.08);
        color: var(--ng-ink);
    }

    .profile-link i {
        color: #117864;
        font-size: 2rem;
    }

    .principle-list {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 1px;
        margin: 2rem 0 4rem;
        background: var(--ng-line);
        border: 1px solid var(--ng-line);
        border-radius: 1rem;
        overflow: hidden;
    }

    .principle-list article {
        padding: 1.5rem;
        background: #fff;
    }

    .principle-list span {
        display: block;
        margin-bottom: .75rem;
        color: #117864;
        font-weight: 800;
    }

    .home-product-card {
        display: grid;
        height: 100%;
        grid-template-rows: 150px 1fr;
        border: 1px solid var(--ng-line);
        border-radius: 1rem;
        background: white;
        overflow: hidden;
        text-decoration: none;
        color: var(--ng-ink);
        transition: transform .18s ease, box-shadow .18s ease;
    }

    .home-product-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 18px 38px rgba(15,23,42,.08);
        color: var(--ng-ink);
    }

    .home-product-visual {
        display: grid;
        place-items: center;
        background: linear-gradient(145deg, rgba(79, 209, 197, .15), rgba(45, 55, 72, .06));
        color: #117864;
    }

    .home-product-visual i {
        font-size: 3.2rem;
    }

    .home-product-body {
        padding: 1rem;
    }

    @media (max-width: 991.98px) {
        .home-band,
        .principle-list {
            grid-template-columns: 1fr;
        }
    }
</style>

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
