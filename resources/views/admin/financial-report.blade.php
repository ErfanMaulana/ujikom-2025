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
                            <label for="date_from" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="{{ request('date_from') }}">
                        </div>
                        <div class="col-md-3">
                            <label for="date_to" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="{{ request('date_to') }}">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter Laporan
                                </button>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-grid">
                                <a href="{{ route('admin.financial-report') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-clockwise"></i> Reset Filter
                                </a>
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
                                        <p class="mb-0">Komisi Admin (30%)</p>
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
                                        <h4>Rp {{ number_format($summary['owner_amount'] ?? 0, 0, ',', '.') }}</h4>
                                        <p class="mb-0">Bagian Pemilik (70%)</p>
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
                                @if(count($chartData['labels'] ?? []) > 0)
                                    <canvas id="revenueChart" style="height: 300px;"></canvas>
                                @else
                                    <div class="text-center text-muted py-5">
                                        <i class="bi bi-graph-up fs-1"></i>
                                        <p class="mt-2">Belum ada data untuk ditampilkan dalam grafik</p>
                                    </div>
                                @endif
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
                                        <strong>{{ $motor->motor->brand ?? 'N/A' }} {{ $motor->motor->model ?? 'N/A' }}</strong><br>
                                        <small class="text-muted">{{ $motor->motor->license_plate ?? 'N/A' }}</small>
                                    </div>
                                    <div class="text-end">
                                        <span class="badge bg-primary">{{ $motor->booking_count }} sewa</span><br>
                                        <small>Rp {{ number_format($motor->total_revenue, 0, ',', '.') }}</small>
                                    </div>
                                </div>
                                @empty
                                <div class="text-center text-muted">
                                    <i class="bi bi-trophy fs-3"></i>
                                    <p class="mt-2">Belum ada data</p>
                                </div>
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
                                            <strong>#BK{{ str_pad($transaction->id, 4, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td>
                                            {{ $transaction->renter->name }}<br>
                                            <small class="text-muted">{{ $transaction->renter->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            {{ $transaction->motor->brand }} {{ $transaction->motor->model }}<br>
                                            <small class="text-muted">{{ $transaction->motor->license_plate }}</small>
                                        </td>
                                        <td>
                                            {{ $transaction->motor->owner->name }}<br>
                                            <small class="text-muted">{{ $transaction->motor->owner->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($transaction->price, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-success">
                                                Rp {{ number_format($transaction->price * 0.3, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary">
                                                Rp {{ number_format($transaction->price * 0.7, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if($transaction->status == 'completed') bg-success
                                                @elseif($transaction->status == 'active') bg-info
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
                                        <td colspan="9" class="text-center text-muted py-4">
                                            <i class="bi bi-inbox fs-3"></i>
                                            <p class="mt-2">Tidak ada transaksi dalam periode ini</p>
                                        </td>
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
                                        <th>Bagian Pemilik (70%)</th>
                                        <th>Komisi Admin (30%)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ownerSummary ?? [] as $ownerData)
                                    <tr>
                                        <td>
                                            <strong>{{ $ownerData->owner->name }}</strong><br>
                                            <small class="text-muted">{{ $ownerData->owner->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $ownerData->owner->ownedMotors->count() ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $ownerData->transaction_count }}</span>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($ownerData->total_revenue, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-primary">
                                                Rp {{ number_format($ownerData->owner_earned, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-success">
                                                Rp {{ number_format($ownerData->admin_earned, 0, ',', '.') }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bi bi-people fs-3"></i>
                                            <p class="mt-2">Tidak ada data pemilik</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
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
.content-header {
    margin-bottom: 1.5rem;
}
.content-header h1 {
    font-size: 2rem;
    font-weight: 600;
    color: #1f2937;
}
.content-header p {
    color: #6b7280;
    margin-bottom: 0;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Revenue Chart
    @if(count($chartData['labels'] ?? []) > 0)
    const ctx = document.getElementById('revenueChart');
    if (ctx) {
        const revenueChart = new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: @json($chartData['labels'] ?? []),
                datasets: [
                    {
                        label: 'Total Pendapatan',
                        data: @json($chartData['revenue'] ?? []),
                        borderColor: 'rgb(59, 130, 246)',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 3,
                        fill: true,
                        tension: 0.4
                    },
                    {
                        label: 'Komisi Admin (30%)',
                        data: @json($chartData['admin_commission'] ?? []),
                        borderColor: 'rgb(34, 197, 94)',
                        backgroundColor: 'rgba(34, 197, 94, 0.1)',
                        borderWidth: 2,
                        borderDash: [5, 5],
                        fill: false
                    },
                    {
                        label: 'Bagian Pemilik (70%)',
                        data: @json($chartData['owner_share'] ?? []),
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
                                return 'Rp ' + new Intl.NumberFormat('id-ID').format(value);
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
                                return context.dataset.label + ': Rp ' + new Intl.NumberFormat('id-ID').format(context.parsed.y);
                            }
                        }
                    }
                }
            }
        });
    }
    @endif
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