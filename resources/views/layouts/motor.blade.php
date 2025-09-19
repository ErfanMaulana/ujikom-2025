<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'Laravel') }} - @yield('title', 'Sistem Penyewaan Motor')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #6c757d;
            --success-color: #198754;
            --danger-color: #dc3545;
            --warning-color: #ffc107;
            --info-color: #0dcaf0;
        }
        
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .sidebar {
            min-height: calc(100vh - 56px);
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.3s ease;
        }
        
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.375rem;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: box-shadow 0.15s ease-in-out;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
        }
        
        .table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            font-weight: 600;
        }
        
        .badge {
            font-size: 0.75rem;
        }
        
        .motor-card {
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .motor-card img {
            height: 200px;
            object-fit: cover;
        }
        
        .status-available { background-color: var(--success-color); }
        .status-rented { background-color: var(--danger-color); }
        .status-maintenance { background-color: var(--warning-color); color: #000; }
        .status-pending { background-color: var(--info-color); color: #000; }
    </style>
    
    @stack('styles')
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('dashboard') }}">
                <i class="bi bi-motorcycle me-2"></i>
                RentMotor
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('dashboard') }}">
                            <i class="bi bi-house-door me-1"></i>Dashboard
                        </a>
                    </li>
                    @auth
                        @if(auth()->user()->isPenyewa())
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-search me-1"></i>Cari Motor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bookmark me-1"></i>Booking Saya
                                </a>
                            </li>
                        @elseif(auth()->user()->isPemilik())
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-plus-circle me-1"></i>Tambah Motor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-motorcycle me-1"></i>Motor Saya
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-graph-up me-1"></i>Laporan
                                </a>
                            </li>
                        @elseif(auth()->user()->isAdmin())
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi Motor
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-card-checklist me-1"></i>Kelola Booking
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-bar-chart me-1"></i>Laporan Admin
                                </a>
                            </li>
                        @endif
                    @endauth
                </ul>
                
                <ul class="navbar-nav">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>
                                {{ auth()->user()->name }}
                                <span class="badge bg-light text-primary ms-1">{{ ucfirst(auth()->user()->role) }}</span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Profile</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="dropdown-item">
                                            <i class="bi bi-box-arrow-right me-2"></i>Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @endguest
                </ul>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container-fluid">
        <div class="row">
            @auth
                @if(!request()->routeIs('dashboard'))
                <!-- Sidebar -->
                <nav class="col-md-3 col-lg-2 d-md-block sidebar collapse">
                    <div class="position-sticky pt-3">
                        <ul class="nav flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="bi bi-house-door me-2"></i>Dashboard
                                </a>
                            </li>
                            
                            @if(auth()->user()->isPenyewa())
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-search me-2"></i>Cari Motor
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-bookmark me-2"></i>Booking Saya
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-clock-history me-2"></i>Riwayat Sewa
                                    </a>
                                </li>
                            @elseif(auth()->user()->isPemilik())
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-plus-circle me-2"></i>Tambah Motor
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-motorcycle me-2"></i>Motor Saya
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-graph-up me-2"></i>Pendapatan
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-earmark-text me-2"></i>Laporan
                                    </a>
                                </li>
                            @elseif(auth()->user()->isAdmin())
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-check-circle me-2"></i>Verifikasi Motor
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-card-checklist me-2"></i>Kelola Booking
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-people me-2"></i>Kelola Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-bar-chart me-2"></i>Laporan Admin
                                    </a>
                                </li>
                            @endif
                            
                            <hr class="text-white">
                            <li class="nav-item">
                                <a class="nav-link" href="#">
                                    <i class="bi bi-person me-2"></i>Profile
                                </a>
                            </li>
                        </ul>
                    </div>
                </nav>
                @endif
                
                <!-- Main Content Area -->
                <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            @else
                <main class="col-12">
            @endauth
                <!-- Page Header -->
                @if(isset($header))
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                        {{ $header }}
                    </div>
                @endif
                
                <!-- Flash Messages -->
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>{{ session('warning') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        <i class="bi bi-info-circle me-2"></i>{{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                <!-- Page Content -->
                @yield('content')
            </main>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-dark text-light py-4 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-md-6">
                    <h5><i class="bi bi-motorcycle me-2"></i>RentMotor</h5>
                    <p class="mb-0">Sistem Penyewaan Motor - Ujikom 2025</p>
                    <p class="mb-0">SMKN 1 Ciamis - XII PPLG</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <p class="mb-0">Developed by <strong>Erfan Eka Maulana</strong></p>
                    <p class="mb-0">NIS: 232410549 - NISN: 0088223031</p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    @stack('scripts')
</body>
</html>