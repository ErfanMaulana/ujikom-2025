@extends('layouts.fann')

@section('title', 'Dashboard Penyewa')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Dashboard Penyewa</h1>
    <p>Temukan dan sewa motor impian Anda dengan mudah</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(59, 130, 246, 0.1); color: var(--primary-color);">
                <i class="bi bi-calendar-check"></i>
            </div>
            <h3 class="stat-number">{{ $totalBookings }}</h3>
            <p class="stat-label">Total Pemesanan</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(245, 158, 11, 0.1); color: var(--warning-color);">
                <i class="bi bi-clock"></i>
            </div>
            <h3 class="stat-number">{{ $activeBookings }}</h3>
            <p class="stat-label">Sedang Berlangsung</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(16, 185, 129, 0.1); color: var(--success-color);">
                <i class="bi bi-check-circle"></i>
            </div>
            <h3 class="stat-number">{{ $completedBookings }}</h3>
            <p class="stat-label">Selesai</p>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-4">
        <div class="card stat-card">
            <div class="stat-icon" style="background: rgba(6, 182, 212, 0.1); color: var(--info-color);">
                <i class="bi bi-currency-dollar"></i>
            </div>
            <h3 class="stat-number">Rp {{ number_format($totalSpent, 0, ',', '.') }}</h3>
            <p class="stat-label">Total Pengeluaran</p>
        </div>
    </div>
</div>

<!-- Featured Motors and Recent Bookings -->
<div class="row">
    <!-- Featured Motors -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-star me-2"></i>
                        Motor Rekomendasi
                    </h5>
                    <a href="{{ route('penyewa.motors') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($featuredMotors->count() > 0)
                    <div class="row">
                        @foreach($featuredMotors as $motor)
                        <div class="col-md-6 mb-3">
                            <div class="card h-100 border">
                                @if($motor->photo)
                                    <img src="{{ Storage::url($motor->photo) }}" 
                                         class="card-img-top" 
                                         alt="{{ $motor->brand }} {{ $motor->model }}" 
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-motorcycle text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body">
                                    <h6 class="card-title">{{ $motor->brand }} {{ $motor->model }}</h6>
                                    <p class="text-muted mb-2">{{ $motor->type_cc }} • {{ $motor->year }}</p>
                                    @if($motor->rentalRate)
                                        <p class="text-primary fw-bold mb-2">
                                            Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}/hari
                                        </p>
                                    @endif
                                    <button type="button" 
                                            class="btn btn-primary btn-sm w-100"
                                            onclick="showMotorDetail({{ $motor->id }})">
                                        <i class="bi bi-eye me-1"></i>Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-motorcycle text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3 text-muted">Belum ada motor tersedia</h6>
                        <p class="text-muted">Motor yang tersedia akan ditampilkan di sini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions and Recent Bookings -->
    <div class="col-lg-4 mb-4">
        <!-- Quick Actions -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('penyewa.motors') }}" class="btn btn-primary">
                        <i class="bi bi-search me-2"></i>Cari Motor
                    </a>
                    <a href="{{ route('penyewa.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>Riwayat Pemesanan
                    </a>
                    <a href="{{ route('penyewa.payment.history') }}" class="btn btn-outline-info">
                        <i class="bi bi-credit-card me-2"></i>Riwayat Pembayaran
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Bookings -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-white py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Pemesanan Terbaru
                </h6>
            </div>
            <div class="card-body">
                @if($recentBookings->count() > 0)
                    @foreach($recentBookings as $booking)
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
                            <h6 class="mb-0">{{ $booking->motor->brand }} {{ $booking->motor->model }}</h6>
                            <small class="text-muted">{{ $booking->start_date->format('d M Y') }}</small>
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
                        <p class="text-muted mb-0 mt-2">Belum ada pemesanan</p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Tips Card -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-lightbulb me-2"></i>
                    Tips Penyewa
                </h6>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        <small>Periksa kondisi motor sebelum menyewa</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        <small>Bawa SIM dan identitas saat pengambilan</small>
                    </li>
                    <li class="mb-2">
                        <i class="bi bi-check text-success me-2"></i>
                        <small>Kembalikan motor tepat waktu</small>
                    </li>
                    <li class="mb-0">
                        <i class="bi bi-check text-success me-2"></i>
                        <small>Jaga motor dengan baik selama penyewaan</small>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Motor Detail Modal -->
<div class="modal fade" id="motorDetailModal" tabindex="-1" aria-labelledby="motorDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="motorDetailModalLabel">
                    <i class="bi bi-motorcycle me-2"></i>Detail Motor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="motorDetailContent">
                    <div class="d-flex justify-content-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail motor...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Tutup
                </button>
                <div id="bookingButtonContainer"></div>
            </div>
        </div>
    </div>
</div>

<script>
// Show motor detail in modal
function showMotorDetail(motorId) {
    const modal = new bootstrap.Modal(document.getElementById('motorDetailModal'));
    const content = document.getElementById('motorDetailContent');
    const bookingContainer = document.getElementById('bookingButtonContainer');
    
    // Reset content
    content.innerHTML = `
        <div class="d-flex justify-content-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail motor...</p>
        </div>
    `;
    bookingContainer.innerHTML = '';
    
    // Show modal
    modal.show();
    
    // Fetch motor detail
    fetch(`/penyewa/motors/${motorId}/detail-ajax`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const motor = data.motor;
            
            // Build motor detail HTML
            let rentalRateHtml = '';
            if (motor.rental_rate) {
                rentalRateHtml = `
                    <div class="col-md-6 mb-3">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Tarif Sewa Harian</h6>
                                <div class="h5 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                <small class="text-muted">per hari</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        ${motor.photo ? 
                            `<img src="/storage/${motor.photo}" class="img-fluid rounded" alt="${motor.brand} ${motor.model}">` :
                            `<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                             </div>`
                        }
                    </div>
                    <div class="col-md-6">
                        <h3>${motor.brand} ${motor.model}</h3>
                        <div class="mb-3">
                            <span class="badge bg-primary me-2">${motor.type_cc}</span>
                            <span class="badge bg-success">Tersedia</span>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Tahun:</strong><br>
                                <span class="text-muted">${motor.year}</span>
                            </div>
                            <div class="col-6">
                                <strong>Warna:</strong><br>
                                <span class="text-muted">${motor.color}</span>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-6">
                                <strong>Nomor Plat:</strong><br>
                                <span class="text-muted">${motor.license_plate}</span>
                            </div>
                            <div class="col-6">
                                <strong>Pemilik:</strong><br>
                                <span class="text-muted">${motor.owner.name}</span>
                            </div>
                        </div>
                        
                        ${motor.description ? `
                            <div class="mb-3">
                                <strong>Deskripsi:</strong><br>
                                <p class="text-muted mb-0">${motor.description}</p>
                            </div>
                        ` : ''}
                    </div>
                </div>
                
                ${rentalRateHtml ? `
                    <div class="mt-4">
                        <h5><i class="bi bi-currency-dollar me-2"></i>Harga Sewa</h5>
                        <div class="row">
                            ${rentalRateHtml}
                        </div>
                    </div>
                ` : `
                    <div class="mt-4">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            Harga sewa belum ditentukan untuk motor ini.
                        </div>
                    </div>
                `}
            `;
            
            // Add booking button if rates available
            if (motor.rental_rate) {
                bookingContainer.innerHTML = `
                    <a href="/penyewa/booking/${motor.id}" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i>Sewa Sekarang
                    </a>
                `;
            }
        })
        .catch(error => {
            console.error('Error fetching motor detail:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Error</h6>
                    <p class="mt-2 text-danger">Gagal memuat detail motor.</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="showMotorDetail(${motorId})">
                        <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}
</script>
@endsection