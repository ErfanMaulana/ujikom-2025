@extends('layouts.motor')

@section('title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="card-title mb-1">
                            <i class="bi bi-person-circle me-2"></i>
                            Selamat Datang, {{ auth()->user()->name }}!
                        </h1>
                        <p class="card-text mb-0">
                            Anda masuk sebagai <strong>{{ ucfirst(auth()->user()->role) }}</strong> 
                            di Sistem Penyewaan Motor
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <i class="bi bi-motorcycle" style="font-size: 4rem; opacity: 0.3;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(auth()->user()->isPenyewa())
    <!-- Dashboard Penyewa -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Booking Aktif</p>
                        </div>
                        <i class="bi bi-bookmark-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Total Sewa</p>
                        </div>
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Pending</p>
                        </div>
                        <i class="bi bi-clock-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-secondary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">25</h4>
                            <p class="card-text">Motor Tersedia</p>
                        </div>
                        <i class="bi bi-motorcycle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-search d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Cari Motor</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-bookmark d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Booking Saya</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-info w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-clock-history d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Riwayat</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif(auth()->user()->isPemilik())
    <!-- Dashboard Pemilik -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Motor Terdaftar</p>
                        </div>
                        <i class="bi bi-motorcycle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Motor Disewa</p>
                        </div>
                        <i class="bi bi-check-circle-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Rp 0</h4>
                            <p class="card-text">Pendapatan Bulan Ini</p>
                        </div>
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Menunggu Verifikasi</p>
                        </div>
                        <i class="bi bi-clock-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-plus-circle d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Tambah Motor</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-motorcycle d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Motor Saya</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-4 mb-3">
                            <a href="#" class="btn btn-info w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-graph-up d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Laporan</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@elseif(auth()->user()->isAdmin())
    <!-- Dashboard Admin -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Total Motor</p>
                        </div>
                        <i class="bi bi-motorcycle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Total Booking</p>
                        </div>
                        <i class="bi bi-bookmark-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">Rp 0</h4>
                            <p class="card-text">Pendapatan Total</p>
                        </div>
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h4 class="card-title">0</h4>
                            <p class="card-text">Menunggu Verifikasi</p>
                        </div>
                        <i class="bi bi-clock-fill" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-lightning me-2"></i>Aksi Cepat Admin</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-warning w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-check-circle d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Verifikasi Motor</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-success w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-card-checklist d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Kelola Booking</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-info w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-people d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Kelola Users</span>
                                </div>
                            </a>
                        </div>
                        <div class="col-md-3 mb-3">
                            <a href="#" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center">
                                <div class="text-center">
                                    <i class="bi bi-bar-chart d-block mb-2" style="font-size: 2rem;"></i>
                                    <span>Laporan</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif

<!-- Recent Activity -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="bi bi-clock-history me-2"></i>Aktivitas Terbaru</h5>
            </div>
            <div class="card-body">
                <div class="text-center text-muted py-4">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-2">Belum ada aktivitas terbaru</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
