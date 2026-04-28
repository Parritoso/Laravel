<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin NexusGear - @yield('title')</title>
    @vite(['resources/css/app.scss', 'resources/js/app.js'])
    <style>
        :root { --admin-sidebar-width: 260px; }
        body { background-color: #f4f7f6; }
        .sidebar { 
            width: var(--admin-sidebar-width); 
            height: 100vh; 
            position: fixed; 
            background: #2d3748;
            color: white;
        }
        .main-content { 
            margin-left: var(--admin-sidebar-width); 
            padding: 30px; 
        }
        .nav-link-admin {
            color: rgba(255,255,255,0.7);
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            transition: all 0.3s;
        }
        .nav-link-admin:hover, .nav-link-admin.active {
            background: rgba(79, 209, 197, 0.1);
            color: #4FD1C5;
            border-left: 4px solid #4FD1C5;
        }
    </style>
</head>
<body>
    <div class="sidebar d-flex flex-column shadow">
        <div class="p-4">
            <h4 class="text-primary fw-bold"><i class="bi bi-cpu"></i> NexusGear</h4>
            <small class="text-muted">Panel de Control</small>
        </div>
        
        <nav class="mt-2">
            <a href="{{ route('admin.dashboard') }}" class="nav-link-admin {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>
            <a href="{{ route('admin.products.index') }}" class="nav-link-admin {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
                <i class="bi bi-box-seam me-2"></i> Productos
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link-admin {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
                <i class="bi bi-cart-check me-2"></i> Pedidos
            </a>
            <hr class="mx-3 opacity-25">
            <a href="{{ url('/') }}" class="nav-link-admin">
                <i class="bi bi-house me-2"></i> Ir a la tienda
            </a>
        </nav>
    </div>

    <main class="main-content">
        <header class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold">@yield('page-title')</h2>
            <div class="dropdown">
                <button class="btn btn-white shadow-sm dropdown-toggle" data-bs-toggle="dropdown">
                    {{ Auth::user()->name }}
                </button>
                <ul class="dropdown-menu shadow border-0">
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#"
                           onclick="event.preventDefault(); document.getElementById('admin-logout-form').submit();">
                            Salir
                        </a>
                        <form id="admin-logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
        </header>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Cerrar"></button>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
