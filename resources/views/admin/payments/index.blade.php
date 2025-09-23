@extends('layouts.fann')

@section('title', 'Verifikasi Pembayaran')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-credit-card me-3"></i>Verifikasi Pembayaran
    </h1>
    <p>Kelola dan verifikasi pembayaran dari penyewa</p>
</div>

<!-- Summary Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $summary['total_payments'] }}</h4>
                        <p class="card-text">Total Pembayaran</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-receipt" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $summary['unverified_payments'] }}</h4>
                        <p class="card-text">Menunggu Verifikasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-clock" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h4 class="card-title">{{ $summary['verified_payments'] }}</h4>
                        <p class="card-text">Sudah Diverifikasi</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-check-circle" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Rp {{ number_format($summary['pending_amount'], 0, ',', '.') }}</h6>
                        <p class="card-text">Nilai Pending</p>
                    </div>
                    <div class="align-self-center">
                        <i class="bi bi-currency-dollar" style="font-size: 2rem;"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.payments') }}" class="row g-3">
            <div class="col-md-3">
                <label for="status" class="form-label">Status Verifikasi</label>
                <select class="form-select" id="status" name="status">
                    <option value="">Semua Status</option>
                    <option value="unverified" {{ request('status') === 'unverified' ? 'selected' : '' }}>
                        Belum Diverifikasi
                    </option>
                    <option value="verified" {{ request('status') === 'verified' ? 'selected' : '' }}>
                        Sudah Diverifikasi
                    </option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>
                        Pending
                    </option>
                    <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>
                        Lunas
                    </option>
                    <option value="failed" {{ request('status') === 'failed' ? 'selected' : '' }}>
                        Gagal
                    </option>
                </select>
            </div>
            <div class="col-md-3">
                <label for="payment_method" class="form-label">Metode Pembayaran</label>
                <select class="form-select" id="payment_method" name="payment_method">
                    <option value="">Semua Metode</option>
                    <option value="transfer_bank" {{ request('payment_method') === 'transfer_bank' ? 'selected' : '' }}>
                        Transfer Bank
                    </option>
                    <option value="e_wallet" {{ request('payment_method') === 'e_wallet' ? 'selected' : '' }}>
                        E-Wallet
                    </option>
                    <option value="cash" {{ request('payment_method') === 'cash' ? 'selected' : '' }}>
                        Tunai
                    </option>
                </select>
            </div>
            <div class="col-md-4">
                <label for="search" class="form-label">Cari</label>
                <input type="text" class="form-control" id="search" name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Nama penyewa, email, atau ID booking...">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Filter
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Payments Table -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">
            <i class="bi bi-list-ul me-2"></i>Daftar Pembayaran
        </h5>
    </div>
    <div class="card-body">
        @if($payments->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>ID</th>
                            <th>Penyewa</th>
                            <th>Motor</th>
                            <th>Booking ID</th>
                            <th>Jumlah</th>
                            <th>Metode</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($payments as $payment)
                        <tr>
                            <td>
                                <strong>#{{ $payment->id }}</strong>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $payment->booking->renter->name }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payment->booking->renter->email }}</small>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</strong>
                                    <br>
                                    <small class="text-muted">{{ $payment->booking->motor->type_cc }}</small>
                                </div>
                            </td>
                            <td>
                                <a href="{{ route('admin.bookings.show', $payment->booking->id) }}" 
                                   class="btn btn-sm btn-outline-primary">
                                    #{{ $payment->booking->id }}
                                </a>
                            </td>
                            <td>
                                <strong class="text-success">
                                    Rp {{ number_format($payment->amount, 0, ',', '.') }}
                                </strong>
                            </td>
                            <td>
                                <span class="badge bg-secondary">
                                    {{ $payment->formatted_payment_method }}
                                </span>
                            </td>
                            <td>
                                @if($payment->verified_at)
                                    @if($payment->status === 'paid')
                                        <span class="badge bg-success">
                                            <i class="bi bi-check-circle me-1"></i>Diverifikasi
                                        </span>
                                    @else
                                        <span class="badge bg-danger">
                                            <i class="bi bi-x-circle me-1"></i>Ditolak
                                        </span>
                                    @endif
                                    <br>
                                    <small class="text-muted">
                                        oleh {{ $payment->verifiedBy->name }}
                                    </small>
                                @else
                                    <span class="badge bg-warning">
                                        <i class="bi bi-clock me-1"></i>Menunggu Verifikasi
                                    </span>
                                @endif
                            </td>
                            <td>
                                <div>
                                    {{ $payment->created_at->format('d M Y') }}
                                    <br>
                                    <small class="text-muted">{{ $payment->created_at->format('H:i') }}</small>
                                </div>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-info" 
                                            onclick="showPaymentDetail({{ $payment->id }})"
                                            title="Lihat Detail">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    
                                    @if(!$payment->verified_at)
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                onclick="verifyPayment({{ $payment->id }}, 'approve')"
                                                title="Verifikasi">
                                            <i class="bi bi-check"></i>
                                        </button>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                onclick="verifyPayment({{ $payment->id }}, 'reject')"
                                                title="Tolak">
                                            <i class="bi bi-x"></i>
                                        </button>
                                    @else
                                        <a href="{{ route('admin.payments.show', $payment->id) }}" 
                                           class="btn btn-sm btn-outline-primary"
                                           title="Detail">
                                            <i class="bi bi-info-circle"></i>
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
            <div class="d-flex justify-content-center mt-4">
                {{ $payments->withQueryString()->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-receipt text-muted" style="font-size: 4rem;"></i>
                <h5 class="text-muted mt-3">Tidak ada pembayaran ditemukan</h5>
                <p class="text-muted">Belum ada pembayaran yang perlu diverifikasi.</p>
            </div>
        @endif
    </div>
</div>

<!-- Payment Detail Modal -->
<div class="modal fade" id="paymentDetailModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-credit-card me-2"></i>Detail Pembayaran
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="paymentDetailContent">
                <!-- Content will be loaded via AJAX -->
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat detail pembayaran...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Payment Verification Modal -->
<div class="modal fade" id="verifyPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="verifyForm" method="POST">
                @csrf
                @method('PATCH')
                <div class="modal-header">
                    <h5 class="modal-title" id="verifyModalTitle"></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="verification_notes" class="form-label">Catatan Verifikasi</label>
                        <textarea class="form-control" id="verification_notes" name="verification_notes" 
                                  rows="3" placeholder="Tambahkan catatan verifikasi (opsional)..."></textarea>
                    </div>
                    <input type="hidden" id="verify_action" name="action">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn" id="verifySubmitBtn">Konfirmasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showPaymentDetail(paymentId) {
    const modal = new bootstrap.Modal(document.getElementById('paymentDetailModal'));
    const content = document.getElementById('paymentDetailContent');
    
    // Reset content
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail pembayaran...</p>
        </div>
    `;
    
    modal.show();
    
    // Load payment detail via AJAX
    fetch(`{{ route('admin.payments.detail.ajax', ':id') }}`.replace(':id', paymentId))
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                content.innerHTML = generatePaymentDetailHTML(data);
            } else {
                content.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Gagal memuat detail pembayaran.
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            content.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat memuat data.
                </div>
            `;
        });
}

function generatePaymentDetailHTML(data) {
    const payment = data.payment;
    const booking = data.booking;
    const renter = data.renter;
    const motor = data.motor;
    
    let proofImage = '';
    if (data.payment_proof_url) {
        proofImage = `
            <div class="mb-3">
                <h6><i class="bi bi-image me-2"></i>Bukti Pembayaran</h6>
                <img src="${data.payment_proof_url}" alt="Bukti Pembayaran" class="img-fluid rounded" style="max-height: 300px;">
            </div>
        `;
    }
    
    let verificationInfo = '';
    if (payment.verified_at) {
        verificationInfo = `
            <div class="alert alert-info">
                <h6><i class="bi bi-check-circle me-2"></i>Informasi Verifikasi</h6>
                <p class="mb-1"><strong>Diverifikasi oleh:</strong> ${data.verified_by.name}</p>
                <p class="mb-1"><strong>Waktu verifikasi:</strong> ${new Date(payment.verified_at).toLocaleString('id-ID')}</p>
                <p class="mb-0"><strong>Status:</strong> 
                    <span class="badge bg-${payment.status === 'paid' ? 'success' : 'danger'}">
                        ${payment.status === 'paid' ? 'Disetujui' : 'Ditolak'}
                    </span>
                </p>
            </div>
        `;
    }
    
    return `
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-person me-2"></i>Informasi Penyewa</h6>
                <table class="table table-sm">
                    <tr><td>Nama:</td><td><strong>${renter.name}</strong></td></tr>
                    <tr><td>Email:</td><td>${renter.email}</td></tr>
                    <tr><td>Telepon:</td><td>${renter.phone || '-'}</td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-motorcycle me-2"></i>Informasi Motor</h6>
                <table class="table table-sm">
                    <tr><td>Motor:</td><td><strong>${motor.brand} ${motor.model}</strong></td></tr>
                    <tr><td>Type:</td><td>${motor.type_cc}</td></tr>
                    <tr><td>Tahun:</td><td>${motor.year}</td></tr>
                </table>
            </div>
        </div>
        
        <hr>
        
        <div class="row">
            <div class="col-md-6">
                <h6><i class="bi bi-calendar me-2"></i>Detail Booking</h6>
                <table class="table table-sm">
                    <tr><td>Booking ID:</td><td><strong>#${booking.id}</strong></td></tr>
                    <tr><td>Tanggal Mulai:</td><td>${new Date(booking.start_date).toLocaleDateString('id-ID')}</td></tr>
                    <tr><td>Tanggal Selesai:</td><td>${new Date(booking.end_date).toLocaleDateString('id-ID')}</td></tr>
                    <tr><td>Total Harga:</td><td><strong class="text-success">${data.formatted_amount}</strong></td></tr>
                </table>
            </div>
            <div class="col-md-6">
                <h6><i class="bi bi-credit-card me-2"></i>Detail Pembayaran</h6>
                <table class="table table-sm">
                    <tr><td>Payment ID:</td><td><strong>#${payment.id}</strong></td></tr>
                    <tr><td>Metode:</td><td><span class="badge bg-secondary">${data.formatted_payment_method}</span></td></tr>
                    <tr><td>Tanggal:</td><td>${new Date(payment.created_at).toLocaleString('id-ID')}</td></tr>
                    <tr><td>Catatan:</td><td>${payment.payment_notes || '-'}</td></tr>
                </table>
            </div>
        </div>
        
        <hr>
        
        ${proofImage}
        ${verificationInfo}
        
        ${!payment.verified_at ? `
            <div class="d-flex gap-2 mt-3">
                <button type="button" class="btn btn-success" onclick="verifyPayment(${payment.id}, 'approve')">
                    <i class="bi bi-check-circle me-1"></i>Verifikasi & Setujui
                </button>
                <button type="button" class="btn btn-danger" onclick="verifyPayment(${payment.id}, 'reject')">
                    <i class="bi bi-x-circle me-1"></i>Tolak Pembayaran
                </button>
            </div>
        ` : ''}
    `;
}

function verifyPayment(paymentId, action) {
    const modal = new bootstrap.Modal(document.getElementById('verifyPaymentModal'));
    const form = document.getElementById('verifyForm');
    const title = document.getElementById('verifyModalTitle');
    const actionInput = document.getElementById('verify_action');
    const submitBtn = document.getElementById('verifySubmitBtn');
    
    // Set form action
    form.action = `{{ route('admin.payments.verify', ':id') }}`.replace(':id', paymentId);
    actionInput.value = action;
    
    // Set modal content based on action
    if (action === 'approve') {
        title.innerHTML = '<i class="bi bi-check-circle me-2 text-success"></i>Verifikasi & Setujui Pembayaran';
        submitBtn.className = 'btn btn-success';
        submitBtn.innerHTML = '<i class="bi bi-check me-1"></i>Setujui';
    } else {
        title.innerHTML = '<i class="bi bi-x-circle me-2 text-danger"></i>Tolak Pembayaran';
        submitBtn.className = 'btn btn-danger';
        submitBtn.innerHTML = '<i class="bi bi-x me-1"></i>Tolak';
    }
    
    // Close payment detail modal if open
    const paymentDetailModal = bootstrap.Modal.getInstance(document.getElementById('paymentDetailModal'));
    if (paymentDetailModal) {
        paymentDetailModal.hide();
    }
    
    modal.show();
}
</script>

@endsection