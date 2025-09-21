@extends('layouts.fann')

@section('title', 'Verifikasi Motor')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Verifikasi Motor</h1>
    <p>Kelola dan verifikasi motor yang didaftarkan pemilik</p>
</div>

<!-- Filter & Search -->
<div class="row mb-4">
    <div class="col-md-8">
        <form method="GET" action="{{ route('admin.motors') }}" class="row g-3">
            <div class="col-md-3">
                <select class="form-select" name="status">
                    <option value="">Semua Status</option>
                    <option value="pending_verification" {{ request('status') == 'pending_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="available" {{ request('status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                    <option value="rented" {{ request('status') == 'rented' ? 'selected' : '' }}>Disewa</option>
                    <option value="maintenance" {{ request('status') == 'maintenance' ? 'selected' : '' }}>Maintenance</option>
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" name="cc">
                    <option value="">Semua CC</option>
                    <option value="100" {{ request('cc') == '100' ? 'selected' : '' }}>100cc</option>
                    <option value="125" {{ request('cc') == '125' ? 'selected' : '' }}>125cc</option>
                    <option value="150" {{ request('cc') == '150' ? 'selected' : '' }}>150cc</option>
                    <option value="250" {{ request('cc') == '250' ? 'selected' : '' }}>250cc</option>
                    <option value="500" {{ request('cc') == '500' ? 'selected' : '' }}>500cc</option>
                </select>
            </div>
            <div class="col-md-4">
                <div class="input-group">
                    <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Cari brand atau plat nomor...">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-2">
                <a href="{{ route('admin.motors') }}" class="btn btn-outline-secondary w-100">Reset</a>
            </div>
        </form>
    </div>
    <div class="col-md-4">
        <div class="row g-2">
            <div class="col-6">
                <div class="card bg-warning text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $pendingCount ?? 0 }}</h6>
                        <small>Perlu Verifikasi</small>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <div class="card bg-success text-white">
                    <div class="card-body py-2 text-center">
                        <h6 class="mb-0">{{ $verifiedCount ?? 0 }}</h6>
                        <small>Terverifikasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Motors Grid -->
<div class="row">
    @if($motors->count() > 0)
        @foreach($motors as $motor)
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100 border-0 shadow-sm">
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
                    
                    <!-- Status Badge -->
                    <div class="position-absolute top-0 end-0 m-3">
                        @if($motor->status === 'pending_verification')
                            <span class="badge bg-warning">Menunggu Verifikasi</span>
                        @elseif($motor->status === 'available')
                            <span class="badge bg-success">Tersedia</span>
                        @elseif($motor->status === 'rented')
                            <span class="badge bg-info">Disewa</span>
                        @elseif($motor->status === 'maintenance')
                            <span class="badge bg-secondary">Maintenance</span>
                        @endif
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
                    
                    <!-- Owner Info -->
                    <div class="mb-3">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-person-circle me-2"></i>
                            <div>
                                <div class="fw-bold">{{ $motor->owner->name }}</div>
                                <small class="text-muted">{{ $motor->owner->email }}</small>
                            </div>
                        </div>
                    </div>

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

                <!-- Card Footer -->
                <div class="card-footer bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            <i class="bi bi-calendar me-1"></i>
                            {{ $motor->created_at->format('d M Y') }}
                        </small>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-outline-primary" onclick="showMotorDetail({{ $motor->id }})">
                                <i class="bi bi-eye me-1"></i>Detail
                            </button>
                            @if($motor->status === 'pending_verification')
                                <button type="button" class="btn btn-sm btn-success" onclick="verifyMotor({{ $motor->id }})">
                                    <i class="bi bi-check-circle me-1"></i>Verifikasi
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    @else
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-motorcycle text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">Tidak ada motor ditemukan</h4>
                <p class="text-muted">Coba ubah filter pencarian Anda</p>
            </div>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($motors->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $motors->links() }}
    </div>
@endif

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
                <button type="button" class="btn btn-success" id="verifyMotorFromModal" style="display: none;">
                    <i class="bi bi-check-circle me-2"></i>Verifikasi Motor
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Verify Motor Modal -->
<div class="modal fade" id="verifyModal" tabindex="-1" aria-labelledby="verifyModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="verifyModalLabel">Verifikasi Motor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin memverifikasi motor ini?</p>
                <p class="text-muted">Motor yang sudah diverifikasi akan muncul dalam daftar rental untuk penyewa.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="verifyForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Verifikasi Motor</button>
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
    fetch(`{{ url('admin/motors') }}/${motorId}/ajax`)
        .then(response => response.json())
        .then(data => {
            const motor = data.motor;
            const stats = data.stats;
            
            // Update verify button
            const verifyBtn = document.getElementById('verifyMotorFromModal');
            if (motor.status === 'pending_verification') {
                verifyBtn.style.display = 'inline-block';
                verifyBtn.onclick = function() {
                    modal.hide();
                    verifyMotor(motorId);
                };
            } else {
                verifyBtn.style.display = 'none';
            }
            
            // Build motor detail HTML
            const photoUrl = motor.photo ? `{{ asset('storage') }}/${motor.photo}` : null;
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
                                \`<img src="\${photoUrl}" class="img-fluid rounded" alt="\${motor.brand}" style="width: 100%; height: 400px; object-fit: cover;">\` :
                                \`<div class="bg-light rounded d-flex align-items-center justify-content-center" style="height: 400px;">
                                    <i class="bi bi-motorcycle text-muted" style="font-size: 5rem;"></i>
                                </div>\`
                            }
                        </div>
                    </div>
                    
                    <!-- Motor Info -->
                    <div class="col-md-6">
                        <div class="motor-info">
                            <div class="d-flex justify-content-between align-items-start mb-3">
                                <h3 class="mb-0">\${motor.brand}</h3>
                                <span class="badge bg-\${statusClass[motor.status]} fs-6">\${statusText[motor.status]}</span>
                            </div>
                            
                            <div class="row mb-4">
                                <div class="col-6">
                                    <div class="info-item">
                                        <i class="bi bi-gear text-primary me-2"></i>
                                        <strong>Kapasitas Mesin:</strong>
                                        <div class="ms-4">\${motor.type_cc}</div>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="info-item">
                                        <i class="bi bi-credit-card text-primary me-2"></i>
                                        <strong>Nomor Plat:</strong>
                                        <div class="ms-4">\${motor.plate_number}</div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Owner Info -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h6 class="card-title">
                                        <i class="bi bi-person-circle me-2"></i>Informasi Pemilik
                                    </h6>
                                    <div class="row">
                                        <div class="col-12 mb-2">
                                            <strong>Nama:</strong> \${motor.owner.name}
                                        </div>
                                        <div class="col-12 mb-2">
                                            <strong>Email:</strong> \${motor.owner.email}
                                        </div>
                                        <div class="col-12">
                                            <strong>Telepon:</strong> \${motor.owner.phone || 'Tidak tersedia'}
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            \${motor.description ? \`
                                <div class="info-group mb-4">
                                    <h6><i class="bi bi-chat-text text-primary me-2"></i>Deskripsi Motor</h6>
                                    <div class="border rounded p-3 bg-light">\${motor.description}</div>
                                </div>
                            \` : ''}
                        </div>
                    </div>
                </div>
                
                <!-- Rental Rates -->
                \${motor.rental_rate ? \`
                    <div class="row mt-4">
                        <div class="col-12">
                            <h6 class="mb-3"><i class="bi bi-currency-dollar text-primary me-2"></i>Tarif Sewa</h6>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="text-center p-4 border rounded bg-light">
                                        <i class="bi bi-calendar-day text-primary fs-3"></i>
                                        <div class="mt-2">
                                            <div class="fw-bold fs-5 text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.daily_rate)}</div>
                                            <small class="text-muted">Per Hari</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-4 border rounded bg-light">
                                        <i class="bi bi-calendar-week text-primary fs-3"></i>
                                        <div class="mt-2">
                                            <div class="fw-bold fs-5 text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.weekly_rate)}</div>
                                            <small class="text-muted">Per Minggu</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="text-center p-4 border rounded bg-light">
                                        <i class="bi bi-calendar-month text-primary fs-3"></i>
                                        <div class="mt-2">
                                            <div class="fw-bold fs-5 text-primary">Rp \${new Intl.NumberFormat('id-ID').format(motor.rental_rate.monthly_rate)}</div>
                                            <small class="text-muted">Per Bulan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                \` : ''}
                
                <!-- Statistics -->
                <div class="row mt-4">
                    <div class="col-12">
                        <h6 class="mb-3"><i class="bi bi-graph-up text-primary me-2"></i>Statistik Motor</h6>
                        <div class="row">
                            <div class="col-3">
                                <div class="text-center p-3 bg-primary bg-opacity-10 rounded">
                                    <i class="bi bi-calendar-check text-primary fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold fs-5">\${stats.total_bookings}</div>
                                        <small class="text-muted">Total Pesanan</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-warning bg-opacity-10 rounded">
                                    <i class="bi bi-clock text-warning fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold fs-5">\${stats.active_bookings}</div>
                                        <small class="text-muted">Pesanan Aktif</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-success bg-opacity-10 rounded">
                                    <i class="bi bi-check-circle text-success fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold fs-5">\${stats.completed_bookings}</div>
                                        <small class="text-muted">Selesai</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-3">
                                <div class="text-center p-3 bg-info bg-opacity-10 rounded">
                                    <i class="bi bi-currency-dollar text-info fs-4"></i>
                                    <div class="mt-2">
                                        <div class="fw-bold fs-5">Rp \${new Intl.NumberFormat('id-ID').format(stats.total_earnings)}</div>
                                        <small class="text-muted">Total Pendapatan</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Registration Info -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Informasi Pendaftaran:</strong>
                            Motor ini didaftarkan pada \${new Date(motor.created_at).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric',
                                hour: '2-digit',
                                minute: '2-digit'
                            })}
                            \${motor.verified_at ? \` dan telah diverifikasi pada \${new Date(motor.verified_at).toLocaleDateString('id-ID', {
                                year: 'numeric',
                                month: 'long',
                                day: 'numeric'
                            })}\` : ''}
                        </div>
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

function verifyMotor(motorId) {
    const form = document.getElementById('verifyForm');
    form.action = `/admin/motors/${motorId}/verify`;
    
    const modal = new bootstrap.Modal(document.getElementById('verifyModal'));
    modal.show();
}
</script>
@endsection