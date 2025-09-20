@extends('layouts.fann')

@section('title', 'Kelola Pemesanan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Kelola Pemesanan</h1>
    <p>Kelola dan pantau pemesanan motor Anda</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $bookings->total() }}</h3>
                        <p class="mb-0">Total Pemesanan</p>
                    </div>
                    <i class="bi bi-calendar-check" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $bookings->where('status', 'active')->count() }}</h3>
                        <p class="mb-0">Sedang Aktif</p>
                    </div>
                    <i class="bi bi-play-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $bookings->where('status', 'pending')->count() }}</h3>
                        <p class="mb-0">Menunggu Konfirmasi</p>
                    </div>
                    <i class="bi bi-clock" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $bookings->where('status', 'completed')->count() }}</h3>
                        <p class="mb-0">Selesai</p>
                    </div>
                    <i class="bi bi-check-circle" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('pemilik.bookings') }}" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                            <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Aksi</label>
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i> Filter
                            </button>
                            <a href="{{ route('pemilik.bookings') }}" class="btn btn-outline-secondary">Reset</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Bookings Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Daftar Pemesanan Motor</h5>
        </div>
    </div>
    <div class="card-body p-0">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>ID Booking</th>
                            <th>Penyewa</th>
                            <th>Motor</th>
                            <th>Tanggal Booking</th>
                            <th>Periode Sewa</th>
                            <th>Total Biaya</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bookings as $booking)
                        <tr>
                            <td>
                                <span class="fw-bold text-primary">#{{ $booking->id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary rounded-circle text-white d-flex align-items-center justify-content-center me-3">
                                        {{ substr($booking->renter->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $booking->renter->name }}</div>
                                        <small class="text-muted">{{ $booking->renter->email }}</small>
                                        @if($booking->renter->phone)
                                            <br><small class="text-muted">{{ $booking->renter->phone }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                    <small class="text-muted">{{ $booking->motor->plate_number }}</small>
                                    <br><small class="badge bg-light text-dark">{{ $booking->motor->cc }}cc</small>
                                </div>
                            </td>
                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div>
                                    <div>{{ $booking->start_date->format('d M Y') }}</div>
                                    <small class="text-muted">s/d {{ $booking->end_date->format('d M Y') }}</small>
                                    <br>
                                    <small class="badge bg-light text-dark">{{ $booking->getDurationInDays() }} hari</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">Rp {{ number_format($booking->price, 0, ',', '.') }}</div>
                                @if($booking->payment)
                                    <small class="text-muted">{{ ucfirst($booking->payment->method) }}</small>
                                @endif
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-info">Dikonfirmasi</span>
                                @elseif($booking->status === 'active')
                                    <span class="badge bg-success">Sedang Berlangsung</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge bg-primary">Selesai</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Dibatalkan</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#" onclick="viewBookingDetail({{ $booking->id }})">
                                            <i class="bi bi-eye me-2"></i>Detail
                                        </a></li>
                                        @if($booking->status === 'pending')
                                            <li><a class="dropdown-item text-success" href="#" onclick="confirmBooking({{ $booking->id }})">
                                                <i class="bi bi-check-circle me-2"></i>Terima
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="cancelBooking({{ $booking->id }})">
                                                <i class="bi bi-x-circle me-2"></i>Tolak
                                            </a></li>
                                        @endif
                                        @if($booking->status === 'confirmed')
                                            <li><a class="dropdown-item text-primary" href="#" onclick="activateBooking({{ $booking->id }})">
                                                <i class="bi bi-play-circle me-2"></i>Mulai Sewa
                                            </a></li>
                                        @endif
                                        @if($booking->status === 'active')
                                            <li><a class="dropdown-item text-info" href="#" onclick="completeBooking({{ $booking->id }})">
                                                <i class="bi bi-check-circle-fill me-2"></i>Selesaikan
                                            </a></li>
                                        @endif
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-calendar-x text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">Tidak ada pemesanan ditemukan</h4>
                <p class="text-muted">Belum ada pemesanan untuk motor Anda atau coba ubah filter pencarian</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($bookings->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $bookings->links() }}
    </div>
@endif

<!-- Booking Detail Modal -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <!-- Content will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 14px;
    font-weight: 600;
}

.content-header {
    margin-bottom: 2rem;
}

.content-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.content-header p {
    color: #6c757d;
    margin-bottom: 0;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>

<script>
function viewBookingDetail(bookingId) {
    // Load booking detail via AJAX
    fetch(`/pemilik/booking/${bookingId}/detail`)
        .then(response => response.text())
        .then(html => {
            document.getElementById('bookingDetailContent').innerHTML = html;
            new bootstrap.Modal(document.getElementById('bookingDetailModal')).show();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal memuat detail pemesanan');
        });
}

function confirmBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin menerima pemesanan ini?')) {
        fetch(`/pemilik/booking/${bookingId}/confirm`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal mengkonfirmasi pemesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function cancelBooking(bookingId) {
    const reason = prompt('Masukkan alasan penolakan:');
    if (reason && reason.trim() !== '') {
        fetch(`/pemilik/booking/${bookingId}/cancel`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ reason: reason })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal membatalkan pemesanan');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function activateBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin memulai masa sewa ini?')) {
        fetch(`/pemilik/booking/${bookingId}/activate`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal memulai masa sewa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}

function completeBooking(bookingId) {
    if (confirm('Apakah Anda yakin ingin menyelesaikan masa sewa ini?')) {
        fetch(`/pemilik/booking/${bookingId}/complete`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert(data.message || 'Gagal menyelesaikan masa sewa');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi kesalahan');
        });
    }
}
</script>
@endsection