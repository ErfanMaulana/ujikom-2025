@extends('layouts.fann')

@section('title', 'Laporan Pendapatan')

@section('content')
<!-- Content Header -->
<div class="content-header">
    <h1>Laporan Pendapatan</h1>
    <p>Analisis pendapatan dari penyewaan motor Anda</p>
</div>

<div class="row">
    <div class="col-12">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Laporan Pendapatan Motor
                    </h5>
                    <div>
                        <form method="GET" action="{{ route('pemilik.revenue.export.pdf') }}" style="display: inline;">
                            <input type="hidden" name="month" value="{{ request('month') }}">
                            <input type="hidden" name="year" value="{{ request('year') }}">
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-file-pdf"></i> Export Laporan
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <!-- Filter Periode -->
                <form method="GET" action="{{ route('pemilik.revenue.report') }}">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="month" class="form-label">Bulan</label>
                            <select class="form-select" name="month">
                                <option value="">Semua Bulan</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                        {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="year" class="form-label">Tahun</label>
                            <select class="form-select" name="year">
                                <option value="">Semua Tahun</option>
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>
                                        {{ $y }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">&nbsp;</label>
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search"></i> Filter
                                </button>
                                <a href="{{ route('pemilik.revenue.report') }}" class="btn btn-outline-secondary">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <!-- Ringkasan Pendapatan -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}</h4>
                                        <p class="mb-0">Total Pendapatan Anda</p>
                                    </div>
                                    <i class="bi bi-cash-stack fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>{{ $revenues->total() }}</h4>
                                        <p class="mb-0">Total Transaksi</p>
                                    </div>
                                    <i class="bi bi-list-check fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h4>70%</h4>
                                        <p class="mb-0">Bagi Hasil untuk Anda</p>
                                    </div>
                                    <i class="bi bi-percent fs-1"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabel Riwayat Pendapatan -->
                <div class="table-responsive">
                    @if($revenues->count() > 0)
                        <table class="table table-hover">
                            <thead class="bg-light">
                                <tr>
                                    <th>Tanggal</th>
                                    <th>ID Booking</th>
                                    <th>Motor</th>
                                    <th>Penyewa</th>
                                    <th>Total Booking</th>
                                    <th>Pendapatan Anda</th>
                                    <th>Komisi Admin</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($revenues as $revenue)
                                <tr>
                                    <td>{{ $revenue->created_at->format('d M Y') }}</td>
                                    <td>
                                        <span class="fw-bold text-primary">#{{ $revenue->booking_id }}</span>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $revenue->booking->motor->brand }} {{ $revenue->booking->motor->model }}</div>
                                            <small class="text-muted">{{ $revenue->booking->motor->plate_number }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <div>
                                            <div class="fw-bold">{{ $revenue->booking->renter->name }}</div>
                                            <small class="text-muted">{{ $revenue->booking->renter->email }}</small>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="fw-bold">Rp {{ number_format($revenue->total_amount, 0, ',', '.') }}</span>
                                    </td>
                                    <td>
                                        <span class="fw-bold text-success">Rp {{ number_format($revenue->owner_amount, 0, ',', '.') }}</span>
                                        <br><small class="text-muted">70% dari total</small>
                                    </td>
                                    <td>
                                        <span class="text-muted">Rp {{ number_format($revenue->admin_commission, 0, ',', '.') }}</span>
                                        <br><small class="text-muted">30% komisi</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-success">Selesai</span>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="bg-light">
                                <tr>
                                    <th colspan="5" class="text-end">Total:</th>
                                    <th class="text-success">Rp {{ number_format($revenues->sum('owner_amount'), 0, ',', '.') }}</th>
                                    <th class="text-muted">Rp {{ number_format($revenues->sum('admin_commission'), 0, ',', '.') }}</th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    @else
                        <div class="text-center py-5">
                            <i class="bi bi-graph-down text-muted" style="font-size: 5rem;"></i>
                            <h4 class="mt-3 text-muted">Belum ada data pendapatan</h4>
                            <p class="text-muted">Pendapatan akan muncul setelah booking motor Anda selesai</p>
                        </div>
                    @endif
                </div>

                <!-- Pagination -->
                @if($revenues->hasPages())
                    <div class="d-flex justify-content-center mt-4">
                        {{ $revenues->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Info Panel -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body">
                <h6 class="fw-bold mb-3">
                    <i class="bi bi-info-circle me-2"></i>Informasi Bagi Hasil
                </h6>
                <div class="row">
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Anda mendapat <strong>70%</strong> dari setiap booking yang selesai
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Admin mendapat <strong>30%</strong> sebagai komisi platform
                            </li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-unstyled mb-0">
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Pendapatan dihitung otomatis saat booking selesai
                            </li>
                            <li class="mb-2">
                                <i class="bi bi-check-circle text-success me-2"></i>
                                Laporan dapat didownload dalam format PDF
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.content-header {
    margin-bottom: 2rem;
}

.content-header h1 {
    font-size: 2rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.5rem;
}

.content-header p {
    color: #6c757d;
    margin-bottom: 0;
}

.card {
    transition: all 0.3s ease;
}

.card:hover {
    transform: translateY(-2px);
}

.table th {
    border-top: none;
    font-weight: 600;
    color: #495057;
    font-size: 0.875rem;
}

.table tfoot th {
    border-top: 2px solid #dee2e6;
    font-weight: 700;
}

.badge {
    font-size: 0.75rem;
    padding: 0.375rem 0.75rem;
}
</style>
@endsection