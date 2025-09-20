@extends('layouts.fann')

@section('title', 'Motor Saya')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Motor Saya</h1>
    <p>Kelola semua motor yang telah Anda daftarkan</p>
</div>

<!-- Action Bar -->
<div class="row mb-4">
    <div class="col-md-6">
        <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor Baru
        </a>
    </div>
    <div class="col-md-6">
        <form method="GET" action="{{ route('pemilik.motors') }}">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari motor...">
                <select class="form-select" name="status" style="max-width: 200px;">
                    <option value="">Semua Status</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Motors List -->
@if($motors->count() > 0)
    <div class="row">
        @foreach($motors as $motor)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
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
                    <div class="position-absolute top-0 start-0 m-3">
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

                    <!-- Action Menu -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle" type="button" 
                                    id="motorAction{{ $motor->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="motorAction{{ $motor->id }}">
                                <li>
                                    <a class="dropdown-item" href="{{ route('pemilik.motor.detail', $motor->id) }}">
                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('pemilik.motor.edit', $motor->id) }}">
                                        <i class="bi bi-pencil me-2"></i>Edit Motor
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <button class="dropdown-item text-danger" onclick="deleteMotor({{ $motor->id }}, '{{ $motor->brand }} {{ $motor->plate_number }}')">
                                        <i class="bi bi-trash me-2"></i>Hapus Motor
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Motor Info -->
                <div class="card-body">
                    <h5 class="card-title">{{ $motor->brand }}</h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-gear me-1"></i>{{ $motor->type_cc }}
                        <span class="ms-3">
                            <i class="bi bi-credit-card me-1"></i>{{ $motor->plate_number }}
                        </span>
                    </p>
                    
                    @if($motor->description)
                        <p class="card-text text-muted small">
                            {{ Str::limit($motor->description, 80) }}
                        </p>
                    @endif

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

                <!-- Footer -->
                <div class="card-footer bg-light text-muted small d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-calendar me-1"></i>
                        Didaftarkan {{ $motor->created_at->diffForHumans() }}
                    </span>
                    @if($motor->status === 'available')
                        <span class="text-success">
                            <i class="bi bi-check-circle me-1"></i>Terverifikasi
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $motors->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="bi bi-motorcycle"></i>
        <h6>Belum ada motor yang didaftarkan</h6>
        <p>Mulai daftarkan motor Anda untuk disewakan dan dapatkan penghasilan tambahan</p>
        <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor Pertama
        </a>
    </div>
@endif

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus motor <strong id="motorName"></strong>?</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Motor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function deleteMotor(motorId, motorName) {
    const form = document.getElementById('deleteForm');
    form.action = `/pemilik/motors/${motorId}`;
    
    // Update motor name in modal
    document.getElementById('motorName').textContent = motorName;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection