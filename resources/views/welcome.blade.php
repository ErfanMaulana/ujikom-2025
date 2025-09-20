@extends('layouts.welcome')

@section('title', 'Rental Motor - Selamat Datang')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="bg-primary text-white text-center py-4 rounded">
                <h1 class="display-5 fw-bold mb-2">
                    <i class="bi bi-motorcycle me-2"></i>FannRental
                </h1>
                <p class="lead mb-3">Platform terpercaya untuk menyewa dan menyewakan motor</p>
                
                @guest
                    <div class="d-flex justify-content-center gap-2">
                        <a href="{{ route('login') }}" class="btn btn-light">
                            <i class="bi bi-box-arrow-in-right me-1"></i>Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light">
                            <i class="bi bi-person-plus me-1"></i>Daftar
                        </a>
                    </div>
                @else
                    <div class="d-flex justify-content-center">
                        @if(auth()->user()->role === 'admin')
                            <a href="{{ route('admin.dashboard') }}" class="btn btn-light">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard Admin
                            </a>
                        @elseif(auth()->user()->role === 'pemilik')
                            <a href="{{ route('pemilik.dashboard') }}" class="btn btn-light">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard Pemilik
                            </a>
                        @else
                            <a href="{{ route('penyewa.dashboard') }}" class="btn btn-light">
                                <i class="bi bi-speedometer2 me-1"></i>Dashboard Penyewa
                            </a>
                        @endif
                    </div>
                @endguest
            </div>
        </div>
    </div>

    <!-- Features Section -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-center mb-3">Mengapa Memilih Kami?</h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-shield-check text-primary mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title">Aman & Terpercaya</h6>
                    <p class="card-text small">Semua motor telah melalui verifikasi admin.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-currency-dollar text-success mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title">Harga Terjangkau</h6>
                    <p class="card-text small">Berbagai pilihan dengan harga kompetitif.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100">
                <div class="card-body text-center">
                    <i class="bi bi-clock text-warning mb-2" style="font-size: 2.5rem;"></i>
                    <h6 class="card-title">Proses Cepat</h6>
                    <p class="card-text small">Booking mudah dan konfirmasi langsung.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Motor Categories -->
    <div class="row mb-4">
        <div class="col-12">
            <h3 class="text-center mb-3">Kategori Motor</h3>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-primary">
                <div class="card-header bg-primary text-white text-center">
                    <h6 class="mb-0">Motor 100cc</h6>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-bicycle text-primary mb-2" style="font-size: 2rem;"></i>
                    <p class="small">Cocok untuk dalam kota</p>
                    <strong class="text-primary">Rp 50.000/hari</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-success">
                <div class="card-header bg-success text-white text-center">
                    <h6 class="mb-0">Motor 125cc</h6>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-bicycle text-success mb-2" style="font-size: 2rem;"></i>
                    <p class="small">Balance tenaga dan efisiensi</p>
                    <strong class="text-success">Rp 75.000/hari</strong>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card border-warning">
                <div class="card-header bg-warning text-white text-center">
                    <h6 class="mb-0">Motor 150cc</h6>
                </div>
                <div class="card-body text-center">
                    <i class="bi bi-bicycle text-warning mb-2" style="font-size: 2rem;"></i>
                    <p class="small">Performa tinggi perjalanan jauh</p>
                    <strong class="text-warning">Rp 100.000/hari</strong>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection