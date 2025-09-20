<div class="row">
    <div class="col-md-6">
        <h6 class="fw-bold mb-3">Informasi Pemesanan</h6>
        <table class="table table-borderless">
            <tr>
                <td width="40%">ID Booking:</td>
                <td class="fw-bold">#{{ $booking->id }}</td>
            </tr>
            <tr>
                <td>Status:</td>
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
            </tr>
            <tr>
                <td>Tanggal Booking:</td>
                <td>{{ $booking->created_at->format('d M Y H:i') }}</td>
            </tr>
            <tr>
                <td>Periode Sewa:</td>
                <td>
                    {{ $booking->start_date->format('d M Y') }} - {{ $booking->end_date->format('d M Y') }}
                    <br><small class="text-muted">{{ $booking->getDurationInDays() }} hari</small>
                </td>
            </tr>
            <tr>
                <td>Total Biaya:</td>
                <td class="fw-bold text-success">Rp {{ number_format($booking->price, 0, ',', '.') }}</td>
            </tr>
        </table>
    </div>
    <div class="col-md-6">
        <h6 class="fw-bold mb-3">Informasi Penyewa</h6>
        <table class="table table-borderless">
            <tr>
                <td width="40%">Nama:</td>
                <td class="fw-bold">{{ $booking->renter->name }}</td>
            </tr>
            <tr>
                <td>Email:</td>
                <td>{{ $booking->renter->email }}</td>
            </tr>
            @if($booking->renter->phone)
            <tr>
                <td>Telepon:</td>
                <td>{{ $booking->renter->phone }}</td>
            </tr>
            @endif
            <tr>
                <td>Member sejak:</td>
                <td>{{ $booking->renter->created_at->format('M Y') }}</td>
            </tr>
        </table>

        <h6 class="fw-bold mb-3 mt-4">Informasi Motor</h6>
        <table class="table table-borderless">
            <tr>
                <td width="40%">Motor:</td>
                <td class="fw-bold">{{ $booking->motor->brand }} {{ $booking->motor->model }}</td>
            </tr>
            <tr>
                <td>Plat Nomor:</td>
                <td class="fw-bold">{{ $booking->motor->plate_number }}</td>
            </tr>
            <tr>
                <td>CC:</td>
                <td>{{ $booking->motor->cc }}cc</td>
            </tr>
            <tr>
                <td>Tahun:</td>
                <td>{{ $booking->motor->year }}</td>
            </tr>
        </table>
    </div>
</div>

@if($booking->payment)
<div class="row mt-4">
    <div class="col-12">
        <h6 class="fw-bold mb-3">Informasi Pembayaran</h6>
        <div class="card bg-light">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <small class="text-muted">Metode Pembayaran</small>
                        <div class="fw-bold">{{ ucfirst($booking->payment->method) }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Jumlah</small>
                        <div class="fw-bold text-success">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Status Pembayaran</small>
                        <div>
                            @if($booking->payment->status === 'pending')
                                <span class="badge bg-warning">Pending</span>
                            @elseif($booking->payment->status === 'paid')
                                <span class="badge bg-success">Lunas</span>
                            @elseif($booking->payment->status === 'failed')
                                <span class="badge bg-danger">Gagal</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-3">
                        <small class="text-muted">Tanggal Pembayaran</small>
                        <div>{{ $booking->payment->created_at->format('d M Y H:i') }}</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@if($booking->status === 'cancelled' && $booking->notes)
<div class="row mt-4">
    <div class="col-12">
        <h6 class="fw-bold mb-3">Alasan Pembatalan</h6>
        <div class="alert alert-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>
            {{ $booking->notes }}
        </div>
    </div>
</div>
@endif