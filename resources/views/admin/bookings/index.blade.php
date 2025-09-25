@extends('layouts.fann')

@section('title', 'Kelola Pemesanan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Kelola Pemesanan</h1>
    <p>Manajemen pemesanan motor dalam sistem rental</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Daftar Pemesanan
                    </h5>
                    <div>
                        <button class="btn btn-success me-2" onclick="exportBookings()">
                            <i class="bi bi-file-pdf"></i> Export Bookings
                        </button>
                        <span class="badge bg-info fs-6">Total: {{ $bookings->total() }} Pemesanan</span>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter dan Search -->
                <form method="GET" action="{{ route('admin.bookings') }}">
                    <div class="row mb-3">
                        <div class="col-md-2">
                            <label for="status_filter" class="form-label">Status</label>
                            <select class="form-select" id="status_filter" name="status">
                                <option value="">Semua Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Sedang Berlangsung</option>
                                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Dibatalkan</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-2">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       placeholder="Nama penyewa, motor, atau kode booking..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-outline-primary">
                                        <i class="bi bi-search"></i> Filter
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Statistik Cepat -->
                <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['pending'] ?? 0 }}</h4>
                                    <p class="mb-0">Pending</p>
                                </div>
                                <i class="bi bi-clock-history fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['confirmed'] ?? 0 }}</h4>
                                    <p class="mb-0">Dikonfirmasi</p>
                                </div>
                                <i class="bi bi-check-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['ongoing'] ?? 0 }}</h4>
                                    <p class="mb-0">Berlangsung</p>
                                </div>
                                <i class="bi bi-arrow-repeat fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $stats['completed'] ?? 0 }}</h4>
                                    <p class="mb-0">Selesai</p>
                                </div>
                                <i class="bi bi-check-all fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Pemesanan -->
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Kode Booking</th>
                                <th>Penyewa</th>
                                <th>Motor</th>
                                <th>Tanggal Sewa</th>
                                <th>Durasi</th>
                                <th>Total Harga</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                                @forelse($bookings as $booking)
                                <tr>
                                    <td>
                                        <strong>{{ $booking->booking_code }}</strong><br>
                                        <small class="text-muted">{{ $booking->created_at->format('d/m/Y H:i') }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->renter->name }}</strong><br>
                                        <small class="text-muted">{{ $booking->renter->phone }}</small>
                                    </td>
                                    <td>
                                        <strong>{{ $booking->motor->brand }} {{ $booking->motor->model }}</strong><br>
                                        <small class="text-muted">{{ $booking->motor->plate_number }} - {{ $booking->motor->cc }}cc</small>
                                    </td>
                                    <td>
                                        <strong>{{ \Carbon\Carbon::parse($booking->start_date)->format('d/m/Y') }}</strong><br>
                                        <small class="text-muted">s/d {{ \Carbon\Carbon::parse($booking->end_date)->format('d/m/Y') }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-secondary">{{ $booking->duration }} hari</span>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($booking->price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($booking->status == 'pending') bg-warning
                                            @elseif($booking->status == 'confirmed') bg-primary
                                            @elseif($booking->status == 'ongoing') bg-info
                                            @elseif($booking->status == 'completed') bg-success
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($booking->status) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-sm btn-outline-primary" 
                                                    onclick="viewBooking({{ $booking->id }})" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </button>
                                            @if($booking->status == 'pending')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="confirmBooking({{ $booking->id }})" title="Konfirmasi">
                                                <i class="bi bi-check"></i>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                                    onclick="cancelBooking({{ $booking->id }})" title="Batalkan">
                                                <i class="bi bi-x"></i>
                                            </button>
                                            @elseif($booking->status == 'confirmed')
                                            <button type="button" class="btn btn-sm btn-outline-info" 
                                                    onclick="startBooking({{ $booking->id }})" title="Mulai Sewa">
                                                <i class="bi bi-play"></i>
                                            </button>
                                            @elseif($booking->status == 'ongoing')
                                            <button type="button" class="btn btn-sm btn-outline-success" 
                                                    onclick="completeBooking({{ $booking->id }})" title="Selesaikan">
                                                <i class="bi bi-check-all"></i>
                                            </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="text-center">Tidak ada pemesanan ditemukan</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($bookings->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $bookings->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- Modal Detail Booking -->
<div class="modal fade" id="viewBookingModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Pemesanan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function viewBooking(bookingId) {
    fetch(`/admin/bookings/${bookingId}`)
        .then(response => response.json())
        .then(data => {
            document.getElementById('bookingDetailContent').innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Penyewa</h6>
                        <p><strong>Nama:</strong> ${data.renter.name}</p>
                        <p><strong>Email:</strong> ${data.renter.email}</p>
                        <p><strong>Telepon:</strong> ${data.renter.phone}</p>
                        
                        <h6 class="mt-4">Informasi Motor</h6>
                        <p><strong>Motor:</strong> ${data.motor.brand} ${data.motor.model}</p>
                        <p><strong>Plat Nomor:</strong> ${data.motor.plate_number}</p>
                        <p><strong>CC:</strong> ${data.motor.cc}cc</p>
                        <p><strong>Harga/Hari:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.motor.price_per_day)}</p>
                    </div>
                    <div class="col-md-6">
                        <h6>Detail Pemesanan</h6>
                        <p><strong>Kode Booking:</strong> ${data.booking_code}</p>
                        <p><strong>Tanggal Mulai:</strong> ${new Date(data.start_date).toLocaleDateString('id-ID')}</p>
                        <p><strong>Tanggal Selesai:</strong> ${new Date(data.end_date).toLocaleDateString('id-ID')}</p>
                        <p><strong>Durasi:</strong> ${data.duration} hari</p>
                        <p><strong>Total Harga:</strong> Rp ${new Intl.NumberFormat('id-ID').format(data.price)}</p>
                        <p><strong>Status:</strong> <span class="badge bg-primary">${data.status}</span></p>
                        <p><strong>Dibuat:</strong> ${new Date(data.created_at).toLocaleDateString('id-ID')}</p>
                    </div>
                </div>
            `;
            new bootstrap.Modal(document.getElementById('viewBookingModal')).show();
        });
}

function confirmBooking(bookingId) {
    if (confirm('Konfirmasi pemesanan ini?')) {
        updateBookingStatus(bookingId, 'confirmed');
    }
}

function cancelBooking(bookingId) {
    if (confirm('Batalkan pemesanan ini?')) {
        updateBookingStatus(bookingId, 'cancelled');
    }
}

function startBooking(bookingId) {
    if (confirm('Mulai proses penyewaan?')) {
        updateBookingStatus(bookingId, 'ongoing');
    }
}

function completeBooking(bookingId) {
    if (confirm('Selesaikan penyewaan ini?')) {
        updateBookingStatus(bookingId, 'completed');
    }
}

function updateBookingStatus(bookingId, status) {
    fetch(`/admin/bookings/${bookingId}/status`, {
        method: 'PATCH',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ status: status })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            alert('Gagal mengupdate status pemesanan');
        }
    });
}

function exportBookings() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/admin/bookings/export?${params.toString()}`, '_blank');
}
</script>
@endpush