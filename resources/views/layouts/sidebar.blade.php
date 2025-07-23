<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard CRM')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s;
            z-index: 1000;
        }
        .sidebar .nav-link {
            color: rgba(255,255,255,0.8);
            padding: 1rem 1.5rem;
            border-radius: 0.5rem;
            margin: 0.2rem 0;
            transition: all 0.3s;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255,255,255,0.1);
            backdrop-filter: blur(10px);
        }
        .sidebar .nav-link i {
            margin-right: 0.5rem;
            width: 20px;
            text-align: center;
        }
        .main-content {
            margin-left: 0;
            transition: all 0.3s;
        }
        .sidebar-brand {
            font-size: 1.2rem;
            font-weight: bold;
            color: white;
            text-decoration: none;
            padding: 1.5rem;
            display: block;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .sidebar-brand:hover {
            color: white;
        }
        .content-header {
            background: white;
            padding: 1.5rem 2rem;
            border-bottom: 1px solid #e9ecef;
            margin-bottom: 2rem;
        }
        .stats-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        .stats-card:hover {
            transform: translateY(-2px);
        }
        .sidebar-toggle {
            display: none;
            position: fixed;
            top: 1rem;
            left: 1rem;
            z-index: 1001;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                margin-left: -250px;
                width: 250px;
            }
            .sidebar.show {
                margin-left: 0;
            }
            .sidebar-toggle {
                display: block;
            }
            .main-content {
                margin-left: 0;
            }
            .content-header {
                padding-left: 4rem;
            }
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <!-- Botón toggle para mobile -->
            <button class="sidebar-toggle" id="sidebar-toggle">
                <i class="bi bi-list"></i>
            </button>

            <!-- Sidebar -->
            <nav class="col-md-3 col-lg-2 sidebar position-fixed" id="sidebar">
                <a href="{{ route('dashboard') }}" class="sidebar-brand">
                    <i class="bi bi-speedometer2"></i>
                    Dashboard CRM
                </a>
                
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" 
                               href="{{ route('dashboard') }}">
                                <i class="bi bi-house-door"></i>
                                Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('clientes.*') ? 'active' : '' }}" 
                               href="{{ route('clientes.index') }}">
                                <i class="bi bi-people"></i>
                                Clientes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('productos.*') ? 'active' : '' }}" 
                               href="{{ route('productos.index') }}">
                                <i class="bi bi-box-seam"></i>
                                Productos
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('inventarios.*') ? 'active' : '' }}" 
                               href="{{ route('inventarios.index') }}">
                                <i class="bi bi-clipboard-data"></i>
                                Inventario
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('facturas.*') ? 'active' : '' }}" 
                               href="{{ route('facturas.index') }}">
                                <i class="bi bi-file-earmark-text"></i>
                                Facturas
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <!-- Main content -->
            <main class="col-md-9 ms-sm-auto col-lg-10 px-0 main-content">
                <!-- Content Header -->
                <div class="content-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h1 class="h3 mb-0">@yield('page-title', 'Dashboard')</h1>
                            <p class="text-muted mb-0">@yield('page-description', 'Panel de control principal')</p>
                        </div>
                        <div>
                            @yield('page-actions')
                        </div>
                    </div>
                </div>

                <!-- Page Content -->
                <div class="container-fluid px-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Toggle sidebar en mobile
        document.getElementById('sidebar-toggle').addEventListener('click', function() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('show');
        });

        // Cerrar sidebar al hacer click fuera en mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.getElementById('sidebar-toggle');
            
            if (window.innerWidth <= 768 && 
                !sidebar.contains(event.target) && 
                !toggle.contains(event.target) &&
                sidebar.classList.contains('show')) {
                sidebar.classList.remove('show');
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
