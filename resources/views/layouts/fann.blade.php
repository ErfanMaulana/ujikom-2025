<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>FannRental - @yield('title', 'Sistem Rental Motor')</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --primary-color: #3b82f6;
            --primary-dark: #2563eb;
            --secondary-color: #64748b;
            --success-color: #10b981;
            --danger-color: #ef4444;
            --warning-color: #f59e0b;
            --info-color: #06b6d4;
            --dark-color: #1e293b;
            --light-color: #f8fafc;
            --border-color: #e2e8f0;
            --sidebar-width: 280px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--light-color);
            color: #334155;
            line-height: 1.6;
        }
        
        /* Sidebar Styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            width: var(--sidebar-width);
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            color: white;
            z-index: 1000;
            box-shadow: 4px 0 20px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
        }
        
        .sidebar::-webkit-scrollbar {
            width: 4px;
        }
        
        .sidebar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }
        
        .sidebar-brand {
            padding: 2rem 1.5rem;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .sidebar-brand h4 {
            font-weight: 700;
            font-size: 1.5rem;
            margin: 0;
            letter-spacing: -0.025em;
        }
        
        .sidebar-brand .subtitle {
            font-size: 0.875rem;
            opacity: 0.8;
            font-weight: 400;
            margin-top: 0.25rem;
        }
        
        .sidebar-menu {
            padding: 1.5rem 0;
        }
        
        .menu-label {
            padding: 0 1.5rem 0.5rem;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            opacity: 0.7;
        }
        
        .menu-item {
            margin: 0.25rem 1rem;
        }
        
        .menu-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: rgba(255, 255, 255, 0.9);
            text-decoration: none;
            border-radius: 0.75rem;
            font-weight: 500;
            transition: all 0.2s ease;
            position: relative;
        }
        
        .menu-link:hover {
            background: rgba(255, 255, 255, 0.1);
            color: white;
            transform: translateX(4px);
        }
        
        .menu-link.active {
            background: rgba(255, 255, 255, 0.15);
            color: white;
            font-weight: 600;
        }
        
        .menu-link i {
            width: 20px;
            margin-right: 0.75rem;
            font-size: 1.1rem;
        }
        
        .sidebar-user {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 1.5rem;
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            background: rgba(0, 0, 0, 0.1);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.75rem;
            font-size: 1.2rem;
        }
        
        .user-details h6 {
            margin: 0;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-details small {
            opacity: 0.7;
            font-size: 0.75rem;
        }
        
        .logout-btn {
            width: 100%;
            padding: 0.5rem;
            background: rgba(239, 68, 68, 0.2);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: white;
            border-radius: 0.5rem;
            font-size: 0.875rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .logout-btn:hover {
            background: var(--danger-color);
            border-color: var(--danger-color);
            color: white;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            min-height: 100vh;
            padding: 2rem;
        }
        
        .content-header {
            margin-bottom: 2rem;
        }
        
        .content-header h1 {
            font-size: 2rem;
            font-weight: 700;
            color: var(--dark-color);
            margin: 0;
            letter-spacing: -0.025em;
        }
        
        .content-header p {
            color: var(--secondary-color);
            margin: 0.5rem 0 0;
            font-size: 1rem;
        }
        
        /* Cards */
        .card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s ease;
        }
        
        .card:hover {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
            transform: translateY(-2px);
        }
        
        .card-header {
            background: white;
            border-bottom: 1px solid var(--border-color);
            border-radius: 1rem 1rem 0 0 !important;
            padding: 1.5rem;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        .stat-card {
            text-align: center;
            padding: 2rem 1.5rem;
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem;
            font-size: 1.5rem;
        }
        
        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin: 0;
            color: var(--dark-color);
        }
        
        .stat-label {
            color: var(--secondary-color);
            font-weight: 500;
            margin: 0;
        }
        
        /* Buttons */
        .btn {
            border-radius: 0.75rem;
            font-weight: 500;
            padding: 0.75rem 1.5rem;
            border: none;
            transition: all 0.2s ease;
        }
        
        .btn-primary {
            background: var(--primary-color);
            color: white;
        }
        
        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
        }
        
        .btn-outline-primary {
            border: 2px solid var(--primary-color);
            color: var(--primary-color);
            background: transparent;
        }
        
        .btn-outline-primary:hover {
            background: var(--primary-color);
            color: white;
            transform: translateY(-1px);
        }
        
        /* Tables */
        .table {
            margin: 0;
        }
        
        .table thead th {
            border-top: none;
            border-bottom: 2px solid var(--border-color);
            font-weight: 600;
            color: var(--dark-color);
            background: var(--light-color);
            padding: 1rem;
        }
        
        .table tbody td {
            padding: 1rem;
            border-bottom: 1px solid var(--border-color);
            vertical-align: middle;
        }
        
        .table-hover tbody tr:hover {
            background: rgba(59, 130, 246, 0.05);
        }
        
        /* Badges */
        .badge {
            border-radius: 0.5rem;
            font-weight: 500;
            padding: 0.375rem 0.75rem;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem 2rem;
        }
        
        .empty-state i {
            font-size: 4rem;
            color: var(--secondary-color);
            opacity: 0.5;
            margin-bottom: 1rem;
        }
        
        .empty-state h6 {
            color: var(--secondary-color);
            font-weight: 600;
            margin-bottom: 0.5rem;
        }
        
        .empty-state p {
            color: var(--secondary-color);
            opacity: 0.8;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
                padding: 1rem;
            }
            
            .content-header h1 {
                font-size: 1.5rem;
            }
            
            .stat-card {
                padding: 1.5rem 1rem;
            }
            
            .stat-number {
                font-size: 2rem;
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Brand -->
        <div class="sidebar-brand">
            <h4>FannRental</h4>
            <div class="subtitle">Rental Motor Terpercaya</div>
        </div>
        
        <!-- Menu -->
        <div class="sidebar-menu">
            @if(Auth::user()->role === 'pemilik')
                <div class="menu-label">Menu Utama</div>
                <div class="menu-item">
                    <a href="{{ route('pemilik.dashboard') }}" class="menu-link {{ request()->routeIs('pemilik.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('pemilik.motors') }}" class="menu-link {{ request()->routeIs('pemilik.motors*') ? 'active' : '' }}">
                        <i class="bi bi-motorcycle"></i>
                        Motor Saya
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('pemilik.bookings') }}" class="menu-link {{ request()->routeIs('pemilik.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        Pemesanan
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('pemilik.revenue.report') }}" class="menu-link {{ request()->routeIs('pemilik.revenue*') ? 'active' : '' }}">
                        <i class="bi bi-graph-up"></i>
                        Pendapatan
                    </a>
                </div>
            @elseif(Auth::user()->role === 'penyewa')
                <div class="menu-label">Menu Utama</div>
                <div class="menu-item">
                    <a href="{{ route('penyewa.dashboard') }}" class="menu-link {{ request()->routeIs('penyewa.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('penyewa.motors') }}" class="menu-link {{ request()->routeIs('penyewa.motors*') ? 'active' : '' }}">
                        <i class="bi bi-search"></i>
                        Cari Motor
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('penyewa.bookings') }}" class="menu-link {{ request()->routeIs('penyewa.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        Pemesanan Saya
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('penyewa.payment.history') }}" class="menu-link {{ request()->routeIs('penyewa.payment*') ? 'active' : '' }}">
                        <i class="bi bi-credit-card"></i>
                        Riwayat Pembayaran
                    </a>
                </div>
            @elseif(Auth::user()->role === 'admin')
                <div class="menu-label">Menu Utama</div>
                <div class="menu-item">
                    <a href="{{ route('admin.dashboard') }}" class="menu-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="bi bi-speedometer2"></i>
                        Dashboard
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.users') }}" class="menu-link {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                        <i class="bi bi-people"></i>
                        Kelola Pengguna
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.motors') }}" class="menu-link {{ request()->routeIs('admin.motors*') ? 'active' : '' }}">
                        <i class="bi bi-motorcycle"></i>
                        Verifikasi Motor
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.bookings') }}" class="menu-link {{ request()->routeIs('admin.bookings*') ? 'active' : '' }}">
                        <i class="bi bi-calendar-check"></i>
                        Kelola Pemesanan
                    </a>
                </div>
                <div class="menu-item">
                    <a href="{{ route('admin.financial-report') }}" class="menu-link {{ request()->routeIs('admin.financial*') ? 'active' : '' }}">
                        <i class="bi bi-bar-chart"></i>
                        Laporan Keuangan
                    </a>
                </div>
            @endif
            
            <div class="menu-label" style="margin-top: 2rem;">Akun</div>
            <div class="menu-item">
                <a href="{{ route('profile.edit') }}" class="menu-link {{ request()->routeIs('profile*') ? 'active' : '' }}">
                    <i class="bi bi-person"></i>
                    Profile
                </a>
            </div>
        </div>
        
        <!-- User Info -->
        <div class="sidebar-user">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="bi bi-person-circle"></i>
                </div>
                <div class="user-details">
                    <h6>{{ Auth::user()->name }}</h6>
                    <small>{{ ucfirst(Auth::user()->role) }}</small>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right me-2"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        @yield('content')
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>