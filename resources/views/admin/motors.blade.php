@extends('layouts.fann')

@section('title', 'Verifikasi Motor')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Verifikasi Motor</h1>
    <p>Kelola dan verifikasi motor yang didaftarkan pemilik</p>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.motors') }}" class="row g-3">
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="cc">
                    <option value="">Semua CC</option>
                    <option value="100" {{ request('cc') == '100' ? 'selected' : '' }}>100cc</option>
                    <option value="125" {{ request('cc') == '125' ? 'selected' : '' }}>125cc</option>
                    <option value="150" {{ request('cc') == '150' ? 'selected' : '' }}>150cc</option>
                    <option value="250" {{ request('cc') == '250' ? 'selected' : '' }}>250cc</option>
                    <option value="500" {{ request('cc') == '500' ? 'selected' : '' }}>500cc</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari brand atau plat nomor...">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.motors') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="row g-2">
            <div class="col-6">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $pendingCount ?? 0 }}</h6>
                        <small>Perlu Verifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-success text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $verifiedCount ?? 0 }}</h6>
                        <small>Terverifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Motors Grid -->
<div class="row">
    @if($motors->count() > 0)
        @foreach($motors as $motor)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
                <!-- Motor Image -->
                <div class="position-relative">
                    @if($motor->photo)
                        <img src="{{ Storage::url($motor->photo) }}" 
                             class="card-img-top" 
                             alt="{{ $motor->brand }}" 
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 250px;">
                            <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($motor->status === 'pending_verification')
                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                        @elseif($motor->status === 'available')
                            <span class="badge bg-success">Tersedia</span>
                        @elseif($motor->status === 'rented')
                            <span class="badge bg-info">Disewa</span>
                        @elseif($motor->status === 'maintenance')
                            <span class="badge bg-secondary">Maintenance</span>
                        @endif
                    </div>
                </div>

                <!-- Motor Info -->
                <div class="card-body">
                    <h5 class="card-title">{{ $motor->brand }}</h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-gear me-1"></i>{{ $motor->cc }}cc
                        <span class="ms-3">
                            <i class="bi bi-credit-card me-1"></i>{{ $motor->plate_number }}
                        </span>
                    </p>
                    
                    <!-- Owner Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2"></i>
                            <div>
                                <div class="fw-bold">{{ $motor->owner->name }}</div>
                                <small class="text-muted">{{ $motor->owner->email }}</small>
                            </div>
                        </div>
                    </div>

                    <!-- Rental Rates -->
                    @if($motor->rentalRates)
                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Harian</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRates->daily_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Mingguan</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRates->weekly_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Bulanan</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRates->monthly_rate, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $motor->created_at->format('d M Y') }}
                        </small>
                        <div class="btn-group">
                            <a href="{{ route('admin.motor.detail', $motor->id) }}" class="btn btn-sm btn-outline-primary">
                                <i class="bi bi-eye me-1"></i>Detail
                            </a>
                            @if($motor->status === 'pending_verification')
                                <button type="button" class="btn btn-sm btn-success" onclick="verifyMotor({{ $motor->id }})">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-motorcycle text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">Tidak ada motor ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($motors->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $motors->links() }}
    </div>
@endif

<!-- Verify Motor Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verifikasi Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin memverifikasi motor ini?</p>
                <p class="text-muted">Motor yang sudah diverifikasi akan muncul dalam daftar rental untuk penyewa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="verifyForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Verifikasi Motor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function verifyMotor(motorId) {
    const form = document.getElementById('verifyForm');
    form.action = `/admin/motors/${motorId}/verify`;
    
    const modal = new bootstrap.Modal(document.getElementById('verifyModal'));
    modal.show();
}
</script>
@endsection