@extends('layouts.fann')

@section('title', 'Laporan Rental')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Laporan Rental Saya</h1>
    <p>Ringkasan aktivitas rental dan rating motor</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-primary mb-2">
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalBookings }}</h3>
                <p class="text-muted mb-0">Total Booking</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="bi bi-check-circle" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $completedBookings }}</h3>
                <p class="text-muted mb-0">Selesai</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="bi bi-clock" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $activeBookings }}</h3>
                <p class="text-muted mb-0">Aktif</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="bi bi-currency-dollar" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">Rp {{ number_format($totalSpending, 0, ',', '.') }}</h3>
                <p class="text-muted mb-0">Total Pengeluaran</p>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings & Ratings Section -->
<div class="row">
    <!-- Recent Bookings -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-calendar-check me-2"></i>Booking Terbaru
                    </h5>
                    <a href="{{ route('penyewa.bookings') }}" class="btn btn-sm btn-outline-primary">Lihat Semua</a>
                </div>
            </div>
            <div class="card-body">
                @forelse($recentBookings as $booking)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    {{ $booking->motor ? $booking->motor->brand . ' ' . $booking->motor->model : 'Motor Tidak Ditemukan' }}
                                </h6>
                                @if($booking->motor)
                                    <p class="text-muted small mb-1">{{ $booking->motor->plate_number }}</p>
                                @endif
                                <p class="text-muted small mb-1">
                                    {{ \Carbon\Carbon::parse($booking->start_date)->format('d M Y') }} - 
                                    {{ \Carbon\Carbon::parse($booking->end_date)->format('d M Y') }}
                                </p>
                                <p class="text-success fw-bold mb-0">Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</p>
                            </div>
                            <div class="text-end">
                                <span class="badge 
                                    @if($booking->status === 'completed') bg-success
                                    @elseif($booking->status === 'active') bg-primary
                                    @elseif($booking->status === 'cancelled') bg-danger
                                    @elseif($booking->status === 'pending') bg-warning
                                    @else bg-secondary
                                    @endif">
                                    {{ ucfirst($booking->status) }}
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada booking</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Recent Ratings Given -->
    <div class="col-lg-6 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bi bi-star me-2"></i>Rating yang Diberikan
                </h5>
            </div>
            <div class="card-body">
                @forelse($ratingsGiven as $rating)
                    <div class="border rounded p-3 mb-3">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="fw-bold mb-1">
                                    {{ $rating->motor ? $rating->motor->brand . ' ' . $rating->motor->model : 'Motor Tidak Ditemukan' }}
                                </h6>
                                <div class="d-flex align-items-center mb-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star{{ $i <= $rating->rating ? '-fill text-warning' : ' text-muted' }}"></i>
                                    @endfor
                                    <span class="ms-2 text-muted small">({{ $rating->rating }}/5)</span>
                                </div>
                                @if($rating->review)
                                    <p class="text-muted small mb-2">{{ Str::limit($rating->review, 100) }}</p>
                                @endif
                                <p class="text-muted small mb-0">{{ $rating->created_at->format('d M Y') }}</p>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <i class="bi bi-star text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Belum ada rating yang diberikan</p>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- Export Section -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="card-title mb-0">
                    <i class="bi bi-download me-2"></i>Export Laporan
                </h5>
            </div>
            <div class="card-body">
                <div class="d-flex gap-3">
                    <button onclick="exportReport('pdf')" class="btn btn-danger">
                        <i class="bi bi-file-pdf me-2"></i>Export PDF
                    </button>
                    <button onclick="exportReport('excel')" class="btn btn-success">
                        <i class="bi bi-file-excel me-2"></i>Export Excel
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

    </div>
</div>

@push('scripts')
<script>
function exportReport(format) {
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Exporting...';
    button.disabled = true;
    
    fetch(`{{ route('penyewa.reports.export') }}?format=${format}`)
        .then(response => response.json())
        .then(data => {
            console.log('Export data:', data);
            alert('Export berhasil! Data tersedia di console browser.');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Terjadi error saat export');
        })
        .finally(() => {
            button.innerHTML = originalText;
            button.disabled = false;
        });
}
</script>
@endpush
@endsection