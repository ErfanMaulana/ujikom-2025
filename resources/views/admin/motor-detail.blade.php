@extends('layouts.app')

@section('title', 'Detail Motor - Admin')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Sidebar -->
        <div class="col-lg-2 col-md-3 bg-light sidebar">
            <div class="p-3">
                <h5 class="text-primary">Menu Admin</h5>
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.dashboard') }}">
                            <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.users') }}">
                            <i class="fas fa-users me-2"></i>Manajemen User
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="{{ route('admin.motors') }}">
                            <i class="fas fa-motorcycle me-2"></i>Verifikasi Motor
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.bookings') }}">
                            <i class="fas fa-calendar-check me-2"></i>Manajemen Booking
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('admin.reports') }}">
                            <i class="fas fa-chart-line me-2"></i>Laporan
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-lg-10 col-md-9">
            <div class="p-4">
                <!-- Header -->
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="mb-1">Detail Motor</h2>
                        <p class="text-muted">Informasi lengkap motor untuk verifikasi</p>
                    </div>
                    <a href="{{ route('admin.motors') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Kembali
                    </a>
                </div>

                <!-- Motor Detail Card -->
                <div class="row">
                    <div class="col-lg-8">
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-motorcycle me-2"></i>{{ $motor->brand }}
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Motor Photo -->
                                    <div class="col-md-6">
                                        @if($motor->photo)
                                            <img src="{{ asset('storage/' . $motor->photo) }}" 
                                                 alt="{{ $motor->brand }}" 
                                                 class="img-fluid rounded mb-3" 
                                                 style="width: 100%; height: 300px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center mb-3" 
                                                 style="height: 300px;">
                                                <i class="fas fa-motorcycle text-muted" style="font-size: 4rem;"></i>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Motor Info -->
                                    <div class="col-md-6">
                                        <table class="table table-borderless">
                                            <tr>
                                                <td class="fw-bold">Merek:</td>
                                                <td>{{ $motor->brand }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Kapasitas Mesin:</td>
                                                <td>{{ $motor->type_cc }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Nomor Plat:</td>
                                                <td>{{ $motor->plate_number }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Status:</td>
                                                <td>
                                                    @if($motor->status === 'pending_verification')
                                                        <span class="badge bg-warning">Menunggu Verifikasi</span>
                                                    @elseif($motor->status === 'available')
                                                        <span class="badge bg-success">Tersedia</span>
                                                    @elseif($motor->status === 'rented')
                                                        <span class="badge bg-info">Disewa</span>
                                                    @elseif($motor->status === 'maintenance')
                                                        <span class="badge bg-secondary">Maintenance</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Pemilik:</td>
                                                <td>{{ $motor->owner->name }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Email Pemilik:</td>
                                                <td>{{ $motor->owner->email }}</td>
                                            </tr>
                                            <tr>
                                                <td class="fw-bold">Telepon Pemilik:</td>
                                                <td>{{ $motor->owner->phone ?? 'Tidak tersedia' }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>

                                @if($motor->description)
                                <div class="mt-3">
                                    <h6>Deskripsi Motor:</h6>
                                    <p class="text-muted">{{ $motor->description }}</p>
                                </div>
                                @endif

                                <!-- Rental Rates -->
                                @if($motor->rentalRate)
                                <div class="mt-4">
                                    <h6>Tarif Sewa:</h6>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                                                <small class="text-muted">Per Hari</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</div>
                                                <small class="text-muted">Per Minggu</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="text-center p-3 bg-light rounded">
                                                <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</div>
                                                <small class="text-muted">Per Bulan</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Action Panel -->
                    <div class="col-lg-4">
                        <div class="card shadow-sm">
                            <div class="card-header">
                                <h6 class="mb-0">Aksi Verifikasi</h6>
                            </div>
                            <div class="card-body">
                                @if($motor->status === 'pending_verification')
                                    <form action="{{ route('admin.motor.verify', $motor) }}" method="POST">
                                        @csrf
                                        @method('PATCH')
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-success">
                                                <i class="fas fa-check me-2"></i>Verifikasi Motor
                                            </button>
                                        </div>
                                    </form>
                                @else
                                    <div class="alert alert-info">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Motor sudah diverifikasi
                                    </div>
                                @endif

                                <hr>

                                <div class="text-center">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Didaftarkan: {{ $motor->created_at->format('d M Y') }}
                                    </small>
                                </div>

                                @if($motor->verified_at)
                                <div class="text-center mt-2">
                                    <small class="text-muted">
                                        <i class="fas fa-check-circle me-1"></i>
                                        Diverifikasi: {{ $motor->verified_at->format('d M Y') }}
                                    </small>
                                </div>
                                @endif
                            </div>
                        </div>

                        <!-- Booking History (if any) -->
                        @if($motor->bookings->count() > 0)
                        <div class="card shadow-sm mt-3">
                            <div class="card-header">
                                <h6 class="mb-0">Riwayat Pesanan</h6>
                            </div>
                            <div class="card-body">
                                @foreach($motor->bookings->take(5) as $booking)
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <small class="fw-bold">{{ $booking->user->name }}</small><br>
                                        <small class="text-muted">{{ $booking->start_date->format('d M Y') }}</small>
                                    </div>
                                    <span class="badge bg-{{ $booking->status === 'completed' ? 'success' : 'warning' }}">
                                        {{ $booking->status }}
                                    </span>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection