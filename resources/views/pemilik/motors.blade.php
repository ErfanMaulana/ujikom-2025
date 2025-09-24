@extends('layouts.fann')

@section('title', 'Motor Saya')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Motor Saya</h1>
    <p>Kelola semua motor yang telah Anda daftarkan</p>
</div>

<!-- Verification Status Alert -->
@if(!$isVerified)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-shield-exclamation me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Perlu Verifikasi Akun</h6>
                <p class="mb-0">Anda perlu memverifikasi akun terlebih dahulu sebelum dapat mendaftarkan motor baru. Silakan tunggu admin memverifikasi akun Anda.</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Error Messages -->
@if($errors->has('verification'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <div class="d-flex align-items-center">
            <i class="bi bi-exclamation-triangle me-3" style="font-size: 1.5rem;"></i>
            <div>
                <h6 class="alert-heading mb-1">Akses Ditolak</h6>
                <p class="mb-0">{{ $errors->first('verification') }}</p>
            </div>
        </div>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<!-- Action Bar -->
<div class="row mb-4">
    <div class="col-md-6">
        @if($isVerified)
            <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor Baru
            </a>
        @else
            <button class="btn btn-secondary" disabled>
                <i class="bi bi-shield-exclamation me-2"></i>Perlu Verifikasi
            </button>
        @endif
    </div>
    <div class="col-md-6">
        <form method="GET" action="{{ route('pemilik.motors') }}">
            <div class="input-group">
                <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari motor...">
                <select class="form-select" name="status" style="max-width: 200px;">
                    <option value="">Semua Status</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
                <button class="btn btn-outline-primary" type="submit">
                    <i class="bi bi-search"></i>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Motors List -->
@if($motors->count() > 0)
    <div class="row">
        @foreach($motors as $motor)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
                <!-- Motor Image -->
                <div class="position-relative">
                    @if($motor->photo)
                        <img src="{{ Storage::url($motor->photo) }}" 
                             class="card-img-top" 
                             alt="{{ $motor->brand }}" 
                             style="height: 250px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                             style="height: 250px;">
                            <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                        </div>
                    @endif
                    
                    <!-- Status Badge - Realtime -->
                    <div class="position-absolute top-0 start-0 m-3">
                        @php
                            $currentStatus = $motor->getCurrentStatus();
                            $currentBooking = $motor->getCurrentBooking();
                        @endphp
                        
                        @if($currentStatus === 'pending_verification')
                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                        @elseif($currentStatus === 'rented')
                            <span class="badge bg-danger text-white" title="Sedang disewa">
                                <i class="bi bi-person-check me-1"></i>Sedang Disewa
                            </span>
                            @if($currentBooking)
                                <br><small class="badge bg-dark mt-1">{{ $currentBooking->renter->name }}</small>
                            @endif
                        @elseif($currentStatus === 'available')
                            <span class="badge bg-success" title="Tersedia untuk disewa">
                                <i class="bi bi-check-circle me-1"></i>Tersedia
                            </span>
                        @elseif($currentStatus === 'maintenance')
                            <span class="badge bg-secondary" title="Dalam maintenance">
                                <i class="bi bi-tools me-1"></i>Maintenance
                            </span>
                        @endif
                    </div>

                    <!-- Action Menu -->
                    <div class="position-absolute top-0 end-0 m-3">
                        <div class="dropdown">
                            <button class="btn btn-light btn-sm rounded-circle" type="button" 
                                    id="motorAction{{ $motor->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="motorAction{{ $motor->id }}">
                                <li>
                                    <button class="dropdown-item" onclick="showMotorDetail({{ $motor->id }})">
                                        <i class="bi bi-eye me-2"></i>Lihat Detail
                                    </button>
                                </li>
                                @if($isVerified)
                                    <li>
                                        <a class="dropdown-item" href="{{ route('pemilik.motor.edit', $motor->id) }}">
                                            <i class="bi bi-pencil me-2"></i>Edit Motor
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-danger" onclick="deleteMotor({{ $motor->id }}, '{{ $motor->brand }} {{ $motor->plate_number }}')">
                                            <i class="bi bi-trash me-2"></i>Hapus Motor
                                        </button>
                                    </li>
                                @else
                                    <li>
                                        <button class="dropdown-item disabled" disabled>
                                            <i class="bi bi-shield-exclamation me-2"></i>Edit (Perlu Verifikasi)
                                        </button>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <button class="dropdown-item text-muted disabled" disabled>
                                            <i class="bi bi-shield-exclamation me-2"></i>Hapus (Perlu Verifikasi)
                                        </button>
                                    </li>
                                @endif
                            </ul>
                        </div>
                    </div>
                </div>

                <!-- Motor Info -->
                <div class="card-body">
                    <h5 class="card-title">{{ $motor->brand }}</h5>
                    <p class="text-muted mb-2">
                        <i class="bi bi-gear me-1"></i>{{ $motor->type_cc }}
                        <span class="ms-3">
                            <i class="bi bi-credit-card me-1"></i>{{ $motor->plate_number }}
                        </span>
                    </p>
                    
                    @if($motor->description)
                        <p class="card-text text-muted small">
                            {{ Str::limit($motor->description, 80) }}
                        </p>
                    @endif

                    <!-- Rental Rates -->
                    @if($motor->rentalRate)
                        <div class="mb-3">
                            <div class="row text-center">
                                <div class="col-4">
                                    <small class="text-muted">Harian</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->daily_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Mingguan</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->weekly_rate, 0, ',', '.') }}</div>
                                </div>
                                <div class="col-4">
                                    <small class="text-muted">Bulanan</small>
                                    <div class="fw-bold text-primary">Rp {{ number_format($motor->rentalRate->monthly_rate, 0, ',', '.') }}</div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <!-- Footer -->
                <div class="card-footer bg-light text-muted small d-flex justify-content-between align-items-center">
                    <span>
                        <i class="bi bi-calendar me-1"></i>
                        Didaftarkan {{ $motor->created_at->diffForHumans() }}
                    </span>
                    @if($motor->status === 'available')
                        <span class="text-success">
                            <i class="bi bi-check-circle me-1"></i>Terverifikasi
                        </span>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $motors->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="empty-state">
        <i class="bi bi-motorcycle"></i>
        <h6>Belum ada motor yang didaftarkan</h6>
        <p>Mulai daftarkan motor Anda untuk disewakan dan dapatkan penghasilan tambahan</p>
        <a href="{{ route('pemilik.motor.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Daftarkan Motor Pertama
        </a>
    </div>
@endif

<!-- Motor Detail Modal -->
<div class="modal fade" id="motorDetailModal" tabindex="-1" aria-labelledby="motorDetailModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="motorDetailModalLabel">
                    <i class="bi bi-motorcycle me-2"></i>Detail Motor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="motorDetailContent">
                    <!-- Loading state -->
                    <div class="text-center py-4">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Memuat detail motor...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                @if($isVerified)
                    <button type="button" class="btn btn-primary" id="editMotorBtn">
                        <i class="bi bi-pencil me-2"></i>Edit Motor
                    </button>
                @else
                    <button type="button" class="btn btn-secondary" disabled>
                        <i class="bi bi-shield-exclamation me-2"></i>Edit (Perlu Verifikasi)
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus motor <strong id="motorName"></strong>?</p>
                <p class="text-muted">Tindakan ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Hapus Motor</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Show motor detail in modal
function showMotorDetail(motorId) {
    const modal = new bootstrap.Modal(document.getElementById('motorDetailModal'));
    const content = document.getElementById('motorDetailContent');
    
    // Show loading state
    content.innerHTML = `
        <div class="text-center py-4">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
            <p class="mt-2">Memuat detail motor...</p>
        </div>
    `;
    
    // Show modal
    modal.show();
    
    // Fetch motor detail
    fetch(`{{ url('pemilik/motors') }}/${motorId}/ajax`)
        .then(response => response.json())
        .then(data => {
            const motor = data.motor;
            const stats = data.stats;
            
            // Update edit button based on verification status
            @if($isVerified)
                document.getElementById('editMotorBtn').onclick = function() {
                    window.location.href = `{{ url('pemilik/motors') }}/${motorId}/edit`;
                };
            @endif
            
            // Build motor detail HTML
            const photoUrl = motor.photo ? `{{ url('storage') }}/${motor.photo}` : null;
            const statusClass = {
                'pending_verification': 'warning',
                'available': 'success',
                'rented': 'info',
                'maintenance': 'secondary'
            };
            const statusText = {
                'pending_verification': 'Menunggu Verifikasi',
                'available': 'Tersedia',
                'rented': 'Disewa',
                'maintenance': 'Maintenance'
            };
            
            content.innerHTML = \`
                <div class="row">
                    <!-- Motor Photo -->
                    <div class="col-md-6">
                        <div class="motor-photo mb-4">
                            \${photoUrl ? 
                                \`<img src="\${photoUrl}" class="img-fluid rounded" alt="\${motor.brand}" style="width: 100%; height: 300px; object-fit: cover;">\` :
                                \`<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 300px;">
                                    <i class="bi bi-motorcycle text-muted" style="font-size: 4rem;"></i>
                                </div>\`
                            }
                        </div>
                    </div>
                    
                    <!-- Motor Info -->
                    <div class="col-md-6">
                        <div class="motor-info">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h4 class="mb-0">\${motor.brand}</h4>
                                <span class="badge bg-\${statusClass[motor.status]}">\${statusText[motor.status]}</span>
                            </div>
                            
                            <div class="info-group mb-3">
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Kapasitas Mesin</small>
                                        <div class="fw-bold">\${motor.type_cc}</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Plat Nomor</small>
                                        <div class="fw-bold">\${motor.plate_number}</div>
                                    </div>
                                </div>
                            </div>
                            
                            \${motor.description ? \`
                                <div class="info-group mb-3">
                                    <small class="text-muted">Deskripsi</small>
                                    <div>\${motor.description}</div>
                                </div>
                            \` : ''}
                            
                            <!-- Rental Rates -->
                            \${motor.rental_rate ? \`
                                <div class="info-group mb-3">
                                    <small class="text-muted">Tarif Sewa</small>
                                    <div class="row mt-2">
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted">Harian</small>
                                                <div class="fw-bold text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted">Mingguan</small>
                                                <div class="fw-bold text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</div>
                                            </div>
                                        </div>
                                        <div class="col-4 text-center">
                                            <div class="border rounded p-2">
                                                <small class="text-muted">Bulanan</small>
                                                <div class="fw-bold text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            \` : ''}
                        </div>
                    </div>
                </div>
                
                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3">Statistik Motor</h6>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-calendar-check text-primary fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold">\${stats.total_bookings}</div>
                                        <small class="text-muted">Total Pesanan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-clock text-warning fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold">\${stats.active_bookings}</div>
                                        <small class="text-muted">Pesanan Aktif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold">\${stats.completed_bookings}</div>
                                        <small class="text-muted">Selesai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-light rounded">
                                    <i class="bi bi-currency-dollar text-info fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold">Rp \${new Intl.NumberFormat('id-ID').format(stats.total_earnings)}</div>
                                        <small class="text-muted">Total Pendapatan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Recent Bookings -->
                \${motor.bookings && motor.bookings.length > 0 ? \`
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3">Pesanan Terbaru</h6>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Penyewa</th>
                                            <th>Tanggal</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        \${motor.bookings.map(booking => \`
                                            <tr>
                                                <td>\${booking.user ? booking.user.name : 'N/A'}</td>
                                                <td>\${new Date(booking.start_date).toLocaleDateString('id-ID')}</td>
                                                <td>
                                                    <span class="badge bg-\${booking.status === 'confirmed' ? 'success' : booking.status === 'pending' ? 'warning' : 'secondary'} bg-opacity-10 text-\${booking.status === 'confirmed' ? 'success' : booking.status === 'pending' ? 'warning' : 'secondary'}">
                                                        \${booking.status}
                                                    </span>
                                                </td>
                                            </tr>
                                        \`).join('')}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                \` : ''}
                
                <!-- Registration Info -->
                <div class="row mt-3">
                    <div class="col-12">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            Didaftarkan pada \${new Date(motor.created_at).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}
                        </small>
                    </div>
                </div>
            \`;
        })
        .catch(error => {
            console.error('Error fetching motor detail:', error);
            content.innerHTML = \`
                <div class="text-center py-4">
                    <i class="bi bi-exclamation-triangle text-danger fs-1"></i>
                    <p class="mt-2 text-danger">Gagal memuat detail motor.</p>
                    <button class="btn btn-outline-primary btn-sm" onclick="showMotorDetail(\${motorId})">
                        <i class="bi bi-arrow-clockwise me-1"></i>Coba Lagi
                    </button>
                </div>
            \`;
        });
}

function deleteMotor(motorId, motorName) {
    console.log('Delete motor called with ID:', motorId, 'Name:', motorName);
    
    const form = document.getElementById('deleteForm');
    const deleteUrl = `{{ url('pemilik/motors') }}/${motorId}`;
    
    console.log('Setting form action to:', deleteUrl);
    form.action = deleteUrl;
    
    // Update motor name in modal
    document.getElementById('motorName').textContent = motorName;
    
    const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
    modal.show();
}
</script>
@endsection