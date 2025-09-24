@extends('layouts.fann')

@section('title', 'Dashboard Pemilik')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Dashboard Pemilik</h1>
    <p>Kelola motor dan pantau pendapatan Anda dengan mudah</p>
</div>

<!-- Verification Status Alert -->
@if(!$isVerified)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-exclamation me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Akun Belum Terverifikasi</h6>
                <p class="mb-0">Akun Anda belum diverifikasi. Anda dapat melihat dashboard, tetapi tidak dapat mendaftarkan motor baru. Silakan tunggu admin memverifikasi akun Anda.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@else
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-check me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Akun Terverifikasi</h6>
                <p class="mb-0">Selamat! Akun Anda telah diverifikasi dan Anda dapat mendaftarkan motor untuk disewakan.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Error Messages -->
@if($errors->has('verification'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Akses Ditolak</h6>
                <p class="mb-0">{{ $errors->first('verification') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--primary-color);">
                <i class="bi bi-motorcycle"></i>
            </div>
            <h3 class="stat-number">{{ $totalMotors }}</h3>
            <p class="stat-label">Total Motor</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stat-number">{{ $availableMotors }}</h3>
            <p class="stat-label">Motor Tersedia</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="bi bi-clock"></i>
            </div>
            <h3 class="stat-number">{{ $rentedMotors }}</h3>
            <p class="stat-label">Sedang Disewa</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: var(--info-color);">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="stat-number">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p class="stat-label">Total Pendapatan</p>
        </div>
    </div>
</div>

<!-- Main Content Row -->
<div class="row">
    <!-- Motor Terbaru -->
    <div class="col-lg-8 mb-4">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="bi bi-motorcycle me-2"></i>
                        Motor Saya
                    </h5>
                    <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-primary btn-sm">
                        Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($recentMotors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Motor</th>
                                    <th>CC</th>
                                    <th>Status</th>
                                    <th>Tarif/Hari</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentMotors as $motor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($motor->photo)
                                                <img src="{{ Storage::url($motor->photo) }}" 
                                                     alt="{{ $motor->brand }} {{ $motor->model }}" 
                                                     class="rounded me-3" 
                                                     style="width: 48px; height: 48px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 48px; height: 48px;">
                                                    <i class="bi bi-motorcycle text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <h6 class="mb-0">{{ $motor->brand }}</h6>
                                                <small class="text-muted">{{ $motor->model }} • {{ $motor->year }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $motor->cc }}cc</span>
                                    </td>
                                    <td>
                                        @if($motor->is_verified)
                                            @php
                                                $currentStatus = $motor->getCurrentStatus();
                                                $currentBooking = $motor->getCurrentBooking();
                                            @endphp
                                            
                                            @if($currentStatus === 'rented')
                                                <span class="badge bg-warning text-dark" title="Sedang disewa">
                                                    <i class="bi bi-person-check me-1"></i>Disewa
                                                </span>
                                                @if($currentBooking)
                                                    <br><small class="text-muted">{{ $currentBooking->renter->name }}</small>
                                                @endif
                                            @elseif($currentStatus === 'available')
                                                <span class="badge bg-success" title="Tersedia untuk disewa">
                                                    <i class="bi bi-check-circle me-1"></i>Tersedia
                                                </span>
                                            @elseif($currentStatus === 'maintenance')
                                                <span class="badge bg-secondary" title="Dalam maintenance">
                                                    <i class="bi bi-tools me-1"></i>Maintenance
                                                </span>
                                            @endif
                                        @else
                                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($motor->rentalRate)
                                            <strong>Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</strong>
                                        @else
                                            <span class="text-muted">Belum diset</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('pemilik.motor.detail', $motor->id) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="empty-state">
                        <i class="bi bi-motorcycle"></i>
                        <h6>Belum ada motor yang didaftarkan</h6>
                        <p>Mulai daftarkan motor Anda untuk disewakan</p>
                        <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar Actions -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-lightning-charge me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-3">
                    <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
                        <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor Baru
                    </a>
                    <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-primary">
                        <i class="bi bi-list-ul me-2"></i>Kelola Motor
                    </a>
                    <a href="{{ route('pemilik.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>Lihat Pemesanan
                    </a>
                    <a href="{{ route('pemilik.revenue.report') }}" class="btn btn-outline-primary">
                        <i class="bi bi-graph-up me-2"></i>Laporan Pendapatan
                    </a>
                </div>
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips Sukses
                </h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                        <small>Pastikan motor selalu dalam kondisi prima</small>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                        <small>Upload foto motor yang menarik dan berkualitas</small>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="d-flex">
                        <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                        <small>Set tarif yang kompetitif dan wajar</small>
                    </div>
                </div>
                <div class="mb-0">
                    <div class="d-flex">
                        <i class="bi bi-check-circle text-success me-2 mt-1"></i>
                        <small>Respon cepat terhadap pemesanan pelanggan</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection