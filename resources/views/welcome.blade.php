@extends('layouts.welcome')

@section('title', 'FannRental - Platform Rental Motor Terpercaya')

@section('content')
<!-- Hero Banner Section -->
<div class="hero-banner">
    <div class="container">
        <div class="row align-items-center min-vh-80">
            <div class="col-lg-6">
                <div class="hero-content">
                    <h1 class="display-4 fw-bold text-white mb-3">
                        Rental Motor <span class="text-warning">Terpercaya</span> di Indonesia
                    </h1>
                    <p class="lead text-white-75 mb-4">
                        Nikmati pengalaman berkendara yang aman dan nyaman dengan koleksi motor terlengkap kami. 
                        Dari motor matic untuk dalam kota hingga motor sport untuk perjalanan jauh.
                    </p>
                    @guest
                        <div class="d-flex gap-3 mb-4">
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-4 py-3 fw-semibold">
                                <i class="bi bi-person-plus me-2"></i>Mulai Sekarang
                            </a>
                            <a href="#kategori-motor" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="bi bi-search me-2"></i>Lihat Motor
                            </a>
                        </div>
                    @else
                        <div class="d-flex gap-3 mb-4">
                            @if(auth()->user()->role === 'admin')
                                <a href="{{ route('admin.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Admin
                                </a>
                            @elseif(auth()->user()->role === 'pemilik')
                                <a href="{{ route('pemilik.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Pemilik
                                </a>
                            @else
                                <a href="{{ route('penyewa.dashboard') }}" class="btn btn-warning btn-lg px-4 py-3">
                                    <i class="bi bi-speedometer2 me-2"></i>Dashboard Penyewa
                                </a>
                            @endif
                        </div>
                    @endguest
                </div>
            </div>
            <div class="col-lg-6">
                <div class="hero-image">
                    <div class="position-relative">
                        <div class="promo-poster">
                            <div class="poster-bg p-4 rounded-4 shadow-lg">
                                <div class="text-center mb-3">
                                    <h2 class="fw-bold text-primary mb-2">🏍️ PROMO SPESIAL!</h2>
                                    <div class="badge bg-danger fs-6 px-3 py-2 mb-3">HEMAT HINGGA 30%</div>
                                </div>
                                
                                <div class="row g-3">
                                    <div class="col-6">
                                        <div class="promo-item bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-bicycle text-primary mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="fw-semibold">Motor Matic</h6>
                                            <small class="text-muted">Mulai dari</small>
                                            <div class="price">
                                                <span class="text-decoration-line-through text-muted">Rp 70K</span>
                                                <strong class="text-success d-block">Rp 50K/hari</strong>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-6">
                                        <div class="promo-item bg-light p-3 rounded-3 text-center">
                                            <i class="bi bi-bicycle text-success mb-2" style="font-size: 2rem;"></i>
                                            <h6 class="fw-semibold">Motor Sport</h6>
                                            <small class="text-muted">Mulai dari</small>
                                            <div class="price">
                                                <span class="text-decoration-line-through text-muted">Rp 100K</span>
                                                <strong class="text-success d-block">Rp 75K/hari</strong>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-center mt-3">
                                    <small class="text-muted">
                                        <i class="bi bi-clock me-1"></i>Promo berlaku sampai akhir bulan!
                                    </small>
                                </div>
                                
                                <div class="d-flex gap-2 mt-3">
                                    @guest
                                        <a href="{{ route('register') }}" class="btn btn-primary flex-fill">
                                            <i class="bi bi-person-plus me-1"></i>Daftar Sekarang
                                        </a>
                                    @else
                                        <a href="#kategori-motor" class="btn btn-primary flex-fill">
                                            <i class="bi bi-search me-1"></i>Pilih Motor
                                        </a>
                                    @endguest
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Mengapa Memilih FannRental?</h2>
                <p class="text-muted mb-5">Kami berkomitmen memberikan layanan rental motor terbaik untuk Anda</p>
            </div>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Aman & Terpercaya</h5>
                    <p class="text-muted">Semua motor telah melalui verifikasi ketat dari admin kami. Dokumen lengkap dan kondisi prima.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-currency-dollar text-success" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Harga Terjangkau</h5>
                    <p class="text-muted">Berbagai pilihan motor dengan harga kompetitif. Dapatkan promo menarik setiap bulannya.</p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="feature-card text-center p-4 h-100">
                    <div class="feature-icon mb-3">
                        <i class="bi bi-clock text-warning" style="font-size: 3rem;"></i>
                    </div>
                    <h5 class="fw-semibold mb-3">Proses Cepat</h5>
                    <p class="text-muted">Booking mudah dan konfirmasi langsung. Layanan 24/7 siap membantu kapan saja.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Motor Categories Section -->
<section id="kategori-motor" class="py-5" style="background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);">
    <div class="container">
        <div class="row mb-5">
            <div class="col-12 text-center">
                <h2 class="fw-bold mb-3">Kategori Motor Terbaru</h2>
                <p class="text-muted mb-5">Pilih motor sesuai kebutuhan perjalanan Anda</p>
            </div>
        </div>
        
        <div class="row g-4 justify-content-center">
            <!-- Motor Matic -->
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="motor-category-card">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden">
                        <div class="card-header bg-gradient-primary text-white text-center py-4">
                            <div class="category-badge">
                                <i class="bi bi-star-fill me-1"></i>POPULER
                            </div>
                            <h4 class="fw-bold mb-1">Motor Matic</h4>
                            <p class="mb-0 opacity-75">Perfect untuk dalam kota</p>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <div class="motor-icon">
                                    <i class="bi bi-bicycle text-primary" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Honda Vario, Beat, Scoopy</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Yamaha Nmax, Mio, Soul</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Irit BBM & mudah dikendarai</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Ideal untuk pemula</li>
                            </ul>
                            
                            <div class="price-section text-center mt-auto">
                                <div class="old-price">
                                    <small class="text-muted text-decoration-line-through">Rp 70.000</small>
                                </div>
                                <div class="current-price">
                                    <h3 class="fw-bold text-primary mb-1">Rp 50.000</h3>
                                    <small class="text-muted">per hari</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Motor Sport -->
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="motor-category-card">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden">
                        <div class="card-header bg-gradient-success text-white text-center py-4">
                            <div class="category-badge">
                                <i class="bi bi-lightning-fill me-1"></i>PERFORMA
                            </div>
                            <h4 class="fw-bold mb-1">Motor Sport</h4>
                            <p class="mb-0 opacity-75">Untuk perjalanan jauh</p>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <div class="motor-icon">
                                    <i class="bi bi-bicycle text-success" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Honda CBR, CB150R, Sonic</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Yamaha R15, Vixion, Fz</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Tenaga besar & handling bagus</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Cocok untuk touring</li>
                            </ul>
                            
                            <div class="price-section text-center mt-auto">
                                <div class="old-price">
                                    <small class="text-muted text-decoration-line-through">Rp 100.000</small>
                                </div>
                                <div class="current-price">
                                    <h3 class="fw-bold text-success mb-1">Rp 75.000</h3>
                                    <small class="text-muted">per hari</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Motor Premium -->
            <div class="col-lg-4 col-md-6 col-sm-8">
                <div class="motor-category-card">
                    <div class="card border-0 shadow-lg h-100 position-relative overflow-hidden">
                        <div class="card-header bg-gradient-warning text-white text-center py-4">
                            <div class="category-badge">
                                <i class="bi bi-gem me-1"></i>PREMIUM
                            </div>
                            <h4 class="fw-bold mb-1">Motor Premium</h4>
                            <p class="mb-0 opacity-75">Kelas atas & mewah</p>
                        </div>
                        <div class="card-body p-4 d-flex flex-column">
                            <div class="text-center mb-4">
                                <div class="motor-icon">
                                    <i class="bi bi-bicycle text-warning" style="font-size: 4rem;"></i>
                                </div>
                            </div>
                            
                            <ul class="list-unstyled mb-4 flex-grow-1">
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Honda PCX, Forza, ADV</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Yamaha Aerox, Lexi, Xmax</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Fitur lengkap & modern</li>
                                <li class="mb-2"><i class="bi bi-check2 text-success me-2"></i>Kenyamanan maksimal</li>
                            </ul>
                            
                            <div class="price-section text-center mt-auto">
                                <div class="old-price">
                                    <small class="text-muted text-decoration-line-through">Rp 130.000</small>
                                </div>
                                <div class="current-price">
                                    <h3 class="fw-bold text-warning mb-1">Rp 100.000</h3>
                                    <small class="text-muted">per hari</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection