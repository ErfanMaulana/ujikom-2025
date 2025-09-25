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
                                    <p class="mb-0"><strong class="text-primary fs-5">Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</strong></p>
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
                                        <td><strong>Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</strong></td>
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
        
        <!-- Rating Section for Completed Bookings -->
        @if($booking->status === 'completed')
            @php
                $existingRating = App\Models\Rating::where('user_id', auth()->id())
                    ->where('booking_id', $booking->id)
                    ->first();
            @endphp
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="bi bi-star me-2"></i>Rating & Ulasan Motor
                    </h5>
                </div>
                <div class="card-body">
                    @if($existingRating)
                        <!-- Display existing rating -->
                        <div class="alert alert-success">
                            <h6><i class="bi bi-check-circle me-2"></i>Rating Anda</h6>
                            <div class="d-flex align-items-center mb-2">
                                @for($i = 1; $i <= 5; $i++)
                                    <i class="bi bi-star{{ $i <= $existingRating->rating ? '-fill' : '' }} text-warning me-1"></i>
                                @endfor
                                <span class="ms-2">{{ $existingRating->rating }}/5</span>
                            </div>
                            @if($existingRating->review)
                                <p class="mb-2"><strong>Ulasan:</strong> {{ $existingRating->review }}</p>
                            @endif
                            <small class="text-muted">Diberikan pada {{ $existingRating->created_at->format('d M Y H:i') }}</small>
                            
                            @if($existingRating->canEdit())
                                <div class="mt-3">
                                    <button class="btn btn-sm btn-outline-primary" onclick="editRating({{ $existingRating->id }}, {{ $existingRating->rating }}, '{{ $existingRating->review }}')">
                                        <i class="bi bi-pencil me-1"></i>Edit Rating
                                    </button>
                                    <button class="btn btn-sm btn-outline-danger" onclick="deleteRating({{ $existingRating->id }})">
                                        <i class="bi bi-trash me-1"></i>Hapus Rating
                                    </button>
                                </div>
                            @else
                                <small class="text-muted d-block mt-2">
                                    <i class="bi bi-info-circle me-1"></i>Rating hanya dapat diedit dalam 24 jam pertama
                                </small>
                            @endif
                        </div>
                    @else
                        <!-- Rating form -->
                        <form id="ratingForm" onsubmit="submitRating(event)">
                            @csrf
                            <input type="hidden" name="motor_id" value="{{ $booking->motor_id }}">
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            
                            <div class="mb-3">
                                <label class="form-label">Rating Motor</label>
                                <div class="rating-stars" id="ratingStars">
                                    @for($i = 1; $i <= 5; $i++)
                                        <i class="bi bi-star rating-star" data-rating="{{ $i }}" onclick="setRating({{ $i }})"></i>
                                    @endfor
                                </div>
                                <input type="hidden" name="rating" id="ratingValue" required>
                                <div class="form-text">Klik bintang untuk memberikan rating</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="review" class="form-label">Ulasan (Opsional)</label>
                                <textarea class="form-control" name="review" id="review" rows="3" 
                                          placeholder="Bagikan pengalaman Anda dengan motor ini..."></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-star me-1"></i>Kirim Rating
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        @endif
    </div>
</div>

<style>
.rating-stars {
    font-size: 2rem;
    color: #ddd;
    cursor: pointer;
}

.rating-star {
    transition: color 0.2s ease;
    margin-right: 0.25rem;
}

.rating-star:hover,
.rating-star.active {
    color: #ffc107;
}

.rating-star.filled {
    color: #ffc107;
}
</style>

<script>
let selectedRating = 0;

function setRating(rating) {
    selectedRating = rating;
    document.getElementById('ratingValue').value = rating;
    
    // Update star display
    const stars = document.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.remove('bi-star');
            star.classList.add('bi-star-fill');
            star.style.color = '#ffc107';
        } else {
            star.classList.remove('bi-star-fill');
            star.classList.add('bi-star');
            star.style.color = '#ddd';
        }
    });
}

function submitRating(event) {
    event.preventDefault();
    
    if (selectedRating === 0) {
        alert('Silakan pilih rating terlebih dahulu');
        return;
    }
    
    const formData = new FormData(event.target);
    
    fetch('{{ route("penyewa.rating.store") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.message) {
            alert(data.message);
            location.reload();
        } else if (data.error) {
            alert(data.error);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Terjadi error saat mengirim rating');
    });
}

function editRating(ratingId, currentRating, currentReview) {
    // Implementation for editing rating
    const isConfirm = confirm('Apakah Anda ingin mengedit rating ini?');
    if (isConfirm) {
        // For now, just show a simple prompt
        const newRating = prompt('Rating baru (1-5):', currentRating);
        const newReview = prompt('Ulasan baru:', currentReview);
        
        if (newRating && newRating >= 1 && newRating <= 5) {
            const formData = new FormData();
            formData.append('rating', newRating);
            formData.append('review', newReview || '');
            formData.append('_method', 'PUT');
            
            fetch(`/penyewa/ratings/${ratingId}`, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.message) {
                    alert(data.message);
                    location.reload();
                } else if (data.error) {
                    alert(data.error);
                }
            });
        }
    }
}

function deleteRating(ratingId) {
    if (confirm('Apakah Anda yakin ingin menghapus rating ini?')) {
        fetch(`/penyewa/ratings/${ratingId}`, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.message) {
                alert(data.message);
                location.reload();
            } else if (data.error) {
                alert(data.error);
            }
        });
    }
}
</script>
@endsection