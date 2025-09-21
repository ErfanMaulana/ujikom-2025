@extends('layouts.fann')

@section('title', 'Riwayat Pembayaran')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-credit-card me-3"></i>Riwayat Pembayaran
    </h1>
    <p>Lihat semua riwayat pembayaran dan transaksi Anda</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>Daftar Riwayat Pembayaran
                </h5>
                <a href="{{ route('penyewa.bookings') }}" class="btn btn-outline-primary">
                    <i class="bi bi-calendar-check me-1"></i>Lihat Booking
                </a>
            </div>
            <div class="card-body">
                @if($payments->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Booking</th>
                                    <th>Motor</th>
                                    <th>Jumlah</th>
                                    <th>Metode</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($payments as $payment)
                                    <tr>
                                        <td>
                                            <div>{{ $payment->created_at->format('d M Y') }}</div>
                                            <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                        </td>
                                        <td>
                                            <div>Booking #{{ $payment->booking->id }}</div>
                                            <small class="text-muted">
                                                {{ $payment->booking->start_date->format('d M Y') }} - 
                                                {{ $payment->booking->end_date->format('d M Y') }}
                                            </small>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($payment->booking->motor->photo)
                                                    <img src="{{ Storage::url($payment->booking->motor->photo) }}" 
                                                         alt="{{ $payment->booking->motor->brand }}"
                                                         class="rounded me-2"
                                                         style="width: 40px; height: 40px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" 
                                                         style="width: 40px; height: 40px;">
                                                        <i class="bi bi-motorcycle text-muted"></i>
                                                    </div>
                                                @endif
                                                <div>
                                                    <div class="fw-semibold">{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</div>
                                                    <small class="text-muted">{{ $payment->booking->motor->type_cc }}</small>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            @php
                                                $methodLabels = [
                                                    'cash' => 'Tunai',
                                                    'bank_transfer' => 'Transfer Bank',
                                                    'e_wallet' => 'E-Wallet',
                                                    'credit_card' => 'Kartu Kredit'
                                                ];
                                                $methodColors = [
                                                    'cash' => 'success',
                                                    'bank_transfer' => 'info',
                                                    'e_wallet' => 'warning',
                                                    'credit_card' => 'primary'
                                                ];
                                            @endphp
                                            <span class="badge bg-{{ $methodColors[$payment->method] }}">
                                                {{ $methodLabels[$payment->method] }}
                                            </span>
                                        </td>
                                        <td>
                                            @switch($payment->status)
                                                @case('pending')
                                                    <span class="badge bg-warning">Menunggu</span>
                                                    @break
                                                @case('paid')
                                                    <span class="badge bg-success">Lunas</span>
                                                    @break
                                                @case('failed')
                                                    <span class="badge bg-danger">Gagal</span>
                                                    @break
                                                @case('refunded')
                                                    <span class="badge bg-secondary">Dikembalikan</span>
                                                    @break
                                                @default
                                                    <span class="badge bg-light text-dark">{{ ucfirst($payment->status) }}</span>
                                            @endswitch
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" 
                                                        class="btn btn-sm btn-outline-info"
                                                        onclick="showPaymentDetail({{ $payment->id }})"
                                                        data-bs-toggle="modal" 
                                                        data-bs-target="#paymentDetailModal">
                                                    <i class="bi bi-eye"></i>
                                                </button>
                                                @if($payment->status === 'paid')
                                                    <a href="{{ route('penyewa.payment.invoice', $payment->id) }}" 
                                                       class="btn btn-sm btn-outline-primary"
                                                       target="_blank">
                                                        <i class="bi bi-file-earmark-pdf"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    @if($payments->hasPages())
                        <div class="d-flex justify-content-center mt-4">
                            {{ $payments->links() }}
                        </div>
                    @endif
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-credit-card text-muted" style="font-size: 4rem;"></i>
                        <h4 class="mt-3 text-muted">Belum Ada Riwayat Pembayaran</h4>
                        <p class="text-muted">Anda belum memiliki riwayat pembayaran.</p>
                        <a href="{{ route('penyewa.motors') }}" class="btn btn-primary">
                            <i class="bi bi-motorcycle me-1"></i>Sewa Motor Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Payment Detail Modal -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1" aria-labelledby="paymentDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paymentDetailModalLabel">
                    <i class="bi bi-receipt me-2"></i>Detail Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="paymentDetailContent">
                <div class="d-flex justify-content-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg me-1"></i>Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showPaymentDetail(paymentId) {
    const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
    const content = document.getElementById('paymentDetailContent');
    
    // Reset content
    content.innerHTML = `
        <div class="d-flex justify-content-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail pembayaran...</p>
        </div>
    `;
    
    // Show modal
    modal.show();
    
    // Fetch payment detail
    fetch(`/penyewa/payments/${paymentId}/detail`)
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            const payment = data.payment;
            
            // Build payment detail HTML
            let statusBadge = '';
            switch(payment.status) {
                case 'pending':
                    statusBadge = '<span class="badge bg-warning fs-6">Menunggu</span>';
                    break;
                case 'paid':
                    statusBadge = '<span class="badge bg-success fs-6">Lunas</span>';
                    break;
                case 'failed':
                    statusBadge = '<span class="badge bg-danger fs-6">Gagal</span>';
                    break;
                case 'refunded':
                    statusBadge = '<span class="badge bg-secondary fs-6">Dikembalikan</span>';
                    break;
            }
            
            const methodLabels = {
                'cash': 'Tunai',
                'bank_transfer': 'Transfer Bank',
                'e_wallet': 'E-Wallet',
                'credit_card': 'Kartu Kredit'
            };
            
            content.innerHTML = `
                <div class="row">
                    <div class="col-md-6">
                        <h6>Informasi Pembayaran</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>ID Pembayaran:</strong></td>
                                <td>#${payment.id}</td>
                            </tr>
                            <tr>
                                <td><strong>Tanggal:</strong></td>
                                <td>${new Date(payment.created_at).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</td>
                            </tr>
                            <tr>
                                <td><strong>Jumlah:</strong></td>
                                <td><strong class="text-primary">Rp ${new Intl.NumberFormat('id-ID').format(payment.amount)}</strong></td>
                            </tr>
                            <tr>
                                <td><strong>Metode:</strong></td>
                                <td>${methodLabels[payment.method]}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>${statusBadge}</td>
                            </tr>
                            ${payment.paid_at ? `
                            <tr>
                                <td><strong>Dibayar pada:</strong></td>
                                <td>${new Date(payment.paid_at).toLocaleDateString('id-ID', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}</td>
                            </tr>
                            ` : ''}
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h6>Informasi Booking</h6>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Booking ID:</strong></td>
                                <td>#${payment.booking.id}</td>
                            </tr>
                            <tr>
                                <td><strong>Motor:</strong></td>
                                <td>${payment.booking.motor.brand} ${payment.booking.motor.model}</td>
                            </tr>
                            <tr>
                                <td><strong>Periode:</strong></td>
                                <td>${new Date(payment.booking.start_date).toLocaleDateString('id-ID')} - ${new Date(payment.booking.end_date).toLocaleDateString('id-ID')}</td>
                            </tr>
                            <tr>
                                <td><strong>Durasi:</strong></td>
                                <td>${payment.booking.duration_days || Math.ceil((new Date(payment.booking.end_date) - new Date(payment.booking.start_date)) / (1000 * 60 * 60 * 24)) + 1} hari</td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                ${payment.notes ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Catatan</h6>
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>${payment.notes}
                        </div>
                    </div>
                </div>
                ` : ''}
                
                ${payment.payment_proof ? `
                <div class="row mt-3">
                    <div class="col-12">
                        <h6>Bukti Pembayaran</h6>
                        <div class="text-center">
                            <img src="${payment.payment_proof}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 300px;">
                        </div>
                    </div>
                </div>
                ` : ''}
            `;
        })
        .catch(error => {
            console.error('Error fetching payment detail:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <h6><i class="bi bi-exclamation-triangle me-2"></i>Error</h6>
                    <p class="mt-2 text-danger">Gagal memuat detail pembayaran.</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="showPaymentDetail(${paymentId})">
                        <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                    </button>
                </div>
            `;
        });
}
</script>
@endsection