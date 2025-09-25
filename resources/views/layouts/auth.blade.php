<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>FannRental - @yield('title', 'Login')</title>
    
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
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #334155;
            line-height: 1.6;
        }
        
        .auth-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.15);
            padding: 0;
            width: 100%;
            max-width: 1100px;
            margin: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .auth-content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            min-height: 550px;
        }
        
        .auth-left {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            color: white;
            position: relative;
            overflow: hidden;
        }
        
        .auth-left::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.05'%3E%3Ccircle cx='7' cy='7' r='7'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E") repeat;
            animation: float 20s infinite linear;
        }
        
        @keyframes float {
            0% { transform: translate(0, 0) rotate(0deg); }
            100% { transform: translate(-60px, -60px) rotate(360deg); }
        }
        
        .auth-logo {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            z-index: 2;
            position: relative;
        }
        
        .auth-tagline {
            font-size: 1.2rem;
            opacity: 0.9;
            margin-bottom: 2rem;
            z-index: 2;
            position: relative;
        }
        
        .auth-features {
            list-style: none;
            text-align: left;
            z-index: 2;
            position: relative;
        }
        
        .auth-features li {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
            opacity: 0.9;
        }
        
        .auth-features i {
            margin-right: 12px;
            font-size: 1.2rem;
            color: #fbbf24;
        }
        
        .auth-right {
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }
        
        .auth-header {
            text-align: center;
            margin-bottom: 2rem;
        }
        
        .auth-title {
            font-size: 2rem;
            font-weight: 600;
            color: var(--dark-color);
            margin-bottom: 0.5rem;
        }
        
        .auth-subtitle {
            color: var(--secondary-color);
            font-size: 1rem;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark-color);
        }
        
        .form-control {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #ffffff;
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary-color);
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        
        .form-control.is-invalid {
            border-color: var(--danger-color);
        }
        
        .invalid-feedback {
            display: block;
            color: var(--danger-color);
            font-size: 0.875rem;
            margin-top: 0.5rem;
        }
        
        .password-toggle {
            position: relative;
        }
        
        .password-toggle .form-control {
            padding-right: 45px;
        }
        
        .password-toggle-btn {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: var(--secondary-color);
            cursor: pointer;
            padding: 4px;
            border-radius: 4px;
            transition: color 0.3s ease;
        }
        
        .password-toggle-btn:hover {
            color: var(--primary-color);
        }
        
        .btn-primary {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            border: none;
            padding: 14px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 1rem;
            width: 100%;
            color: white;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.3);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(59, 130, 246, 0.4);
        }
        
        .btn-primary:disabled {
            opacity: 0.6;
            transform: none;
            cursor: not-allowed;
        }
        
        .form-check {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .form-check-input {
            margin-right: 8px;
            accent-color: var(--primary-color);
        }
        
        .auth-links {
            text-align: center;
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid var(--border-color);
        }
        
        .auth-link {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s ease;
        }
        
        .auth-link:hover {
            color: var(--primary-dark);
            text-decoration: underline;
        }
        
        .alert {
            padding: 12px 16px;
            border-radius: 12px;
            margin-bottom: 1.5rem;
            border: none;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success-color);
            border-left: 4px solid var(--success-color);
        }
        
        .alert-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--danger-color);
            border-left: 4px solid var(--danger-color);
        }
        
        .role-selection {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        
        .role-card {
            padding: 16px;
            border: 2px solid var(--border-color);
            border-radius: 12px;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .role-card:hover {
            border-color: var(--primary-color);
            transform: translateY(-2px);
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.1);
        }
        
        .role-card.selected {
            border-color: var(--primary-color);
            background: rgba(59, 130, 246, 0.05);
        }
        
        .role-card i {
            font-size: 2rem;
            color: var(--primary-color);
            margin-bottom: 0.5rem;
        }
        
        .role-card h6 {
            margin: 0;
            font-weight: 600;
            color: var(--dark-color);
        }
        
        .role-card p {
            margin: 0;
            font-size: 0.875rem;
            color: var(--secondary-color);
        }
        
        /* Mobile Responsive */
        @media (max-width: 768px) {
            .auth-content {
                grid-template-columns: 1fr;
            }
            
            .auth-left {
                padding: 40px 20px;
                min-height: 300px;
            }
            
            .auth-right {
                padding: 40px 20px;
            }
            
            .auth-logo {
                font-size: 2rem;
            }
            
            .auth-tagline {
                font-size: 1rem;
            }
            
            .role-selection {
                grid-template-columns: 1fr;
            }
        }
        
        /* Loading State */
        .loading {
            position: relative;
            overflow: hidden;
        }
        
        .loading::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
            animation: loading 1.5s infinite;
        }
        
        @keyframes loading {
            0% { left: -100%; }
            100% { left: 100%; }
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-content">
            <div class="auth-left">
                <div class="auth-logo">
                    <i class="bi bi-motorcycle"></i>
                    FannRental
                </div>
                <p class="auth-tagline">
                    Platform rental motor terpercaya untuk perjalanan Anda
                </p>
                
                <ul class="auth-features">
                    <li>
                        <i class="bi bi-shield-check"></i>
                        <span>Sistem keamanan terjamin</span>
                    </li>
                    <li>
                        <i class="bi bi-clock"></i>
                        <span>Booking 24/7 online</span>
                    </li>
                    <li>
                        <i class="bi bi-geo-alt"></i>
                        <span>Tersedia di berbagai lokasi</span>
                    </li>
                    <li>
                        <i class="bi bi-wallet2"></i>
                        <span>Harga terjangkau</span>
                    </li>
                </ul>
            </div>
            
            <div class="auth-right">
                @yield('content')
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>