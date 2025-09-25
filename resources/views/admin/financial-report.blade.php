@extends('layouts.fann')

@section('title', 'Laporan Keuangan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>
        <i class="bi bi-graph-up me-3"></i>Laporan Keuangan
    </h1>
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
                        <a href="{{ route('admin.financial-report.export-pdf', request()->query()) }}" 
                           class="btn btn-success me-2" 
                           target="_blank"
                           onclick="showExportMessage()">
                            <i class="bi bi-download"></i> Export Laporan
                        </a>
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
                                    @if($motor && isset($motor['motor']))
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div>
                                            <strong>{{ isset($motor['motor']->brand) ? $motor['motor']->brand : 'N/A' }} {{ isset($motor['motor']->model) ? $motor['motor']->model : 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ isset($motor['motor']->plate_number) ? $motor['motor']->plate_number : 'N/A' }}</small>
                                        </div>
                                        <div class="text-end">
                                            <span class="badge bg-primary">{{ $motor['booking_count'] ?? 0 }} sewa</span><br>
                                            <small>Rp {{ number_format($motor['total_revenue'] ?? 0, 0, ',', '.') }}</small>
                                        </div>
                                    </div>
                                    @endif
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
                                        <td>{{ optional($transaction->created_at)->format('d/m/Y') ?? 'N/A' }}</td>
                                        <td>
                                            <strong>#BK{{ str_pad($transaction->booking_id ?? 0, 4, '0', STR_PAD_LEFT) }}</strong>
                                        </td>
                                        <td>
                                            {{ optional(optional($transaction->booking)->renter)->name ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ optional(optional($transaction->booking)->renter)->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            {{ optional(optional($transaction->booking)->motor)->brand ?? 'N/A' }} {{ optional(optional($transaction->booking)->motor)->model ?? '' }}<br>
                                            <small class="text-muted">{{ optional(optional($transaction->booking)->motor)->license_plate ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            {{ optional($transaction->owner)->name ?? 'N/A' }}<br>
                                            <small class="text-muted">{{ optional($transaction->owner)->phone ?? 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-success">
                                                Rp {{ number_format($transaction->admin_commission ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="text-primary">
                                                Rp {{ number_format($transaction->owner_amount ?? 0, 0, ',', '.') }}
                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge 
                                                @if(($transaction->status ?? '') == 'paid') bg-success
                                                @elseif(($transaction->status ?? '') == 'pending') bg-warning
                                                @else bg-secondary
                                                @endif">
                                                {{ ucfirst($transaction->status ?? 'unknown') }}
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
                                    @if($ownerData && is_object($ownerData))
                                    <tr>
                                        <td>
                                            <strong>{{ $ownerData->owner ? $ownerData->owner->name : 'N/A' }}</strong><br>
                                            <small class="text-muted">{{ $ownerData->owner ? $ownerData->owner->phone : 'N/A' }}</small>
                                        </td>
                                        <td>
                                            <span class="badge bg-secondary">{{ $ownerData->motor_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $ownerData->transaction_count ?? 0 }}</span>
                                        </td>
                                        <td>
                                            <strong>Rp {{ number_format($ownerData->total_revenue ?? 0, 0, ',', '.') }}</strong>
                                        </td>
                                        <td>
                                            <span class="text-primary">Rp {{ number_format($ownerData->owner_earned ?? 0, 0, ',', '.') }}</span>
                                        </td>
                                        <td>
                                            <span class="text-success">Rp {{ number_format($ownerData->admin_earned ?? 0, 0, ',', '.') }}</span>
                                        </td>
                                    </tr>
                                    @endif
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

function printReport() {
    window.print();
}

function showExportMessage() {
    // Show loading message
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: 'info',
        title: 'PDF sedang dipersiapkan...',
        text: 'Mohon tunggu, file PDF akan diunduh secara otomatis'
    });
}
</script>
@endpush