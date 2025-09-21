<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice #{{ $payment->id }} - FannRental</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        @media print {
            .no-print { display: none !important; }
            .card { border: none !important; box-shadow: none !important; }
        }
        .invoice-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 2rem;
            border-radius: 10px 10px 0 0;
        }
        .invoice-detail {
            border-left: 4px solid #667eea;
            padding-left: 1rem;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow">
                    <!-- Invoice Header -->
                    <div class="invoice-header">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <h1 class="mb-0">
                                    <i class="bi bi-receipt me-2"></i>INVOICE
                                </h1>
                                <h4 class="mt-2 mb-0">#{{ $payment->id }}</h4>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <h3 class="mb-0">FannRental</h3>
                                <p class="mb-0">Sistem Rental Motor</p>
                                <small>{{ now()->format('d F Y') }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <!-- Payment Status -->
                        <div class="row mb-4">
                            <div class="col-12 text-center">
                                @if($payment->status === 'paid')
                                    <span class="badge bg-success fs-4 px-4 py-2">
                                        <i class="bi bi-check-circle me-2"></i>LUNAS
                                    </span>
                                @else
                                    <span class="badge bg-warning fs-4 px-4 py-2">
                                        <i class="bi bi-clock me-2"></i>{{ strtoupper($payment->status) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        
                        <!-- Invoice Details -->
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="invoice-detail">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-person me-2"></i>Informasi Penyewa
                                    </h6>
                                    <p class="mb-1"><strong>{{ $payment->booking->renter->name }}</strong></p>
                                    <p class="mb-1">{{ $payment->booking->renter->email }}</p>
                                    <p class="mb-0">{{ $payment->booking->renter->phone ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="invoice-detail">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-building me-2"></i>Informasi Pemilik
                                    </h6>
                                    <p class="mb-1"><strong>{{ $payment->booking->motor->owner->name }}</strong></p>
                                    <p class="mb-1">{{ $payment->booking->motor->owner->email }}</p>
                                    <p class="mb-0">{{ $payment->booking->motor->owner->phone ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Booking Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-calendar-check me-2"></i>Detail Booking
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Booking ID:</strong></td>
                                            <td>#{{ $payment->booking->id }}</td>
                                            <td width="150"><strong>Tanggal Booking:</strong></td>
                                            <td>{{ $payment->booking->created_at->format('d M Y H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Motor:</strong></td>
                                            <td>{{ $payment->booking->motor->brand }} {{ $payment->booking->motor->model }}</td>
                                            <td><strong>Type:</strong></td>
                                            <td>{{ $payment->booking->motor->type_cc }} • {{ $payment->booking->motor->year }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>Periode Sewa:</strong></td>
                                            <td>{{ $payment->booking->start_date->format('d M Y') }} - {{ $payment->booking->end_date->format('d M Y') }}</td>
                                            <td><strong>Durasi:</strong></td>
                                            <td>{{ $payment->booking->duration_days ?? $payment->booking->start_date->diffInDays($payment->booking->end_date) + 1 }} hari</td>
                                        </tr>
                                        @if($payment->booking->package_type)
                                        <tr>
                                            <td><strong>Paket:</strong></td>
                                            <td>
                                                @php
                                                    $packageLabels = [
                                                        'daily' => 'Harian',
                                                        'weekly' => 'Mingguan',
                                                        'monthly' => 'Bulanan'
                                                    ];
                                                @endphp
                                                {{ $packageLabels[$payment->booking->package_type] ?? $payment->booking->package_type }}
                                            </td>
                                            <td><strong>Status:</strong></td>
                                            <td>
                                                <span class="badge bg-{{ $payment->booking->status === 'confirmed' ? 'success' : ($payment->booking->status === 'pending' ? 'warning' : 'info') }}">
                                                    {{ ucfirst($payment->booking->status) }}
                                                </span>
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Payment Information -->
                        <div class="row mb-4">
                            <div class="col-12">
                                <h6 class="text-primary mb-3">
                                    <i class="bi bi-credit-card me-2"></i>Informasi Pembayaran
                                </h6>
                                <div class="table-responsive">
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="150"><strong>Tanggal Bayar:</strong></td>
                                            <td>{{ $payment->paid_at ? $payment->paid_at->format('d M Y H:i') : '-' }}</td>
                                            <td width="150"><strong>Metode:</strong></td>
                                            <td>
                                                @php
                                                    $methodLabels = [
                                                        'cash' => 'Tunai',
                                                        'bank_transfer' => 'Transfer Bank',
                                                        'e_wallet' => 'E-Wallet',
                                                        'credit_card' => 'Kartu Kredit'
                                                    ];
                                                @endphp
                                                {{ $methodLabels[$payment->method] }}
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Amount Summary -->
                        <div class="row">
                            <div class="col-md-6 offset-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row mb-2">
                                            <div class="col-6"><strong>Subtotal:</strong></div>
                                            <div class="col-6 text-end">Rp {{ number_format($payment->amount, 0, ',', '.') }}</div>
                                        </div>
                                        <div class="row mb-2">
                                            <div class="col-6">Diskon:</div>
                                            <div class="col-6 text-end">
                                                @if($payment->booking->package_type === 'weekly')
                                                    -10%
                                                @elseif($payment->booking->package_type === 'monthly')
                                                    -20%
                                                @else
                                                    0%
                                                @endif
                                            </div>
                                        </div>
                                        <hr>
                                        <div class="row">
                                            <div class="col-6"><strong class="h5">Total:</strong></div>
                                            <div class="col-6 text-end"><strong class="h5 text-primary">Rp {{ number_format($payment->amount, 0, ',', '.') }}</strong></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        @if($payment->notes)
                        <div class="row mt-4">
                            <div class="col-12">
                                <div class="alert alert-info">
                                    <strong>Catatan:</strong><br>
                                    {{ $payment->notes }}
                                </div>
                            </div>
                        </div>
                        @endif
                        
                        <!-- Footer -->
                        <div class="row mt-5 pt-4 border-top">
                            <div class="col-md-6">
                                <p class="small text-muted mb-0">
                                    <strong>FannRental - Sistem Rental Motor</strong><br>
                                    Terima kasih atas kepercayaan Anda!
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <p class="small text-muted mb-0">
                                    Invoice dibuat pada: {{ now()->format('d F Y H:i') }}<br>
                                    Invoice ini sah dan ditandatangani secara elektronik.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="card-footer bg-light no-print">
                        <div class="row">
                            <div class="col-md-6">
                                <a href="{{ route('penyewa.payment.history') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left me-1"></i>Kembali ke Riwayat
                                </a>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <button onclick="window.print()" class="btn btn-primary">
                                    <i class="bi bi-printer me-1"></i>Cetak Invoice
                                </button>
                                <button onclick="downloadPDF()" class="btn btn-success ms-2">
                                    <i class="bi bi-download me-1"></i>Download PDF
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        function downloadPDF() {
            // Simple PDF download simulation
            window.print();
        }
    </script>
</body>
</html>