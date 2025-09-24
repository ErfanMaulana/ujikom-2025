@extends('layouts.fann')

@section('title', 'Detail Motor')

@section('content')
<div class="content-header">
    <h1><i class="bi bi-motorcycle me-3"></i>Detail Motor</h1>
    <p>Informasi lengkap motor untuk verifikasi</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0"><i class="bi bi-info-circle me-2"></i>Informasi Motor</h5>
                <div>
                    @if($motor->status === 'pending_verification')
                        <span class="badge bg-warning fs-6">Menunggu Verifikasi</span>
                    @elseif($motor->status === 'available')
                        <span class="badge bg-success fs-6">Tersedia</span>
                    @elseif($motor->status === 'rented')
                        <span class="badge bg-info fs-6">Disewa</span>
                    @else
                        <span class="badge bg-secondary fs-6">{{ ucfirst($motor->status) }}</span>
                    @endif
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-5">
                        @if($motor->photo)
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 alt="{{ $motor->brand }} {{ $motor->model }}"
                                 class="img-fluid rounded shadow-sm">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center shadow-sm" 
                                 style="height: 300px;">
                                <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="col-md-7">
                        <h3 class="mb-3">{{ $motor->brand }} {{ $motor->model }}</h3>
                        
                        <div class="d-flex align-items-center mb-3">
                            <span class="badge bg-primary me-2">{{ $motor->type_cc }}</span>
                            @if($motor->status === 'available')
                                <span class="badge bg-success">Tersedia</span>
                            @else
                                <span class="badge bg-warning">Tidak Tersedia</span>
                            @endif
                        </div>
                        
                        <div class="row">
                            <div class="col-sm-6 mb-3">
                                <h6><i class="bi bi-calendar me-2"></i>Tahun</h6>
                                <p class="mb-0">{{ $motor->year }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <h6><i class="bi bi-palette me-2"></i>Warna</h6>
                                <p class="mb-0">{{ $motor->color }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <h6><i class="bi bi-card-text me-2"></i>Plat Nomor</h6>
                                <p class="mb-0">{{ $motor->plate_number }}</p>
                            </div>
                            <div class="col-sm-6 mb-3">
                                <h6><i class="bi bi-person me-2"></i>Pemilik</h6>
                                <p class="mb-0">{{ $motor->owner->name }}</p>
                                <small class="text-muted">{{ $motor->owner->email }}</small>
                            </div>
                        </div>
                        
                        @if($motor->description)
                            <div class="mt-4">
                                <h6><i class="bi bi-chat-text me-2"></i>Deskripsi</h6>
                                <p class="text-muted">{{ $motor->description }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @if($motor->status === 'pending_verification')
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-check-circle me-2"></i>Verifikasi Motor</h5>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.motor.verify', $motor->id) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-4">
                            <label for="daily_rate" class="form-label">
                                <i class="bi bi-currency-dollar me-2"></i>Tarif Sewa per Hari *
                            </label>
                            <div class="input-group">
                                <span class="input-group-text">Rp</span>
                                <input type="number" 
                                       class="form-control" 
                                       id="daily_rate" 
                                       name="daily_rate" 
                                       min="0" 
                                       step="1000"
                                       placeholder="50000"
                                       required>
                            </div>
                            <div class="form-text">Masukkan tarif sewa per hari untuk motor ini</div>
                        </div>

                        <div class="mb-4">
                            <label for="verification_notes" class="form-label">
                                <i class="bi bi-chat-text me-2"></i>Catatan Verifikasi (Opsional)
                            </label>
                            <textarea class="form-control" 
                                      id="verification_notes" 
                                      name="verification_notes" 
                                      rows="3"
                                      placeholder="Tambahkan catatan verifikasi jika diperlukan..."></textarea>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.motors') }}" class="btn btn-secondary">
                                <i class="bi bi-arrow-left me-1"></i>Kembali
                            </a>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-check-circle me-1"></i>Verifikasi & Setujui
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @else
            <div class="d-flex justify-content-start mt-4">
                <a href="{{ route('admin.motors') }}" class="btn btn-secondary">
                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar Motor
                </a>
            </div>
        @endif
    </div>

    <div class="col-lg-4">
        @if($motor->rentalRate)
            <div class="card mb-4">
                <div class="card-header">
                    <h6 class="mb-0"><i class="bi bi-currency-dollar me-2"></i>Harga Sewa</h6>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-12 mb-3">
                            <div class="text-center">
                                <h6>Harian</h6>
                                <h4 class="text-primary">Rp {{ number_format((float)$motor->rentalRate->daily_rate, 0, ',', '.') }}</h4>
                                <small class="text-muted">per hari</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6>Mingguan</h6>
                                <h5 class="text-info">Rp {{ number_format((float)$motor->rentalRate->daily_rate * 7 * 0.9, 0, ',', '.') }}</h5>
                                <small class="text-muted">per minggu</small>
                                <br><small class="text-success">Diskon 10%</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="text-center">
                                <h6>Bulanan</h6>
                                <h5 class="text-warning">Rp {{ number_format((float)$motor->rentalRate->daily_rate * 30 * 0.8, 0, ',', '.') }}</h5>
                                <small class="text-muted">per bulan</small>
                                <br><small class="text-success">Diskon 20%</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Statistik Motor</h6>
            </div>
            <div class="card-body">
                @php
                    $totalBookings = $motor->bookings()->count();
                    $totalEarnings = $motor->bookings()->where('status', 'completed')->sum('price');
                @endphp
                
                <div class="text-center">
                    <h4 class="text-primary">{{ $totalBookings }}</h4>
                    <small>Total Booking</small>
                    <hr>
                    <h4 class="text-success">Rp {{ number_format($totalEarnings, 0, ',', '.') }}</h4>
                    <small>Total Earnings</small>
                </div>
            </div>
        </div>

        <div class="card mt-4">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-person me-2"></i>Informasi Pemilik</h6>
            </div>
            <div class="card-body">
                <div class="text-center">
                    <h6 class="mb-1">{{ $motor->owner->name }}</h6>
                    <p class="text-muted mb-2">{{ $motor->owner->email }}</p>
                    @if($motor->owner->phone)
                        <p class="text-muted mb-2">
                            <i class="bi bi-phone me-1"></i>{{ $motor->owner->phone }}
                        </p>
                    @endif
                    <small class="text-muted">
                        Bergabung: {{ $motor->owner->created_at->format('d M Y') }}
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection