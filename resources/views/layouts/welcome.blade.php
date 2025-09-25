<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'FannRental - Rental Motor Terpercaya')</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css" rel="stylesheet">
    
    <!-- Google Fonts - Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        :root {
            --fann-primary: #2563eb;
            --fann-secondary: #64748b;
            --fann-success: #059669;
            --fann-warning: #d97706;
            --fann-danger: #dc2626;
            --fann-dark: #1e293b;
            --fann-light: #f8fafc;
        }

        * {
            font-family: 'Inter', sans-serif;
        }

        body {
            background-color: var(--fann-light);
            color: var(--fann-dark);
            padding-top: 76px; /* Space for fixed navbar */
        }

        .btn-primary {
            background-color: var(--fann-primary);
            border-color: var(--fann-primary);
        }

        .btn-primary:hover {
            background-color: #1d4ed8;
            border-color: #1d4ed8;
        }

        .text-primary {
            color: var(--fann-primary) !important;
        }

        .bg-primary {
            background-color: var(--fann-primary) !important;
        }

        /* Fixed Navbar Styles */
        .navbar {
            position: fixed !important;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1030;
            backdrop-filter: blur(10px);
            background-color: rgba(255, 255, 255, 0.95) !important;
            transition: all 0.3s ease;
            box-shadow: 0 2px 20px rgba(0, 0, 0, 0.1);
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--fann-primary) !important;
        }

        .navbar-scrolled {
            background-color: rgba(255, 255, 255, 0.98) !important;
            box-shadow: 0 2px 30px rgba(0, 0, 0, 0.15);
        }

        .feature-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }

        .category-card {
            border: 2px solid transparent;
            transition: all 0.3s ease;
        }

        .category-card:hover {
            border-color: var(--fann-primary);
            transform: translateY(-2px);
        }

        /* Hero Banner Styles */
        .hero-banner {
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #6366f1 100%);
            min-height: 100vh;
            position: relative;
            overflow: hidden;
            padding-top: 2rem; /* Additional space for fixed navbar */
        }

        .hero-banner::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grid" width="10" height="10" patternUnits="userSpaceOnUse"><path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        }

        .min-vh-80 {
            min-height: 80vh;
        }

        .text-white-75 {
            color: rgba(255, 255, 255, 0.85) !important;
        }

        .stat-item h3 {
            font-size: 2rem;
        }

        /* Promo Poster Styles */
        .promo-poster {
            position: relative;
            z-index: 2;
        }

        .poster-bg {
            background: white;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            border: 2px solid #ffeaea;
        }

        .promo-item {
            transition: all 0.3s ease;
        }

        .promo-item:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.1);
        }

        /* Motor Category Card Styles */
        .motor-category-card {
            margin-bottom: 2rem;
        }
        
        .motor-category-card .card {
            transition: all 0.4s ease;
            border-radius: 16px;
            height: 100%;
            min-height: 500px;
        }

        .motor-category-card .card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
        }

        .category-badge {
            position: absolute;
            top: -10px;
            right: 20px;
            background: rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.75rem;
            font-weight: 600;
        }

        .bg-gradient-primary {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        }

        .bg-gradient-success {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        }

        .bg-gradient-warning {
            background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
        }

        .motor-icon {
            height: 100px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .price-section {
            background: #f8fafc;
            margin: -1rem -1.5rem -1.5rem -1.5rem;
            padding: 1.5rem;
            border-radius: 0 0 16px 16px;
        }

        /* Footer Styles */
        .social-links a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.1);
            transition: all 0.3s ease;
        }

        .social-links a:hover {
            background: var(--fann-warning);
            transform: translateY(-2px);
        }

        .contact-info a:hover {
            color: var(--fann-warning) !important;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .hero-banner {
                min-height: auto;
                padding: 60px 0;
            }

            .display-4 {
                font-size: 2rem;
            }

            .stat-item h3 {
                font-size: 1.5rem;
            }

            .motor-category-card {
                margin-bottom: 1.5rem;
            }
            
            .motor-category-card .card {
                min-height: auto;
            }

            .motor-category-card .card:hover {
                transform: translateY(-4px);
            }
        }
        
        @media (max-width: 576px) {
            .motor-category-card .card {
                min-height: 450px;
            }
        }

        /* Animation */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .hero-content {
            animation: fadeInUp 1s ease-out;
        }

        .promo-poster {
            animation: fadeInUp 1s ease-out 0.3s both;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ url('/') }}">
                <i class="bi bi-motorcycle me-2"></i>
                <span class="fw-bold">FannRental</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @guest
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Daftar
                            </a>
                        </li>
                    @else
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle me-1"></i>{{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                @if(Auth::user()->role === 'admin')
                                    <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a></li>
                                @elseif(Auth::user()->role === 'pemilik')
                                    <li><a class="dropdown-item" href="{{ route('pemilik.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a></li>
                                @else
                                    <li><a class="dropdown-item" href="{{ route('penyewa.dashboard') }}">
                                        <i class="bi bi-speedometer2 me-2"></i>Dashboard
                                    </a></li>
                                @endif
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
    <main>
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center mb-3">
                        <i class="bi bi-motorcycle me-2 text-warning" style="font-size: 1.5rem;"></i>
                        <span class="fw-bold h4 mb-0">FannRental</span>
                    </div>
                    <p class="text-light opacity-75 mb-3">
                        Platform rental motor terpercaya yang menghadirkan solusi transportasi mudah, aman, dan terjangkau untuk kebutuhan mobilitas Anda.
                    </p>
                    <div class="social-links">
                        <a href="#" class="text-light me-3"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-light me-3"><i class="bi bi-twitter"></i></a>
                        <a href="#" class="text-light"><i class="bi bi-whatsapp"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-semibold mb-3 text-warning">Layanan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Rental Motor Harian</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Rental Motor Mingguan</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Rental Motor Bulanan</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Paket Tour Motor</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-2 col-md-6">
                    <h5 class="fw-semibold mb-3 text-warning">Bantuan</h5>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">FAQ</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Cara Booking</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Syarat & Ketentuan</a></li>
                        <li class="mb-2"><a href="#" class="text-light opacity-75 text-decoration-none">Kebijakan Privasi</a></li>
                    </ul>
                </div>
                
                <div class="col-lg-3 col-md-6">
                    <h5 class="fw-semibold mb-3 text-warning">Kontak Saya</h5>
                    <div class="contact-info">
                        
                        <div class="mb-3">
                            <i class="bi bi-envelope text-warning me-2"></i>
                            <a href="mailto:erf4nmaulana@gmail.com" class="text-light opacity-75 text-decoration-none">
                                erf4nmaulana@gmail.com
                            </a>
                        </div>
                        
                        <div class="mb-3">
                            <i class="bi bi-telephone text-warning me-2"></i>
                            <a href="tel:+6283820921722" class="text-light opacity-75 text-decoration-none">
                                +62 838-2092-1722
                            </a>
                        </div>
                        
                        <div class="mb-3">
                            <i class="bi bi-whatsapp text-warning me-2"></i>
                            <a href="https://wa.me/6283820921722" target="_blank" class="text-light opacity-75 text-decoration-none">
                                WhatsApp
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            
            <hr class="my-4 opacity-25">
            
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="text-light opacity-75 mb-0">
                        &copy; Project by: Erfan Eka Maulana
                    </p>
                </div>
                
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>