@extends('layouts.fann')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Dashboard Admin</h1>
    <p>Kelola platform rental motor dengan kontrol penuh</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-primary mb-2">
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalUsers }}</h3>
                <p class="text-muted mb-0">Total Pengguna</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="bi bi-motorcycle" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalMotors }}</h3>
                <p class="text-muted mb-0">Total Motor</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalBookings }}</h3>
                <p class="text-muted mb-0">Total Pemesanan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="bi bi-currency-dollar" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                <p class="text-muted mb-0">Total Pendapatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-primary mb-1">{{ $totalPenyewa }}</h4>
                <small class="text-muted">Penyewa</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-info mb-1">{{ $totalPemilik }}</h4>
                <small class="text-muted">Pemilik</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-warning mb-1">{{ $pendingMotorsCount }}</h4>
                <small class="text-muted">Perlu Verifikasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-success mb-1">{{ $availableMotors }}</h4>
                <small class="text-muted">Motor Tersedia</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-primary mb-1">{{ $pendingBookings }}</h4>
                <small class="text-muted">Booking Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-success mb-1">{{ $confirmedBookings }}</h4>
                <small class="text-muted">Booking Aktif</small>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Quick Actions -->
<div class="row">
    <!-- Pending Verifications -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Menunggu Verifikasi
                    </h5>
                    <a href="{{ route('admin.motors') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($pendingMotors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Motor</th>
                                    <th>Pemilik</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingMotors as $motor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($motor->photo)
                                                <img src="{{ Storage::url($motor->photo) }}" alt="{{ $motor->brand }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-motorcycle text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $motor->brand }}</div>
                                                <small class="text-muted">{{ $motor->cc }}cc - {{ $motor->plate_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $motor->owner->name }}</div>
                                        <small class="text-muted">{{ $motor->owner->email }}</small>
                                    </td>
                                    <td>{{ $motor->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.motor.detail', $motor->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Tidak ada motor yang menunggu verifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">
                        <i class="bi bi-people me-2"></i>Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.motors') }}" class="btn btn-outline-primary">
                        <i class="bi bi-motorcycle me-2"></i>Kelola Motor
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>Kelola Booking
                    </a>
                    <a href="{{ route('admin.financial-report') }}" class="btn btn-outline-success">
                        <i class="bi bi-bar-chart me-2"></i>Laporan Keuangan
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary">{{ $pendingMotorsCount }}</h6>
                        <small class="text-muted">Perlu Verifikasi</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-warning">{{ $pendingBookings }}</h6>
                        <small class="text-muted">Perlu Konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Statistik Pengguna
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h4 class="text-primary">{{ $totalPenyewa + $totalPemilik }}</h4>
                        <small class="text-muted">Total Users</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-warning">{{ $totalPemilik }}</h4>
                        <small class="text-muted">Pemilik</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-info">{{ $totalPenyewa }}</h4>
                        <small class="text-muted">Penyewa</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($pendingBookingsList->count() > 0)
                    @foreach($pendingBookingsList as $booking)
                    <div class="d-flex align-items-center mb-3">
                        <div class="flex-shrink-0">
                            @if($booking->motor->photo)
                                <img src="{{ Storage::url($booking->motor->photo) }}" 
                                     alt="{{ $booking->motor->brand }}" 
                                     class="rounded" 
                                     style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-motorcycle text-muted"></i>
                                </div>
                            @endif
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="mb-0">{{ $booking->motor->brand }}</h6>
                            <small class="text-muted">{{ $booking->renter->name }} • {{ $booking->created_at->format('d M Y') }}</small>
                        </div>
                        <div class="flex-shrink-0">
                            @if($booking->status === 'confirmed')
                                <span class="badge bg-success">Dikonfirmasi</span>
                            @elseif($booking->status === 'pending')
                                <span class="badge bg-warning">Menunggu</span>
                            @elseif($booking->status === 'completed')
                                <span class="badge bg-info">Selesai</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection