@extends('layouts.fann')

@section('title', 'Form Pembayaran')

@section('content')
<div class="content-header">
    <h1>
        <i class="bi bi-credit-card me-3"></i>Form Pembayaran
    </h1>
    <p>Silakan lakukan pembayaran untuk menyelesaikan booking Anda</p>
</div>

<div class="row">
    <div class="col-lg-8">
        <form action="{{ route('penyewa.payment.process', $booking->id) }}" method="POST" id="paymentForm">
            @csrf
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-file-text me-2"></i>Detail Pembayaran
                    </h5>
                </div>
                <div class="card-body">
                    <!-- Payment Method Selection -->
                    <div class="mb-4">
                        <label for="payment_method" class="form-label">
                            <i class="bi bi-wallet me-2"></i>Metode Pembayaran *
                        </label>
                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                id="payment_method" 
                                name="payment_method" 
                                required>
                            <option value="">Pilih Metode Pembayaran</option>
                            <option value="transfer_bank" {{ old('payment_method') == 'transfer_bank' ? 'selected' : '' }}>
                                Transfer Bank
                            </option>
                            <option value="e_wallet" {{ old('payment_method') == 'e_wallet' ? 'selected' : '' }}>
                                E-Wallet (GoPay, OVO, DANA, dll)
                            </option>
                            <option value="cash" {{ old('payment_method') == 'cash' ? 'selected' : '' }}>
                                Tunai/Cash
                            </option>
                        </select>
                        @error('payment_method')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        
                        <!-- Payment Instructions -->
                        <div class="mt-3" id="paymentInstructions" style="display: none;">
                            <div class="alert alert-info">
                                <div id="bankTransferInfo" style="display: none;">
                                    <h6><i class="bi bi-bank me-2"></i>Transfer Bank</h6>
                                    <p class="mb-2"><strong>Bank BCA:</strong> 1234567890 a.n. FannRental</p>
                                    <p class="mb-2"><strong>Bank Mandiri:</strong> 0987654321 a.n. FannRental</p>
                                    <p class="mb-0"><small>Pembayaran akan otomatis terverifikasi setelah transfer berhasil.</small></p>
                                </div>
                                <div id="eWalletInfo" style="display: none;">
                                    <h6><i class="bi bi-phone me-2"></i>E-Wallet</h6>
                                    <p class="mb-2"><strong>GoPay:</strong> 081234567890</p>
                                    <p class="mb-2"><strong>OVO:</strong> 081234567890</p>
                                    <p class="mb-2"><strong>DANA:</strong> 081234567890</p>
                                    <p class="mb-0"><small>Pembayaran akan otomatis terverifikasi setelah transfer berhasil.</small></p>
                                </div>
                                <div id="cashInfo" style="display: none;">
                                    <h6><i class="bi bi-cash me-2"></i>Pembayaran Tunai</h6>
                                    <p class="mb-0">Pembayaran tunai akan dilakukan saat pengambilan motor. Silakan koordinasi dengan pemilik motor.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Payment Notes -->
                    <div class="mb-4">
                        <label for="payment_notes" class="form-label">
                            <i class="bi bi-chat-text me-2"></i>Catatan Pembayaran (Opsional)
                        </label>
                        <textarea class="form-control @error('payment_notes') is-invalid @enderror" 
                                  id="payment_notes" 
                                  name="payment_notes" 
                                  rows="3"
                                  placeholder="Tambahkan catatan atau informasi tambahan mengenai pembayaran...">{{ old('payment_notes') }}</textarea>
                        @error('payment_notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            Contoh: "Transfer dari rekening atas nama John Doe" atau "Pembayaran melalui GoPay"
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('penyewa.booking.detail', $booking->id) }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                        <button type="submit" class="btn btn-success" id="submitBtn">
                            <i class="bi bi-check-circle me-1"></i>Submit Pembayaran
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!-- Payment Summary -->
    <div class="col-lg-4">
        <div class="card sticky-top">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="bi bi-receipt me-2"></i>Ringkasan Pembayaran
                </h5>
            </div>
            <div class="card-body">
                <!-- Motor Info -->
                <div class="d-flex mb-3">
                    @if($booking->motor->photo)
                        <img src="{{ Storage::url($booking->motor->photo) }}" 
                             alt="{{ $booking->motor->brand }} {{ $booking->motor->model }}"
                             class="rounded me-3"
                             style="width: 60px; height: 60px; object-fit: cover;">
                    @else
                        <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                             style="width: 60px; height: 60px;">
                            <i class="bi bi-motorcycle text-muted"></i>
                        </div>
                    @endif
                    <div>
                        <h6 class="mb-1">{{ $booking->motor->brand }} {{ $booking->motor->model }}</h6>
                        <small class="text-muted">{{ $booking->motor->type_cc }} • {{ $booking->motor->year }}</small>
                    </div>
                </div>

                <hr>

                <!-- Booking Details -->
                <div class="mb-3">
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-calendar-event me-1"></i>Tanggal Mulai:</span>
                        <span>{{ $booking->start_date->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-calendar-check me-1"></i>Tanggal Selesai:</span>
                        <span>{{ $booking->end_date->format('d M Y') }}</span>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span><i class="bi bi-clock me-1"></i>Durasi:</span>
                        @php
                            $days = $booking->start_date->diffInDays($booking->end_date) + 1;
                        @endphp
                        <span>{{ $days }} hari</span>
                    </div>
                    @if($booking->package_type)
                        <div class="d-flex justify-content-between mb-2">
                            <span><i class="bi bi-tag me-1"></i>Paket:</span>
                            <span>
                                @php
                                    $packageLabels = [
                                        'daily' => 'Harian',
                                        'weekly' => 'Mingguan', 
                                        'monthly' => 'Bulanan'
                                    ];
                                @endphp
                                {{ $packageLabels[$booking->package_type] ?? $booking->package_type }}
                            </span>
                        </div>
                    @endif
                </div>

                <hr>

                <!-- Price Calculation -->
                <div class="mb-3">
                    @if($booking->motor->rentalRate)
                        <div class="d-flex justify-content-between mb-2">
                            <span>Tarif per hari:</span>
                            <span>Rp {{ number_format($booking->motor->rentalRate->daily_rate, 0, ',', '.') }}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Durasi ({{ $days }} hari):</span>
                            <span>Rp {{ number_format($booking->motor->rentalRate->daily_rate * $days, 0, ',', '.') }}</span>
                        </div>
                        
                        @if($booking->package_type === 'weekly')
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span><small>Diskon mingguan (10%):</small></span>
                                <span><small>-Rp {{ number_format(($booking->motor->rentalRate->daily_rate * $days) * 0.1, 0, ',', '.') }}</small></span>
                            </div>
                        @elseif($booking->package_type === 'monthly')
                            <div class="d-flex justify-content-between mb-2 text-success">
                                <span><small>Diskon bulanan (20%):</small></span>
                                <span><small>-Rp {{ number_format(($booking->motor->rentalRate->daily_rate * $days) * 0.2, 0, ',', '.') }}</small></span>
                            </div>
                        @endif
                    @endif
                </div>

                <hr>

                <!-- Total -->
                <div class="d-flex justify-content-between">
                    <strong class="fs-5">Total Pembayaran:</strong>
                    <strong class="fs-5 text-primary">Rp {{ number_format($booking->price, 0, ',', '.') }}</strong>
                </div>

                <!-- Owner Contact -->
                <div class="mt-4 p-3 bg-light rounded">
                    <h6><i class="bi bi-person me-2"></i>Kontak Pemilik</h6>
                    <p class="mb-1"><strong>{{ $booking->motor->owner->name }}</strong></p>
                    <p class="mb-1"><small>{{ $booking->motor->owner->email }}</small></p>
                    @if($booking->motor->owner->phone)
                        <p class="mb-0"><small><i class="bi bi-phone me-1"></i>{{ $booking->motor->owner->phone }}</small></p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    const paymentInstructions = document.getElementById('paymentInstructions');
    const submitBtn = document.getElementById('submitBtn');
    
    // Payment method change handler
    paymentMethodSelect.addEventListener('change', function() {
        const selectedMethod = this.value;
        
        // Hide all instruction divs first
        document.getElementById('bankTransferInfo').style.display = 'none';
        document.getElementById('eWalletInfo').style.display = 'none';
        document.getElementById('cashInfo').style.display = 'none';
        
        if (selectedMethod) {
            paymentInstructions.style.display = 'block';
            
            // Show relevant instructions
            if (selectedMethod === 'transfer_bank') {
                document.getElementById('bankTransferInfo').style.display = 'block';
            } else if (selectedMethod === 'e_wallet') {
                document.getElementById('eWalletInfo').style.display = 'block';
            } else if (selectedMethod === 'cash') {
                document.getElementById('cashInfo').style.display = 'block';
            }
        } else {
            paymentInstructions.style.display = 'none';
        }
    });
    
    // Form submission validation
    document.getElementById('paymentForm').addEventListener('submit', function(e) {
        const paymentMethod = paymentMethodSelect.value;
        
        if (!paymentMethod) {
            e.preventDefault();
            alert('Silakan pilih metode pembayaran.');
            paymentMethodSelect.focus();
            return;
        }
        
        // Show loading state
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Memproses...';
    });
});

// Trigger change event on page load if method was selected (for old input)
window.addEventListener('load', function() {
    const paymentMethodSelect = document.getElementById('payment_method');
    if (paymentMethodSelect.value) {
        paymentMethodSelect.dispatchEvent(new Event('change'));
    }
});
</script>

@endsection