<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusGear - @yield('title', __('layouts/app.title'))</title>

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="icon" type="image/svg+xml" href="{{ asset('favicon.svg') }}">
    <link rel="icon" type="image/png" sizes="96x96" href="{{ asset('favicon-96x96.png') }}">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('apple-touch-icon.png') }}">
    <link rel="manifest" href="{{ asset('site.webmanifest') }}">
    <meta name="theme-color" content="#ffffff">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    @vite(['resources/css/app.scss', 'resources/js/app.js'])

</head>

<body>
    <div id="app">
        <nav class="navbar navbar-expand-lg navbar-light navbar-nexus sticky-top">
            <div class="container">
                <a class="navbar-brand fw-bold text-primary" href="{{ url('/') }}">
                    <i class="bi bi-cpu"></i> NexusGear
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        <li class="nav-item"><a class="nav-link" href="{{ route('products.index') }}">{{__('layouts/app.catalog')}}</a></li>
                        <li class="nav-item"><a class="nav-link" href="{{ route('favorites.index') }}">{{__('layouts/app.favorites')}}</a></li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <a href="{{ route('cart.index') }}" class="position-relative text-dark text-decoration-none" aria-label="{{ __('layouts/app.cart') }}">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">{{ $cartCount ?? 0 }}</span>
                            </a>
                        </li>
                        @auth
                        <li class="nav-item dropdown me-3">
                            <a class="nav-link position-relative p-0 text-dark" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi {{ auth()->user()->unreadNotifications->isNotEmpty() ? 'bi-bell-fill text-primary' : 'bi-bell' }} fs-5"></i>
                                @if(auth()->user()->unreadNotifications->count() > 0)
                                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem; padding: 0.25em 0.4em;">
                                        {{ auth()->user()->unreadNotifications->count() }}
                                    </span>
                                @endif
                            </a>
                            <div class="dropdown-menu dropdown-menu-end p-0 shadow-sm border-0 mt-2" style="width: 320px; max-height: 420px; overflow-y: auto;">
                                <div class="p-3 border-bottom d-flex justify-content-between align-items-center bg-light rounded-top">
                                    <h6 class="fw-bold mb-0 small"><i class="bi bi-journal-text me-1 text-primary"></i> {{ __('layouts/app.notifications_title') }}</h6>
                                    @if(auth()->user()->unreadNotifications->isNotEmpty())
                                        <form action="{{ route('notifications.markAllAsRead') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="btn btn-link p-0 x-small text-decoration-none text-muted" style="font-size: 0.75rem;">
                                                {{ __('layouts/app.mark_all_read') }}
                                            </button>
                                        </form>
                                    @endif
                                </div>
                                
                                <div class="list-group list-group-flush">
                                    @forelse(auth()->user()->unreadNotifications as $notification)
                                        <a href="{{ route('notifications.read', $notification->id) }}" class="list-group-item list-group-item-action p-3 d-flex gap-3 align-items-start border-0 border-bottom">
                                            <div class="bg-opacity-10 p-2 rounded-circle flex-shrink-0 
                                                {{ $notification->data['tipo'] === 'precio' ? 'bg-success text-success' : '' }}
                                                {{ $notification->data['tipo'] === 'stock_bajo' ? 'bg-warning text-warning-emphasis' : '' }}
                                                {{ $notification->data['tipo'] === 'stock_agotado' ? 'bg-danger text-danger' : '' }}
                                                {{ $notification->data['tipo'] === 'stock_disponible' ? 'bg-info text-info' : '' }}
                                            ">
                                                <i class="bi 
                                                    {{ $notification->data['tipo'] === 'precio' ? 'bi-tag-fill' : '' }}
                                                    {{ $notification->data['tipo'] === 'stock_bajo' ? 'bi-box-seam' : '' }}
                                                    {{ $notification->data['tipo'] === 'stock_agotado' ? 'bi-exclamation-octagon-fill' : '' }}
                                                    {{ $notification->data['tipo'] === 'stock_disponible' ? 'bi-arrow-counterclockwise' : '' }}
                                                "></i>
                                            </div>
                                            <div class="flex-grow-1">
                                                <p class="mb-1 text-dark small font-medium" style="line-height: 1.3;">{{ $notification->data['mensaje'] }}</p>
                                                <small class="text-muted text-uppercase fw-bold" style="font-size: 0.65rem;">{{ $notification->created_at->diffForHumans() }}</small>
                                            </div>
                                        </a>
                                    @empty
                                        <div class="text-center py-4 text-muted rounded-bottom bg-white">
                                            <i class="bi bi-bell-slash display-6 d-block mb-2 text-opacity-25"></i>
                                            <span class="small">{{ __('layouts/app.no_notifications') }}</span>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </li>
                        @endauth
                        @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{ __('layouts/app.login') }}</a></li>
                        @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if (Auth::user()->isAdmin())
                                <li>
                                    <a class="dropdown-item fw-semibold text-primary" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-1"></i> {{__('layouts/app.dashboard')}}
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                @endif
                                <li><a class="dropdown-item" href="{{ route('profile') }}">{{__('layouts/app.profile')}}</a></li>
                                <li><a class="dropdown-item" href="{{ route('orders.index') }}">{{__('layouts/app.orders')}}</a></li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item" href="#"
                                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{__('layouts/app.close_session')}}</a>
                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                        @csrf
                                    </form>
                                </li>
                            </ul>
                        </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4 container">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('layouts/app.close') }}"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="{{ __('layouts/app.close') }}"></button>
                </div>
            @endif

            @yield('content')
        </main>

        <footer class="bg-white border-top py-5 mt-auto">
            <div class="container text-center text-muted">
                <p>&copy; {{ date('Y') }} {{__('layouts/app.footer')}}</p>
                <div class="small">
                    <a href="#" class="text-decoration-none text-primary mx-2">{{__('layouts/app.terms')}}</a>
                    <a href="{{ route('about') }}" class="text-decoration-none text-primary mx-2">{{ __('layouts/app.about') }}</a>
                    <a href="#" class="text-decoration-none text-primary mx-2">{{__('layouts/app.contact')}}</a>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
