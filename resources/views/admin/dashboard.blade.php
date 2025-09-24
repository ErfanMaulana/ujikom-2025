@extends('layouts.fann')

@section('title', 'Dashboard Admin')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Dashboard Admin</h1>
    <p>Kelola platform rental motor dengan kontrol penuh</p>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-primary mb-2">
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalUsers }}</h3>
                <p class="text-muted mb-0">Total Pengguna</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-info mb-2">
                    <i class="bi bi-motorcycle" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalMotors }}</h3>
                <p class="text-muted mb-0">Total Motor</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-warning mb-2">
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">{{ $totalBookings }}</h3>
                <p class="text-muted mb-0">Total Pemesanan</p>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card h-100 border-0 shadow-sm">
            <div class="card-body text-center">
                <div class="text-success mb-2">
                    <i class="bi bi-currency-dollar" style="font-size: 2.5rem;"></i>
                </div>
                <h3 class="h4 fw-bold text-dark">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                <p class="text-muted mb-0">Total Pendapatan</p>
            </div>
        </div>
    </div>
</div>

<!-- Secondary Statistics -->
<div class="row mb-4">
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-primary mb-1">{{ $totalPenyewa }}</h4>
                <small class="text-muted">Penyewa</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-info mb-1">{{ $totalPemilik }}</h4>
                <small class="text-muted">Pemilik</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-warning mb-1">{{ $pendingMotorsCount }}</h4>
                <small class="text-muted">Perlu Verifikasi</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-success mb-1">{{ $availableMotors }}</h4>
                <small class="text-muted">Motor Tersedia</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-primary mb-1">{{ $pendingBookings }}</h4>
                <small class="text-muted">Booking Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 mb-3">
        <div class="card h-100 border-0 shadow-sm bg-light">
            <div class="card-body text-center py-3">
                <h4 class="fw-bold text-success mb-1">{{ $confirmedBookings }}</h4>
                <small class="text-muted">Booking Aktif</small>
            </div>
        </div>
    </div>
</div>

<!-- Motor Status Management -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-motorcycle me-2"></i>Status Motor Realtime
                    </h5>
                    <div>
                        <button type="button" class="btn btn-sm btn-outline-primary me-2" onclick="checkMotorStatus()">
                            <i class="bi bi-arrow-clockwise me-1"></i>Cek Status
                        </button>
                        <button type="button" class="btn btn-sm btn-primary" onclick="updateMotorStatus()">
                            <i class="bi bi-gear me-1"></i>Update Status
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div id="motor-status-container">
                    <div class="text-center text-muted py-3">
                        <i class="bi bi-hourglass-split me-2"></i>
                        Klik "Cek Status" untuk melihat status motor realtime
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities and Quick Actions -->
<div class="row">
    <!-- Pending Verifications -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock me-2"></i>
                        Menunggu Verifikasi
                    </h5>
                    <a href="{{ route('admin.motors') }}" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-eye me-1"></i>Lihat Semua
                    </a>
                </div>
            </div>
            <div class="card-body">
                @if($pendingMotors->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Motor</th>
                                    <th>Pemilik</th>
                                    <th>Tanggal Daftar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($pendingMotors as $motor)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($motor->photo)
                                                <img src="{{ Storage::url($motor->photo) }}" alt="{{ $motor->brand }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" style="width: 50px; height: 50px;">
                                                    <i class="bi bi-motorcycle text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-bold">{{ $motor->brand }}</div>
                                                <small class="text-muted">{{ $motor->cc }}cc - {{ $motor->plate_number }}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="fw-bold">{{ $motor->owner->name }}</div>
                                        <small class="text-muted">{{ $motor->owner->email }}</small>
                                    </td>
                                    <td>{{ $motor->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('admin.motor.detail', $motor->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye me-1"></i>Review
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-check-circle text-success" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-2">Tidak ada motor yang menunggu verifikasi</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-lightning me-2"></i>
                    Aksi Cepat
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users') }}" class="btn btn-primary">
                        <i class="bi bi-people me-2"></i>Kelola Pengguna
                    </a>
                    <a href="{{ route('admin.motors') }}" class="btn btn-outline-primary">
                        <i class="bi bi-motorcycle me-2"></i>Kelola Motor
                    </a>
                    <a href="{{ route('admin.bookings') }}" class="btn btn-outline-primary">
                        <i class="bi bi-calendar-check me-2"></i>Kelola Booking
                    </a>
                    <a href="{{ route('admin.financial-report') }}" class="btn btn-outline-success">
                        <i class="bi bi-bar-chart me-2"></i>Laporan Keuangan
                    </a>
                </div>
            </div>
        </div>

        <!-- System Info -->
        <div class="card border-0 shadow-sm mt-4">
            <div class="card-header bg-light py-3">
                <h6 class="card-title mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Informasi Sistem
                </h6>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <h6 class="text-primary">{{ $pendingMotorsCount }}</h6>
                        <small class="text-muted">Perlu Verifikasi</small>
                    </div>
                    <div class="col-6">
                        <h6 class="text-warning">{{ $pendingBookings }}</h6>
                        <small class="text-muted">Perlu Konfirmasi</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row">
    <!-- Revenue Chart -->
    <div class="col-lg-8 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-graph-up me-2"></i>
                    Grafik Pendapatan Bulanan
                </h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- User Statistics -->
    <div class="col-lg-4 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Statistik Pengguna
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-12 mb-3">
                        <h4 class="text-primary">{{ $totalPenyewa + $totalPemilik }}</h4>
                        <small class="text-muted">Total Users</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-warning">{{ $totalPemilik }}</h4>
                        <small class="text-muted">Pemilik</small>
                    </div>
                    <div class="col-6">
                        <h4 class="text-info">{{ $totalPenyewa }}</h4>
                        <small class="text-muted">Penyewa</small>
                    </div>
                </div>
                
                <!-- Commission Breakdown -->
                <hr>
                <div class="row text-center">
                    <div class="col-12 mb-2">
                        <h6 class="text-muted">Pembagian Komisi</h6>
                    </div>
                    <div class="col-6">
                        <div class="badge bg-success p-2">Admin: 30%</div>
                    </div>
                    <div class="col-6">
                        <div class="badge bg-primary p-2">Pemilik: 70%</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Activities -->
<div class="row">
    <div class="col-lg-12 mb-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Aktivitas Terbaru
                </h5>
            </div>
            <div class="card-body">
                @if($pendingBookingsList->count() > 0)
                    <div class="row">
                        @foreach($pendingBookingsList as $booking)
                        <div class="col-md-6 col-lg-4 mb-3">
                            <div class="d-flex align-items-center p-3 bg-light rounded">
                                <div class="flex-shrink-0">
                                    @if($booking->motor->photo)
                                        <img src="{{ Storage::url($booking->motor->photo) }}" 
                                             alt="{{ $booking->motor->brand }}" 
                                             class="rounded" 
                                             style="width: 40px; height: 40px; object-fit: cover;">
                                    @else
                                        <div class="bg-white rounded d-flex align-items-center justify-content-center" 
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-motorcycle text-muted"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <h6 class="mb-0">{{ $booking->motor->brand }}</h6>
                                    <small class="text-muted">{{ $booking->renter->name }} • {{ $booking->created_at->format('d M Y') }}</small>
                                </div>
                                <div class="flex-shrink-0">
                                    @if($booking->status === 'confirmed')
                                        <span class="badge bg-success">Dikonfirmasi</span>
                                    @elseif($booking->status === 'pending')
                                        <span class="badge bg-warning">Menunggu</span>
                                    @elseif($booking->status === 'completed')
                                        <span class="badge bg-info">Selesai</span>
                                    @else
                                        <span class="badge bg-secondary">{{ ucfirst($booking->status) }}</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-3">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 2rem;"></i>
                        <p class="text-muted mb-0 mt-2">Belum ada aktivitas terbaru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    const ctx = document.getElementById('revenueChart').getContext('2d');
    
    const chartData = @json($chartData);
    
    const revenueChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: chartData.labels,
            datasets: [
                {
                    label: 'Total Pendapatan',
                    data: chartData.total_revenue,
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                },
                {
                    label: 'Komisi Admin (30%)',
                    data: chartData.admin_commission,
                    borderColor: 'rgb(34, 197, 94)',
                    backgroundColor: 'rgba(34, 197, 94, 0.1)',
                    borderWidth: 2,
                    borderDash: [5, 5],
                    fill: false
                },
                {
                    label: 'Bagian Pemilik (70%)',
                    data: chartData.owner_share,
                    borderColor: 'rgb(249, 115, 22)',
                    backgroundColor: 'rgba(249, 115, 22, 0.1)',
                    borderWidth: 2,
                    borderDash: [10, 5],
                    fill: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'Rp ' + value.toLocaleString('id-ID');
                        }
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': Rp ' + context.parsed.y.toLocaleString('id-ID');
                        }
                    }
                }
            }
        }
    });
});

// Motor Status Management Functions
function checkMotorStatus() {
    const container = document.getElementById('motor-status-container');
    
    // Show loading
    container.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            Mengecek status motor...
        </div>
    `;
    
    fetch('{{ route("admin.motors.status-realtime") }}')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayMotorStatus(data.motors, data.timestamp);
            } else {
                container.innerHTML = `
                    <div class="alert alert-danger">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        Error: ${data.message}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Terjadi kesalahan saat mengecek status motor
                </div>
            `;
        });
}

function updateMotorStatus() {
    if (!confirm('Update status semua motor berdasarkan booking aktif?')) {
        return;
    }
    
    const container = document.getElementById('motor-status-container');
    
    // Show loading
    container.innerHTML = `
        <div class="text-center py-3">
            <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
            Memperbarui status motor...
        </div>
    `;
    
    fetch('{{ route("admin.motors.update-status-realtime") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Show success message then refresh status
            container.innerHTML = `
                <div class="alert alert-success">
                    <i class="bi bi-check-circle me-2"></i>
                    ${data.message}
                </div>
            `;
            
            // Auto-refresh status after 1 second
            setTimeout(() => {
                checkMotorStatus();
            }, 1000);
        } else {
            container.innerHTML = `
                <div class="alert alert-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Error: ${data.message}
                </div>
            `;
        }
    })
    .catch(error => {
        console.error('Error:', error);
        container.innerHTML = `
            <div class="alert alert-danger">
                <i class="bi bi-exclamation-triangle me-2"></i>
                Terjadi kesalahan saat memperbarui status motor
            </div>
        `;
    });
}

function displayMotorStatus(motors, timestamp) {
    const container = document.getElementById('motor-status-container');
    
    if (motors.length === 0) {
        container.innerHTML = `
            <div class="text-center text-muted py-3">
                <i class="bi bi-inbox me-2"></i>
                Tidak ada motor ditemukan
            </div>
        `;
        return;
    }
    
    let html = `
        <div class="row mb-3">
            <div class="col-12">
                <small class="text-muted">
                    <i class="bi bi-clock me-1"></i>
                    Terakhir diperbarui: ${timestamp}
                </small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Motor</th>
                        <th>Status DB</th>
                        <th>Status Realtime</th>
                        <th>Booking Aktif</th>
                        <th>Penyewa</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    motors.forEach(motor => {
        const statusMatch = motor.database_status === motor.realtime_status;
        const statusBadgeDB = getStatusBadge(motor.database_status);
        const statusBadgeRT = getStatusBadge(motor.realtime_status);
        
        html += `
            <tr class="${!statusMatch ? 'table-warning' : ''}">
                <td>
                    <strong>${motor.brand} ${motor.type_cc}</strong><br>
                    <small class="text-muted">${motor.plate_number}</small>
                </td>
                <td>${statusBadgeDB}</td>
                <td>
                    ${statusBadgeRT}
                    ${!statusMatch ? '<i class="bi bi-exclamation-triangle text-warning ms-1" title="Status tidak sinkron"></i>' : ''}
                </td>
                <td>
                    ${motor.current_booking ? 
                        `<span class="badge bg-info">Ya</span><br>
                         <small class="text-muted">${motor.current_booking.start_date} - ${motor.current_booking.end_date}</small>`
                        : '<span class="badge bg-light text-dark">Tidak</span>'}
                </td>
                <td>
                    ${motor.current_booking ? 
                        `<strong>${motor.current_booking.renter_name}</strong><br>
                         <small class="text-muted">Status: ${motor.current_booking.status}</small>`
                        : '<span class="text-muted">-</span>'}
                </td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
        </div>
    `;
    
    container.innerHTML = html;
}

function getStatusBadge(status) {
    const badges = {
        'available': '<span class="badge bg-success">Tersedia</span>',
        'rented': '<span class="badge bg-warning">Disewa</span>',
        'maintenance': '<span class="badge bg-secondary">Maintenance</span>',
        'pending_verification': '<span class="badge bg-info">Menunggu Verifikasi</span>'
    };
    
    return badges[status] || `<span class="badge bg-light text-dark">${status}</span>`;
}
</script>
@endsection