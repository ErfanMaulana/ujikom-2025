@extends('layouts.fann')

@section('title', 'Detail Pemesanan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-file-text me-3"></i>Detail Pemesanan
    </h1>
    <p>Informasi lengkap pemesanan motor Anda</p>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="bi bi-calendar-check me-2"></i>Detail Pemesanan #{{ $booking->id }}
                </h5>
                <div>
                    @switch($booking->status)
                        @case('pending')
                            <span class="badge bg-warning fs-6">Menunggu Konfirmasi</span>
                            @break
                        @case('confirmed')
                            <span class="badge bg-info fs-6">Dikonfirmasi</span>
                                @break
                            @case('active')
                                <span class="badge bg-success fs-6">Aktif</span>
                                @break
                            @case('completed')
                                <span class="badge bg-primary fs-6">Selesai</span>
                                @break
                            @case('cancelled')
                                <span class="badge bg-danger fs-6">Dibatalkan</span>
                                @break
                            @default
                                <span class="badge bg-secondary fs-6">{{ ucfirst($booking->status) }}</span>
                        @endswitch
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            @if($booking->motor->photo)
                                <img src="{{ Storage::url($booking->motor->photo) }}" 
                                     alt="{{ $booking->motor->brand }} {{ $booking->motor->model }}"
                                     class="img-fluid rounded">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="height: 250px;">
                                    <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-8">
                            <h4>{{ $booking->motor->brand }} {{ $booking->motor->model }}</h4>
                            <p class="text-muted mb-3">{{ $booking->motor->type_cc }} • {{ $booking->motor->year }}</p>
                            
                            @if($booking->motor->description)
                                <p class="mb-4">{{ $booking->motor->description }}</p>
                            @endif
                            
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <h6><i class="bi bi-calendar-event me-2"></i>Tanggal Mulai</h6>
                                    <p class="mb-0">{{ $booking->start_date->format('d F Y') }}</p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6><i class="bi bi-calendar-check me-2"></i>Tanggal Selesai</h6>
                                    <p class="mb-0">{{ $booking->end_date->format('d F Y') }}</p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6><i class="bi bi-clock me-2"></i>Durasi</h6>
                                    @php
                                        $days = $booking->start_date->diffInDays($booking->end_date) + 1;
                                    @endphp
                                    <p class="mb-0">{{ $days }} hari</p>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <h6><i class="bi bi-currency-dollar me-2"></i>Total Harga</h6>
                                    <p class="mb-0"><strong class="text-primary fs-5">Rp {{ number_format($booking->price, 0, ',', '.') }}</strong></p>
                                </div>
                            </div>
                            
                            @if($booking->notes)
                                <div class="mt-4">
                                    <h6><i class="bi bi-chat-text me-2"></i>Catatan</h6>
                                    <p class="text-muted">{{ $booking->notes }}</p>
                                </div>
                            @endif
                            
                            <div class="mt-4">
                                <h6><i class="bi bi-person me-2"></i>Pemilik Motor</h6>
                                <p class="mb-0">{{ $booking->motor->owner->name }}</p>
                                <small class="text-muted">{{ $booking->motor->owner->email }}</small>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Informasi Pemesanan</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td>Tanggal Pesan:</td>
                                    <td>{{ $booking->created_at->format('d F Y H:i') }}</td>
                                </tr>
                                @if($booking->confirmed_at)
                                <tr>
                                    <td>Dikonfirmasi:</td>
                                    <td>{{ $booking->confirmed_at->format('d F Y H:i') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td>Status:</td>
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
                                        @endswitch
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($booking->rentalRate)
                                <h6>Detail Tarif</h6>
                                <table class="table table-sm">
                                    <tr>
                                        <td>Tarif per hari:</td>
                                        <td>Rp {{ number_format($booking->motor->rentalRate->daily_rate, 0, ',', '.') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Durasi:</td>
                                        <td>{{ $days }} hari</td>
                                    </tr>
                                    <tr class="table-primary">
                                        <td><strong>Total:</strong></td>
                                        <td><strong>Rp {{ number_format($booking->price, 0, ',', '.') }}</strong></td>
                                    </tr>
                                </table>
                            @endif
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="{{ route('penyewa.bookings') }}" class="btn btn-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali ke Daftar
                        </a>
                        
                        <div>
                            @if($booking->status === 'pending')
                                <a href="{{ route('penyewa.payment.form', $booking->id) }}" class="btn btn-success me-2">
                                    <i class="bi bi-credit-card me-1"></i>Bayar Sekarang
                                </a>
                                
                                <form action="{{ route('penyewa.booking.cancel', $booking->id) }}" 
                                      method="POST" 
                                      class="d-inline"
                                      onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger">
                                        <i class="bi bi-x-circle me-1"></i>Batalkan
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
@endsection