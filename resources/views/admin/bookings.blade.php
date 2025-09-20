@extends('layouts.fann')

@section('title', 'Laporan Booking')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Laporan Booking</h1>
    <p>Kelola dan pantau semua transaksi booking motor</p>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $totalBookings ?? 0 }}</h3>
                        <p class="mb-0">Total Booking</p>
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
                        <h3 class="mb-0">{{ $activeBookings ?? 0 }}</h3>
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
                        <h3 class="mb-0">{{ $pendingBookings ?? 0 }}</h3>
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
                        <h3 class="mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <i class="bi bi-currency-dollar" style="font-size: 2rem; opacity: 0.7;"></i>
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
                <form method="GET" action="{{ route('admin.bookings') }}" class="row g-3">
                    <div class="col-md-2">
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
                    <div class="col-md-2">
                        <label class="form-label">Periode</label>
                        <select class="form-select" name="period">
                            <option value="">Semua Periode</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari</label>
                        <div class="input-group">
                            <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Booking ID, nama penyewa...">
                            <button class="btn btn-primary" type="submit">
                                <i class="bi bi-search"></i>
                            </button>
                        </div>
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <a href="{{ route('admin.bookings') }}" class="btn btn-outline-secondary w-100">Reset</a>
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
            <h5 class="mb-0">Daftar Booking</h5>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($bookings->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Booking ID</th>
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
                                        {{ substr($booking->user->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $booking->user->name }}</div>
                                        <small class="text-muted">{{ $booking->user->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $booking->motor->brand }}</div>
                                    <small class="text-muted">{{ $booking->motor->plate_number }}</small>
                                </div>
                            </td>
                            <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
                            <td>
                                <div>
                                    <div>{{ $booking->start_date->format('d M Y') }}</div>
                                    <small class="text-muted">s/d {{ $booking->end_date->format('d M Y') }}</small>
                                    <br>
                                    <small class="badge bg-light text-dark">{{ $booking->duration }} hari</small>
                                </div>
                            </td>
                            <td>
                                <div class="fw-bold text-success">Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</div>
                            </td>
                            <td>
                                @if($booking->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge bg-info">Confirmed</span>
                                @elseif($booking->status === 'active')
                                    <span class="badge bg-success">Active</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge bg-primary">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge bg-danger">Cancelled</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="{{ route('admin.booking.detail', $booking->id) }}">
                                            <i class="bi bi-eye me-2"></i>Detail
                                        </a></li>
                                        @if($booking->status === 'pending')
                                            <li><a class="dropdown-item text-success" href="#" onclick="confirmBooking({{ $booking->id }})">
                                                <i class="bi bi-check-circle me-2"></i>Konfirmasi
                                            </a></li>
                                            <li><a class="dropdown-item text-danger" href="#" onclick="cancelBooking({{ $booking->id }})">
                                                <i class="bi bi-x-circle me-2"></i>Batalkan
                                            </a></li>
                                        @endif
                                        @if($booking->status === 'confirmed')
                                            <li><a class="dropdown-item text-primary" href="#" onclick="activateBooking({{ $booking->id }})">
                                                <i class="bi bi-play-circle me-2"></i>Aktifkan
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
                <h4 class="mt-3 text-muted">Tidak ada booking ditemukan</h4>
                <p class="text-muted">Belum ada transaksi booking atau coba ubah filter pencarian</p>
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

<!-- Confirmation Modal -->
<div class="modal fade" id="actionModal" tabindex="-1" aria-labelledby="actionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="actionModalLabel">Konfirmasi Aksi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p id="actionMessage">Apakah Anda yakin ingin melakukan aksi ini?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="actionForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn" id="actionButton">Konfirmasi</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmBooking(bookingId) {
    showActionModal(
        bookingId, 
        'confirm', 
        'Konfirmasi Booking', 
        'Apakah Anda yakin ingin mengkonfirmasi booking ini?',
        'btn-success'
    );
}

function cancelBooking(bookingId) {
    showActionModal(
        bookingId, 
        'cancel', 
        'Batalkan Booking', 
        'Apakah Anda yakin ingin membatalkan booking ini?',
        'btn-danger'
    );
}

function activateBooking(bookingId) {
    showActionModal(
        bookingId, 
        'activate', 
        'Aktifkan Booking', 
        'Apakah Anda yakin ingin mengaktifkan booking ini?',
        'btn-primary'
    );
}

function completeBooking(bookingId) {
    showActionModal(
        bookingId, 
        'complete', 
        'Selesaikan Booking', 
        'Apakah Anda yakin ingin menyelesaikan booking ini?',
        'btn-info'
    );
}

function showActionModal(bookingId, action, title, message, buttonClass) {
    document.getElementById('actionModalLabel').textContent = title;
    document.getElementById('actionMessage').textContent = message;
    
    const form = document.getElementById('actionForm');
    form.action = `/admin/bookings/${bookingId}/${action}`;
    
    const button = document.getElementById('actionButton');
    button.className = `btn ${buttonClass}`;
    button.textContent = title;
    
    const modal = new bootstrap.Modal(document.getElementById('actionModal'));
    modal.show();
}
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 0.875rem;
}
</style>
@endsection