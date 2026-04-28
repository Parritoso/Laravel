<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>NexusGear - @yield('title', __('layouts/app.title'))</title>

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
                        <li class="nav-item"><a class="nav-link" href="#">{{__('layouts/app.favorites')}}</a></li>
                    </ul>

                    <ul class="navbar-nav ms-auto align-items-center">
                        <li class="nav-item me-3">
                            <a href="#" class="position-relative text-dark text-decoration-none">
                                <i class="bi bi-cart3 fs-5"></i>
                                <span
                                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-primary">0</span>
                            </a>
                        </li>
                        @guest
                        <li class="nav-item"><a class="nav-link" href="{{ route('login') }}">Login</a></li>
                        @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">{{__('layouts/app.profile')}}</a></li>
                                <li><a class="dropdown-item" href="#">{{__('layouts/app.orders')}}</a></li>
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
            @yield('content')
        </main>

        <footer class="bg-white border-top py-5 mt-auto">
            <div class="container text-center text-muted">
                <p>&copy; {{ date('Y') }} {{__('layouts/app.footer')}}</p>
                <div class="small">
                    <a href="#" class="text-decoration-none text-primary mx-2">{{__('layouts/app.terms')}}</a>
                    <a href="#" class="text-decoration-none text-primary mx-2">{{__('layouts/app.contact')}}</a>
                </div>
            </div>
        </footer>
    </div>
</body>

</html>
