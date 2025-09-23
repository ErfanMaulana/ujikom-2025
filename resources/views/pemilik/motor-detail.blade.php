@extends('layouts.fann')

@section('title', 'Detail Motor - ' . $motor->brand . ' ' . $motor->model)

@section('content')
<!-- Content Header -->
<div class="content-header">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Detail Motor</h1>
            <p>{{ $motor->brand }} {{ $motor->model }} - {{ $motor->plate_number }}</p>
        </div>
        <div>
            <a href="{{ route('pemilik.motors') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Kembali
            </a>
            @if(Auth::user()->isVerified())
                <a href="{{ route('pemilik.motor.edit', $motor->id) }}" class="btn btn-warning">
                    <i class="bi bi-pencil me-2"></i>Edit Motor
                </a>
                <button type="button" class="btn btn-danger" onclick="confirmDelete({{ $motor->id }})">
                    <i class="bi bi-trash me-2"></i>Hapus Motor
                </button>
            @endif
        </div>
    </div>
</div>

<!-- Motor Detail Card -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-motorcycle me-2"></i>
                    Informasi Motor
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Motor Image -->
                    <div class="col-md-5 mb-3">
                        @if($motor->photo)
                            <img src="{{ Storage::url($motor->photo) }}" 
                                 class="img-fluid rounded" 
                                 alt="{{ $motor->brand }}"
                                 style="width: 100%; height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                 style="height: 300px;">
                                <div class="text-center text-muted">
                                    <i class="bi bi-camera" style="font-size: 3rem;"></i>
                                    <p class="mt-2">Tidak ada foto</p>
                                </div>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Motor Info -->
                    <div class="col-md-7">
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Brand:</strong></div>
                            <div class="col-sm-8">{{ $motor->brand }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Model:</strong></div>
                            <div class="col-sm-8">{{ $motor->model }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>CC:</strong></div>
                            <div class="col-sm-8">{{ $motor->type_cc }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Tahun:</strong></div>
                            <div class="col-sm-8">{{ $motor->year }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Warna:</strong></div>
                            <div class="col-sm-8">{{ $motor->color }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Plat Nomor:</strong></div>
                            <div class="col-sm-8">{{ $motor->plate_number }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-4"><strong>Status:</strong></div>
                            <div class="col-sm-8">
                                @switch($motor->status)
                                    @case('available')
                                        <span class="badge bg-success">Tersedia</span>
                                        @break
                                    @case('rented')
                                        <span class="badge bg-warning">Disewa</span>
                                        @break
                                    @case('maintenance')
                                        <span class="badge bg-secondary">Maintenance</span>
                                        @break
                                    @case('pending_verification')
                                        <span class="badge bg-info">Menunggu Verifikasi</span>
                                        @break
                                    @default
                                        <span class="badge bg-dark">{{ ucfirst($motor->status) }}</span>
                                @endswitch
                            </div>
                        </div>
                        @if($motor->description)
                            <div class="row mb-3">
                                <div class="col-sm-4"><strong>Deskripsi:</strong></div>
                                <div class="col-sm-8">{{ $motor->description }}</div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Rental Rate Card -->
        @if($motor->rentalRate)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-currency-dollar me-2"></i>
                    Tarif Sewa
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-primary">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</h4>
                            <p class="text-muted mb-0">Per Hari</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-success">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</h4>
                            <p class="text-muted mb-0">Per Minggu</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="text-center">
                            <h4 class="text-info">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</h4>
                            <p class="text-muted mb-0">Per Bulan</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Recent Bookings -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>
                    Booking Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($motor->bookings->count() > 0)
                    @foreach($motor->bookings->take(5) as $booking)
                        <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                            <div>
                                <h6 class="mb-1">{{ $booking->user->name }}</h6>
                                <small class="text-muted">
                                    {{ $booking->start_date->format('d/m/Y') }} - {{ $booking->end_date->format('d/m/Y') }}
                                </small>
                            </div>
                            <div>
                                @switch($booking->status)
                                    @case('pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                        @break
                                    @case('confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                        @break
                                    @case('active')
                                        <span class="badge bg-primary">Aktif</span>
                                        @break
                                    @case('completed')
                                        <span class="badge bg-success">Selesai</span>
                                        @break
                                    @case('cancelled')
                                        <span class="badge bg-danger">Dibatalkan</span>
                                        @break
                                @endswitch
                            </div>
                        </div>
                    @endforeach
                    <div class="text-center">
                        <a href="{{ route('pemilik.bookings') }}?motor_id={{ $motor->id }}" class="btn btn-outline-primary btn-sm">
                            Lihat Semua Booking
                        </a>
                    </div>
                @else
                    <div class="text-center text-muted">
                        <i class="bi bi-calendar-x" style="font-size: 2rem;"></i>
                        <p class="mt-2">Belum ada booking</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Motor Statistics -->
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Statistik Motor
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex justify-content-between mb-3">
                    <span>Total Booking:</span>
                    <strong>{{ $motor->bookings->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Booking Aktif:</span>
                    <strong>{{ $motor->bookings->whereIn('status', ['confirmed', 'active'])->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between mb-3">
                    <span>Booking Selesai:</span>
                    <strong>{{ $motor->bookings->where('status', 'completed')->count() }}</strong>
                </div>
                <div class="d-flex justify-content-between">
                    <span>Terdaftar:</span>
                    <strong>{{ $motor->created_at->format('d/m/Y') }}</strong>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus motor <strong>{{ $motor->brand }} {{ $motor->model }}</strong>?</p>
                <div class="alert alert-warning">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    <strong>Peringatan:</strong> Aksi ini tidak dapat dibatalkan. Motor dan semua data terkait akan dihapus secara permanen.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form method="POST" action="{{ route('pemilik.motor.delete', $motor->id) }}" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-trash me-2"></i>Hapus Motor
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
function confirmDelete(motorId) {
    const deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
    deleteModal.show();
}
</script>
@endsection