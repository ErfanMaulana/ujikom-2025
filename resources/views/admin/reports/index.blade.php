@extends('layouts.fann')

@section('title', 'Laporan Keuangan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Laporan Keuangan</h1>
    <p>Analisis pendapatan dan revenue sharing sistem rental</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Laporan Keuangan
                    </h5>
                    <div>
                        <button class="btn btn-success me-2" onclick="exportReport()">
                            <i class="bi bi-download"></i> Export Laporan
                        </button>
                        <button class="btn btn-info" onclick="printReport()">
                            <i class="bi bi-printer"></i> Print
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter Periode -->
                <form method="GET" action="{{ route('admin.financial-report') }}">
                    <div class="row mb-3">
                        <div class="col-md-3">
                                <label for="period" class="form-label">Periode</label>
                                <select class="form-select" id="period" name="period" onchange="toggleDateInputs()">
                                    <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                    <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                    <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                    <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                                    <option value="custom" {{ request('period') == 'custom' ? 'selected' : '' }}>Custom</option>
                                </select>
                            </div>
                            <div class="col-md-3" id="dateFromDiv" style="{{ request('period') == 'custom' ? '' : 'display:none' }}">
                                <label for="date_from" class="form-label">Dari Tanggal</label>
                                <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                            </div>
                            <div class="col-md-3" id="dateToDiv" style="{{ request('period') == 'custom' ? '' : 'display:none' }}">
                                <label for="date_to" class="form-label">Sampai Tanggal</label>
                                <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-grid">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="bi bi-search"></i> Tampilkan Laporan
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Ringkasan Keuangan -->
                <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Total Pendapatan</p>
                                </div>
                                <i class="bi bi-cash-stack fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>Rp {{ number_format($summary['admin_commission'] ?? 0, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Komisi Admin (10%)</p>
                                </div>
                                <i class="bi bi-percent fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>Rp {{ number_format($summary['owner_share'] ?? 0, 0, ',', '.') }}</h4>
                                    <p class="mb-0">Bagian Pemilik (90%)</p>
                                </div>
                                <i class="bi bi-person-check fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-info text-white">
                        <div class="card-body">
                            <div class="d-flex justify-content-between">
                                <div>
                                    <h4>{{ $summary['total_bookings'] ?? 0 }}</h4>
                                    <p class="mb-0">Total Transaksi</p>
                                </div>
                                <i class="bi bi-graph-up fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Grafik Pendapatan -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="card">
                        <div class="card-header">
                            <h5>Trend Pendapatan</h5>
                        </div>
                        <div class="card-body">
                            <canvas id="revenueChart" style="height: 300px;"></canvas>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-header">
                            <h5>Top Motor Terlaris</h5>
                        </div>
                        <div class="card-body">
                            @forelse($topMotors ?? [] as $motor)
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div>
                                    <strong>{{ $motor->brand }} {{ $motor->model }}</strong><br>
                                    <small class="text-muted">{{ $motor->license_plate }}</small>
                                </div>
                                <span class="badge bg-primary">{{ $motor->bookings_count }} sewa</span>
                            </div>
                            @empty
                            <p class="text-center text-muted">Belum ada data</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tabel Detail Transaksi -->
            <div class="card">
                <div class="card-header">
                    <h5>Detail Transaksi</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead class="table-dark">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>Kode Booking</th>
                                    <th>Penyewa</th>
                                    <th>Motor</th>
                                    <th>Pemilik</th>
                                    <th>Total</th>
                                    <th>Komisi Admin</th>
                                    <th>Bagian Pemilik</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transactions ?? [] as $transaction)
                                <tr>
                                    <td>{{ \Carbon\Carbon::parse($transaction->created_at)->format('d/m/Y') }}</td>
                                    <td>
                                        <strong>{{ $transaction->booking_code }}</strong>
                                    </td>
                                    <td>
                                        {{ $transaction->user->name }}<br>
                                        <small class="text-muted">{{ $transaction->user->phone }}</small>
                                    </td>
                                    <td>
                                        {{ $transaction->motor->brand }} {{ $transaction->motor->model }}<br>
                                        <small class="text-muted">{{ $transaction->motor->license_plate }}</small>
                                    </td>
                                    <td>
                                        {{ $transaction->motor->owner->name }}<br>
                                        <small class="text-muted">{{ $transaction->motor->owner->phone }}</small>
                                    </td>
                                    <td>
                                        <strong>Rp {{ number_format($transaction->price, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            Rp {{ number_format($transaction->price * 0.1, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-primary">
                                            Rp {{ number_format($transaction->price * 0.9, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="badge 
                                            @if($transaction->status == 'completed') bg-success
                                            @elseif($transaction->status == 'ongoing') bg-info
                                            @elseif($transaction->status == 'confirmed') bg-primary
                                            @elseif($transaction->status == 'pending') bg-warning
                                            @else bg-danger
                                            @endif">
                                            {{ ucfirst($transaction->status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center">Tidak ada transaksi dalam periode ini</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if(isset($transactions) && $transactions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>

            <!-- Ringkasan per Pemilik -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5>Ringkasan per Pemilik Motor</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead class="table-dark">
                                <tr>
                                    <th>Pemilik</th>
                                    <th>Jumlah Motor</th>
                                    <th>Total Transaksi</th>
                                    <th>Total Pendapatan</th>
                                    <th>Bagian Pemilik (90%)</th>
                                    <th>Komisi Admin (10%)</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ownerSummary ?? [] as $owner)
                                <tr>
                                    <td>
                                        <strong>{{ $owner->name }}</strong><br>
                                        <small class="text-muted">{{ $owner->phone }}</small>
                                    </td>
                                    <td>{{ $owner->motors_count }}</td>
                                    <td>{{ $owner->bookings_count }}</td>
                                    <td>
                                        <strong>Rp {{ number_format($owner->total_revenue, 0, ',', '.') }}</strong>
                                    </td>
                                    <td>
                                        <span class="text-primary">
                                            Rp {{ number_format($owner->owner_share, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td>
                                        <span class="text-success">
                                            Rp {{ number_format($owner->admin_commission, 0, ',', '.') }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center">Tidak ada data pemilik</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    @if($transactions->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $transactions->links() }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
.card-body canvas {
    max-height: 300px;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
function toggleDateInputs() {
    const period = document.getElementById('period').value;
    const dateFromDiv = document.getElementById('dateFromDiv');
    const dateToDiv = document.getElementById('dateToDiv');
    
    if (period === 'custom') {
        dateFromDiv.style.display = 'block';
        dateToDiv.style.display = 'block';
    } else {
        dateFromDiv.style.display = 'none';
        dateToDiv.style.display = 'none';
    }
}

// Revenue Chart
const ctx = document.getElementById('revenueChart').getContext('2d');
const revenueChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: @json($chartData['labels'] ?? []),
        datasets: [{
            label: 'Pendapatan Harian',
            data: @json($chartData['revenue'] ?? []),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.1
        }, {
            label: 'Komisi Admin',
            data: @json($chartData['commission'] ?? []),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            tension: 0.1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
                    }
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                    }
                }
            }
        }
    }
});

function exportReport() {
    const params = new URLSearchParams(window.location.search);
    window.open(`/admin/reports/export?${params.toString()}`, '_blank');
}

function printReport() {
    window.print();
}
</script>
@endpush