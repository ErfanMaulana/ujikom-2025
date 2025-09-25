@extends('layouts.fann')

@section('title', 'Riwayat Pemesanan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-calendar-check me-3"></i>Riwayat Pemesanan
    </h1>
    <p>Kelola dan pantau semua pemesanan motor Anda</p>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Pemesanan</h5>
                <a href="{{ route('penyewa.motors') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Sewa Motor Baru
                </a>
            </div>
            <div class="card-body">
                @if($bookings->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Motor</th>
                                    <th>Paket</th>
                                    <th>Tanggal Sewa</th>
                                    <th>Durasi</th>
                                    <th>Total Harga</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($bookings as $booking)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($booking->motor->photo)
                                                    <img src="{{ Storage::url($booking->motor->photo) }}" 
                                                         alt="{{ $booking->motor->brand }}"
                                                         class="rounded me-2"
                                                         style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded me-2 d-flex align-items-center justify-content-center"
                                                         style="width: 50px; height: 50px;">
                                                        <i class="bi bi-motorcycle text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $booking->motor->brand }} {{ $booking->motor->model }}</div>
                                                    <small class="text-muted">{{ $booking->motor->type_cc }} • {{ $booking->motor->year }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @php
                                                $packageLabels = [
                                                    'daily' => 'Harian',
                                                    'weekly' => 'Mingguan', 
                                                    'monthly' => 'Bulanan'
                                                ];
                                                $packageColors = [
                                                    'daily' => 'primary',
                                                    'weekly' => 'success',
                                                    'monthly' => 'warning'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $packageColors[$booking->package_type ?? 'daily'] }}">
                                                {{ $packageLabels[$booking->package_type ?? 'daily'] }}
                                            </span>
                                        </td>
                                        <td>
                                            <div>{{ $booking->start_date->format('d M Y') }}</div>
                                            <small class="text-muted">s/d {{ $booking->end_date->format('d M Y') }}</small>
                                        </td>
                                        <td>
                                            @php
                                                $days = $booking->start_date->diffInDays($booking->end_date) + 1;
                                            @endphp
                                            {{ $days }} hari
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @switch($booking->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Menunggu Konfirmasi</span>
                                                    @break
                                                @case('confirmed')
                                                    <span class="badge bg-info">Dikonfirmasi</span>
                                                    @break
                                                @case('active')
                                                    <span class="badge bg-success">Aktif</span>
                                                    @break
                                                @case('completed')
                                                    <span class="badge bg-primary">Selesai</span>
                                                    @break
                                                @case('cancelled')
                                                    <span class="badge bg-danger">Dibatalkan</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-outline-primary btn-sm"
                                                        onclick="showBookingDetail({{ $booking->id }})"
                                                        data-bs-toggle="tooltip" title="Lihat Detail">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                
                                                @if($booking->status === 'pending')
                                                    <a href="{{ route('penyewa.payment.form', $booking->id) }}" 
                                                       class="btn btn-success btn-sm"
                                                       data-bs-toggle="tooltip" title="Bayar">
                                                        <i class="bi bi-credit-card"></i>
                                                    </a>
                                                    
                                                    <form action="{{ route('penyewa.booking.cancel', $booking->id) }}" 
                                                          method="POST" 
                                                          class="d-inline"
                                                          onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-outline-danger btn-sm"
                                                                data-bs-toggle="tooltip" title="Batalkan">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="d-flex justify-content-center">
                        {{ $bookings->links() }}
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                        <h5 class="mt-3 text-muted">Belum Ada Pemesanan</h5>
                        <p class="text-muted">Anda belum pernah melakukan pemesanan motor.</p>
                        <a href="{{ route('penyewa.motors') }}" class="btn btn-primary">
                            <i class="bi bi-motorcycle me-1"></i>Sewa Motor Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Modal Detail Booking -->
<div class="modal fade" id="bookingDetailModal" tabindex="-1" aria-labelledby="bookingDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="bookingDetailModalLabel">
                    <i class="bi bi-calendar-check me-2"></i>Detail Pemesanan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="bookingDetailContent">
                <div class="text-center">
                    <div class="spinner-border" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    })

    function showBookingDetail(bookingId) {
        const modal = new bootstrap.Modal(document.getElementById('bookingDetailModal'));
        const content = document.getElementById('bookingDetailContent');
        
        // Show loading
        content.innerHTML = `
            <div class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `;
        
        modal.show();
        
        // Fetch booking detail
        fetch(`/penyewa/bookings/${bookingId}/detail`)
        .then(response => response.json())
        .then(data => {
            const booking = data.booking;
            const motor = booking.motor;
            
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-4">
                        <img src="${motor.photo ? '/storage/' + motor.photo : '/images/default-motor.jpg'}" 
                             alt="${motor.brand} ${motor.model}"
                             class="img-fluid rounded">
                    </div>
                    <div class="col-md-8">
                        <h5>${motor.brand} ${motor.model}</h5>
                        <p class="text-muted">${motor.type_cc} • ${motor.year}</p>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>Tanggal Mulai:</strong><br>
                                ${new Date(booking.start_date).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long', 
                                    year: 'numeric'
                                })}
                            </div>
                            <div class="col-sm-6">
                                <strong>Tanggal Selesai:</strong><br>
                                ${new Date(booking.end_date).toLocaleDateString('id-ID', {
                                    day: 'numeric',
                                    month: 'long',
                                    year: 'numeric'
                                })}
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-sm-6">
                                <strong>Total Harga:</strong><br>
                                <span class="h5 text-primary">Rp ${new Intl.NumberFormat('id-ID').format(booking.price)}</span>
                            </div>
                            <div class="col-sm-6">
                                <strong>Status:</strong><br>
                                <span class="badge bg-${getStatusColor(booking.status)} fs-6">
                                    ${getStatusText(booking.status)}
                                </span>
                            </div>
                        </div>
                        
                        ${booking.notes ? `
                            <hr>
                            <strong>Catatan:</strong><br>
                            <p class="text-muted">${booking.notes}</p>
                        ` : ''}
                    </div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error fetching booking detail:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Gagal memuat detail pemesanan.
                </div>
            `;
        });
    }
    
    function getStatusColor(status) {
        switch(status) {
            case 'pending': return 'warning';
            case 'confirmed': return 'info';
            case 'active': return 'success';
            case 'completed': return 'primary';
            case 'cancelled': return 'danger';
            default: return 'secondary';
        }
    }
    
    function getStatusText(status) {
        switch(status) {
            case 'pending': return 'Menunggu Konfirmasi';
            case 'confirmed': return 'Dikonfirmasi';
            case 'active': return 'Aktif';
            case 'completed': return 'Selesai';
            case 'cancelled': return 'Dibatalkan';
            default: return status;
        }
    }
</script>
@endpush