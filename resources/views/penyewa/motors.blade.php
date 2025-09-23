@extends('layouts.fann')

@section('title', 'Daftar Motor - Penyewa')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-motorcycle me-3"></i>Daftar Motor
    </h1>
    <p>Pilih motor yang ingin Anda sewa</p>
</div>

<!-- Verification Status Alert -->
@if(!$isVerified)
<div class="alert alert-warning mb-4" role="alert">
    <div class="d-flex align-items-center">
        <i class="bi bi-exclamation-triangle-fill me-3" style="font-size: 1.2rem;"></i>
        <div>
            <strong>Akun Belum Diverifikasi:</strong> 
            Anda tidak dapat menyewa motor hingga akun diverifikasi oleh admin.
        </div>
    </div>
</div>
@endif

<div class="row">
    <div class="col-lg-12">
                    <h1 class="h2 mb-0">
                        <i class="bi bi-motorcycle me-3"></i>Daftar Motor Tersedia
                    </h1>
                    <p class="text-muted">Jelajahi motor yang tersedia untuk disewa</p>
                </div>
            </div>

            <!-- Filter dan Search -->
            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="input-group">
                        <span class="input-group-text">
                            <i class="bi bi-search"></i>
                        </span>
                        <input type="text" class="form-control" id="searchMotor" placeholder="Cari brand, model, tahun, atau plat nomor...">
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterBrand">
                        <option value="">Semua Merek</option>
                        @foreach($motors->pluck('brand')->unique() as $brand)
                            <option value="{{ $brand }}">{{ $brand }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" id="filterType">
                        <option value="">Semua Tipe CC</option>
                        <option value="110cc">110cc</option>
                        <option value="125cc">125cc</option>
                        <option value="150cc">150cc</option>
                        <option value="160cc">160cc</option>
                        <option value="250cc">250cc</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select class="form-select" id="sortBy">
                        <option value="newest">Terbaru</option>
                        <option value="price_low">Harga Terendah</option>
                        <option value="price_high">Harga Tertinggi</option>
                        <option value="brand">Merek A-Z</option>
                    </select>
                </div>
            </div>

            <!-- Motor Cards -->
            <div class="row" id="motorContainer">
                @forelse($motors as $motor)
                    <div class="col-xl-3 col-lg-4 col-md-6 mb-4 motor-card" 
                         data-brand="{{ $motor->brand }}" 
                         data-type="{{ $motor->type_cc }}" 
                         data-name="{{ strtolower($motor->brand . ' ' . $motor->model . ' ' . $motor->year . ' ' . $motor->plate_number) }}">
                        <div class="card h-100 shadow-sm hover-shadow">
                            <div class="position-relative">
                                @if($motor->photo)
                                    <img src="{{ Storage::url($motor->photo) }}" 
                                         class="card-img-top" 
                                         alt="{{ $motor->brand }} {{ $motor->model }}"
                                         style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" 
                                         style="height: 200px;">
                                        <i class="bi bi-motorcycle text-muted" style="font-size: 3rem;"></i>
                                    </div>
                                @endif
                                
                                <!-- Status Badge -->
                                <span class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-success">
                                        <i class="bi bi-check-circle me-1"></i>Tersedia
                                    </span>
                                </span>

                                <!-- Type CC Badge -->
                                <span class="position-absolute top-0 end-0 m-2">
                                    <span class="badge bg-primary">{{ $motor->type_cc }}</span>
                                </span>
                            </div>

                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-2">{{ $motor->brand }} {{ $motor->model }}</h5>
                                <p class="card-text text-muted small mb-2">
                                    <i class="bi bi-calendar me-1"></i>{{ $motor->year }}
                                    <i class="bi bi-palette ms-2 me-1"></i>{{ $motor->color }}
                                </p>
                                <p class="card-text text-muted small mb-2">
                                    <i class="bi bi-credit-card me-1"></i>{{ $motor->plate_number }}
                                </p>
                                
                                @if($motor->description)
                                    <p class="card-text small text-muted mb-3">
                                        {{ Str::limit($motor->description, 80) }}
                                    </p>
                                @endif

                                <!-- Pricing -->
                                <div class="mb-3">
                                    @if($motor->rentalRate)
                                        <div class="h6 text-primary mb-1">
                                            <strong>Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</strong>/hari
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="bi bi-exclamation-triangle me-1"></i>Harga belum ditentukan
                                        </div>
                                    @endif
                                </div>

                                <!-- Pemilik Info -->
                                <div class="mb-3 small">
                                    <i class="bi bi-person me-1"></i>
                                    <span class="text-muted">Pemilik:</span> {{ $motor->owner->name }}
                                </div>

                                <!-- Rating Display -->
                                <div class="mb-3 small">
                                    @php
                                        $avgRating = $motor->getAverageRating();
                                        $totalRatings = $motor->getTotalRatings();
                                    @endphp
                                    
                                    @if($totalRatings > 0)
                                        <div class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="bi bi-star{{ $i <= $avgRating ? '-fill' : '' }} text-warning me-1" style="font-size: 0.85rem;"></i>
                                            @endfor
                                            <span class="text-muted ms-1">({{ number_format($avgRating, 1) }}/5 - {{ $totalRatings }} ulasan)</span>
                                        </div>
                                    @else
                                        <div class="text-muted">
                                            <i class="bi bi-star me-1"></i>Belum ada rating
                                        </div>
                                    @endif
                                </div>

                                <!-- Actions -->
                                <div class="mt-auto">
                                    <div class="d-grid gap-2">
                                        <button type="button" class="btn btn-outline-primary btn-sm" 
                                                onclick="showMotorDetail({{ $motor->id }})">
                                            <i class="bi bi-eye me-1"></i>Lihat Detail
                                        </button>
                                        @if($motor->rentalRate)
                                            @if($isVerified)
                                                <a href="{{ route('penyewa.booking.form', $motor->id) }}" 
                                                   class="btn btn-primary btn-sm">
                                                    <i class="bi bi-calendar-plus me-1"></i>Sewa Sekarang
                                                </a>
                                            @else
                                                <button class="btn btn-secondary btn-sm" disabled title="Akun belum diverifikasi">
                                                    <i class="bi bi-lock me-1"></i>Perlu Verifikasi
                                                </button>
                                            @endif
                                        @else
                                            <button class="btn btn-secondary btn-sm" disabled>
                                                <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <div class="text-center py-5">
                            <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                            <h4 class="mt-3 text-muted">Tidak ada motor yang tersedia</h4>
                            <p class="text-muted">Silakan cek kembali nanti atau ubah filter pencarian.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Load More Button (if needed for pagination) -->
            @if($motors->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $motors->links() }}
                </div>
            @endif
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

<style>
.hover-shadow {
    transition: box-shadow 0.15s ease-in-out;
}

.hover-shadow:hover {
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.card-img-top {
    border-radius: 0.375rem 0.375rem 0 0;
}

.badge {
    font-size: 0.75em;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchMotor');
    const brandFilter = document.getElementById('filterBrand');
    const typeFilter = document.getElementById('filterType');
    const sortBy = document.getElementById('sortBy');

    // Search and Filter functionality
    function filterMotors() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedBrand = brandFilter.value;
        const selectedType = typeFilter.value;
        const sortValue = sortBy.value;
        
        let motorCards = Array.from(document.querySelectorAll('.motor-card'));
        
        // Filter
        motorCards.forEach(card => {
            const name = card.dataset.name;
            const brand = card.dataset.brand;
            const type = card.dataset.type;
            
            const matchesSearch = name.includes(searchTerm);
            const matchesBrand = !selectedBrand || brand === selectedBrand;
            const matchesType = !selectedType || type === selectedType;
            
            if (matchesSearch && matchesBrand && matchesType) {
                card.style.display = 'block';
            } else {
                card.style.display = 'none';
            }
        });
        
        // Sort visible cards
        const visibleCards = motorCards.filter(card => card.style.display !== 'none');
        const container = document.getElementById('motorContainer');
        
        if (sortValue === 'brand') {
            visibleCards.sort((a, b) => a.dataset.brand.localeCompare(b.dataset.brand));
        } else if (sortValue === 'price_low' || sortValue === 'price_high') {
            // Price sorting would need additional data attributes
            // For now, keep original order
        }
        
        // Reorder in DOM
        visibleCards.forEach(card => {
            container.appendChild(card);
        });
    }

    // Event listeners
    searchInput.addEventListener('input', filterMotors);
    brandFilter.addEventListener('change', filterMotors);
    typeFilter.addEventListener('change', filterMotors);
    sortBy.addEventListener('change', filterMotors);
});

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
                                <strong>Plat Nomor:</strong><br>
                                <span class="text-muted">${motor.plate_number}</span>
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
                        <h5>Harga Sewa</h5>
                        <div class="row">
                            ${rentalRateHtml}
                        </div>
                    </div>
                ` : `
                    <div class="mt-4">
                        <p class="text-muted text-center">
                            <i class="bi bi-exclamation-circle me-1"></i>
                            Harga sewa belum tersedia. Silakan hubungi pemilik motor.
                        </p>
                    </div>
                `}
                
                <!-- Rating Section -->
                <div class="mt-4">
                    <h5>Rating & Ulasan</h5>
                    <div id="ratingsSection">
                        <div class="text-center text-muted py-3">
                            <div class="spinner-border spinner-border-sm me-2" role="status"></div>
                            Loading ratings...
                        </div>
                    </div>
                </div>
            `;
            
            // Load ratings for this motor
            loadMotorRatings(motor.id);
            
            const bookingContainer = document.querySelector('.modal-body .d-flex.justify-content-end');
            if (motor.rental_rate) {
                @if(!$isVerified)
                bookingContainer.innerHTML = `
                    <button class="btn btn-secondary" disabled>
                        <i class="bi bi-shield-exclamation me-1"></i>Perlu Verifikasi
                    </button>
                `;
                @else
                bookingContainer.innerHTML = `
                    <a href="/penyewa/booking/${motor.id}" class="btn btn-primary">
                        <i class="bi bi-calendar-plus me-1"></i>Sewa Sekarang
                    </a>
                `;
                @endif
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

// Function to load motor ratings
function loadMotorRatings(motorId) {
    fetch(`/penyewa/ratings/${motorId}`)
        .then(response => response.json())
        .then(data => {
            const ratingsSection = document.getElementById('ratingsSection');
            
            if (data.ratings.data.length === 0) {
                ratingsSection.innerHTML = `
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-star me-1"></i>
                        Belum ada rating untuk motor ini
                    </div>
                `;
                return;
            }
            
            // Display average rating
            let averageDisplay = '';
            if (data.average_rating > 0) {
                averageDisplay = `
                    <div class="d-flex align-items-center justify-content-between mb-3 p-3 bg-light rounded">
                        <div>
                            <h6 class="mb-1">Rating Rata-rata</h6>
                            <div class="d-flex align-items-center">
                                ${generateStarRating(data.average_rating)}
                                <span class="ms-2 fw-bold">${data.average_rating.toFixed(1)}</span>
                                <span class="ms-1 text-muted">(${data.total_ratings} ulasan)</span>
                            </div>
                        </div>
                    </div>
                `;
            }
            
            // Display individual ratings
            let ratingsHtml = data.ratings.data.map(rating => `
                <div class="border-bottom pb-3 mb-3">
                    <div class="d-flex justify-content-between align-items-start">
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center mb-2">
                                <strong class="me-2">${rating.user.name}</strong>
                                ${generateStarRating(rating.rating)}
                                <small class="ms-2 text-muted">${new Date(rating.created_at).toLocaleDateString('id-ID')}</small>
                            </div>
                            ${rating.review ? `<p class="mb-0 text-muted">${rating.review}</p>` : ''}
                        </div>
                    </div>
                </div>
            `).join('');
            
            ratingsSection.innerHTML = averageDisplay + ratingsHtml;
        })
        .catch(error => {
            console.error('Error loading ratings:', error);
            document.getElementById('ratingsSection').innerHTML = `
                <div class="text-center text-danger py-3">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    Gagal memuat rating
                </div>
            `;
        });
}

// Function to generate star rating display
function generateStarRating(rating) {
    let starsHtml = '';
    for (let i = 1; i <= 5; i++) {
        if (i <= rating) {
            starsHtml += '<i class="bi bi-star-fill text-warning"></i>';
        } else if (i - 0.5 <= rating) {
            starsHtml += '<i class="bi bi-star-half text-warning"></i>';
        } else {
            starsHtml += '<i class="bi bi-star text-muted"></i>';
        }
    }
    return starsHtml;
}
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