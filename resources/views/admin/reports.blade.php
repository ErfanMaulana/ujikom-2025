@extends('layouts.fann')

@section('title', 'Laporan Keuangan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Laporan Keuangan</h1>
    <p>Analisis pendapatan dan pembagian revenue sharing</p>
</div>

<!-- Financial Summary Cards -->
<div class="row mb-4">
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-gradient-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0">Total Pendapatan</p>
                    </div>
                    <i class="bi bi-cash-stack" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-gradient-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Rp {{ number_format($adminCommission ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0">Komisi Admin ({{ $commissionRate ?? 10 }}%)</p>
                    </div>
                    <i class="bi bi-percent" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-gradient-info text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">Rp {{ number_format($ownerRevenue ?? 0, 0, ',', '.') }}</h3>
                        <p class="mb-0">Pendapatan Pemilik</p>
                    </div>
                    <i class="bi bi-person-check" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-6 mb-3">
        <div class="card border-0 shadow-sm bg-gradient-warning text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h3 class="mb-0">{{ $totalTransactions ?? 0 }}</h3>
                        <p class="mb-0">Total Transaksi</p>
                    </div>
                    <i class="bi bi-graph-up" style="font-size: 2rem; opacity: 0.7;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart Section -->
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Grafik Pendapatan Bulanan</h5>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0">Distribusi Pendapatan</h5>
            </div>
            <div class="card-body">
                <canvas id="distributionChart"></canvas>
                <div class="mt-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Komisi Admin</span>
                        <span class="fw-bold">{{ $commissionRate ?? 10 }}%</span>
                    </div>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="text-muted">Pendapatan Pemilik</span>
                        <span class="fw-bold">{{ 100 - ($commissionRate ?? 10) }}%</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter -->
<div class="row mb-4">
    <div class="col-md-12">
        <div class="card border-0 shadow-sm">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.reports') }}" class="row g-3">
                    <div class="col-md-2">
                        <label class="form-label">Periode</label>
                        <select class="form-select" name="period">
                            <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua Periode</option>
                            <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                            <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                            <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                            <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Dari Tanggal</label>
                        <input type="date" class="form-control" name="start_date" value="{{ request('start_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Sampai Tanggal</label>
                        <input type="date" class="form-control" name="end_date" value="{{ request('end_date') }}">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Status</label>
                        <select class="form-select" name="status">
                            <option value="">Semua Status</option>
                            <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Paid</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Cari Pemilik</label>
                        <input type="text" class="form-control" name="search" value="{{ request('search') }}" placeholder="Nama pemilik motor...">
                    </div>
                    <div class="col-md-1">
                        <label class="form-label">&nbsp;</label>
                        <button class="btn btn-primary w-100" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Revenue Sharing Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white border-bottom">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Detail Revenue Sharing</h5>
            <div class="dropdown">
                <button class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="bi bi-download me-1"></i>Export
                </button>
                <ul class="dropdown-menu">
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-excel me-2"></i>Excel</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-file-pdf me-2"></i>PDF</a></li>
                    <li><a class="dropdown-item" href="#"><i class="bi bi-printer me-2"></i>Print</a></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($revenueSharing->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="bg-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Booking ID</th>
                            <th>Pemilik Motor</th>
                            <th>Motor</th>
                            <th>Total Transaksi</th>
                            <th>Komisi Admin</th>
                            <th>Pendapatan Pemilik</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($revenueSharing as $revenue)
                        <tr>
                            <td>{{ $revenue->created_at->format('d M Y') }}</td>
                            <td>
                                <span class="fw-bold text-primary">#{{ $revenue->booking_id }}</span>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-info rounded-circle text-white d-flex align-items-center justify-content-center me-3">
                                        {{ substr($revenue->owner->name, 0, 1) }}
                                    </div>
                                    <div>
                                        <div class="fw-bold">{{ $revenue->owner->name }}</div>
                                        <small class="text-muted">{{ $revenue->owner->email }}</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div>
                                    <div class="fw-bold">{{ $revenue->booking->motor->brand ?? 'N/A' }}</div>
                                    <small class="text-muted">{{ $revenue->booking->motor->plate_number ?? 'N/A' }}</small>
                                </div>
                            </td>
                            <td>
                                <span class="fw-bold text-primary">Rp {{ number_format($revenue->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="text-success">Rp {{ number_format($revenue->admin_commission, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <span class="text-info">Rp {{ number_format($revenue->owner_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                @if($revenue->status === 'paid')
                                    <span class="badge bg-success">Paid</span>
                                @elseif($revenue->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @else
                                    <span class="badge bg-secondary">{{ ucfirst($revenue->status) }}</span>
                                @endif
                            </td>
                            <td>
                                <div class="dropdown">
                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        Aksi
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#">
                                            <i class="bi bi-eye me-2"></i>Detail
                                        </a></li>
                                        @if($revenue->status === 'pending')
                                            <li><a class="dropdown-item text-success" href="#" onclick="markAsPaid({{ $revenue->id }})">
                                                <i class="bi bi-check-circle me-2"></i>Mark as Paid
                                            </a></li>
                                        @endif
                                        <li><a class="dropdown-item" href="#">
                                            <i class="bi bi-download me-2"></i>Download Invoice
                                        </a></li>
                                    </ul>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-5">
                <i class="bi bi-graph-down text-muted" style="font-size: 5rem;"></i>
                <h4 class="mt-3 text-muted">Belum ada data revenue sharing</h4>
                <p class="text-muted">Data akan muncul setelah ada transaksi yang completed</p>
            </div>
        @endif
    </div>
</div>

<!-- Pagination -->
@if($revenueSharing->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $revenueSharing->links() }}
    </div>
@endif

<!-- Mark as Paid Modal -->
<div class="modal fade" id="paidModal" tabindex="-1" aria-labelledby="paidModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="paidModalLabel">Konfirmasi Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menandai revenue sharing ini sebagai telah dibayar?</p>
                <p class="text-muted">Aksi ini tidak dapat dibatalkan.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <form id="paidForm" method="POST" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success">Mark as Paid</button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(revenueCtx, {
    type: 'line',
    data: {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        datasets: [{
            label: 'Pendapatan',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0], // Data akan diisi dari backend
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.1,
            fill: true
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: false
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + value.toLocaleString();
                    }
                }
            }
        }
    }
});

// Distribution Chart
const distributionCtx = document.getElementById('distributionChart').getContext('2d');
const distributionChart = new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: ['Komisi Admin', 'Pendapatan Pemilik'],
        datasets: [{
            data: [{{ $commissionRate ?? 10 }}, {{ 100 - ($commissionRate ?? 10) }}],
            backgroundColor: [
                '#28a745',
                '#17a2b8'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true,
        plugins: {
            legend: {
                position: 'bottom'
            }
        }
    }
});

function markAsPaid(revenueId) {
    const form = document.getElementById('paidForm');
    form.action = `/admin/revenue-sharing/${revenueId}/mark-paid`;
    
    const modal = new bootstrap.Modal(document.getElementById('paidModal'));
    modal.show();
}
</script>

<style>
.avatar-sm {
    width: 40px;
    height: 40px;
    font-size: 0.875rem;
}

.bg-gradient-primary {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

.bg-gradient-success {
    background: linear-gradient(45deg, #28a745, #1e7e34);
}

.bg-gradient-info {
    background: linear-gradient(45deg, #17a2b8, #117a8b);
}

.bg-gradient-warning {
    background: linear-gradient(45deg, #ffc107, #e0a800);
}
</style>
@endsection