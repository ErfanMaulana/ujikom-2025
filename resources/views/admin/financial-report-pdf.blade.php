<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan - {{ now()->format('d M Y') }}</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
        }
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-card {
            flex: 1;
            min-width: 200px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }
        .summary-card .amount {
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 30px;
        }
        .section h2 {
            font-size: 18px;
            color: #2c3e50;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 8px;
            margin-bottom: 15px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table th,
        table td {
            border: 1px solid #dee2e6;
            padding: 8px;
            text-align: left;
            font-size: 11px;
        }
        table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        table tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
        }
        .badge-success {
            background-color: #d4edda;
            color: #155724;
        }
        .badge-warning {
            background-color: #fff3cd;
            color: #856404;
        }
        .badge-secondary {
            background-color: #e2e3e5;
            color: #383d41;
        }
        .footer {
            margin-top: 50px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        .page-break {
            page-break-before: always;
        }
        .no-data {
            text-align: center;
            color: #6c757d;
            font-style: italic;
            padding: 20px;
        }
        .highlight {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 10px;
            margin: 10px 0;
        }
        .total-row {
            font-weight: bold;
            background-color: #f8f9fa;
            border-top: 2px solid #dee2e6;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN KEUANGAN</h1>
        <p>Sistem Penyewaan Motor</p>
        <p>Periode: {{ $dateRange ?? 'Semua Data' }}</p>
        <p>Tanggal Cetak: {{ now()->format('d F Y, H:i:s') }} WIB</p>
    </div>

    <!-- Summary Cards -->
    <div class="section">
        <h2>Ringkasan Keuangan</h2>
        <table>
            <tr>
                <td><strong>Total Pendapatan</strong></td>
                <td class="text-right"><strong>Rp {{ number_format($summary['total_revenue'] ?? 0, 0, ',', '.') }}</strong></td>
            </tr>
            <tr>
                <td>Bagian Pemilik (70%)</td>
                <td class="text-right">Rp {{ number_format($summary['owner_amount'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Komisi Admin (30%)</td>
                <td class="text-right">Rp {{ number_format($summary['admin_commission'] ?? 0, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Transaksi</td>
                <td class="text-right">{{ $summary['total_bookings'] ?? 0 }} transaksi</td>
            </tr>
            <tr>
                <td>Transaksi Pending</td>
                <td class="text-right">{{ $summary['pending_settlements'] ?? 0 }} transaksi</td>
            </tr>
            <tr>
                <td>Transaksi Selesai</td>
                <td class="text-right">{{ $summary['completed_settlements'] ?? 0 }} transaksi</td>
            </tr>
        </table>
    </div>

    <!-- Daftar Transaksi -->
    <div class="section">
        <h2>Daftar Transaksi</h2>
        <table>
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>ID Booking</th>
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
                    <td>#BK{{ str_pad($transaction->booking_id ?? 0, 4, '0', STR_PAD_LEFT) }}</td>
                    <td>
                        {{ optional(optional($transaction->booking)->renter)->name ?? 'N/A' }}<br>
                        <small>{{ optional(optional($transaction->booking)->renter)->phone ?? 'N/A' }}</small>
                    </td>
                    <td>
                        {{ optional(optional($transaction->booking)->motor)->brand ?? 'N/A' }} {{ optional(optional($transaction->booking)->motor)->model ?? '' }}<br>
                        <small>{{ optional(optional($transaction->booking)->motor)->plate_number ?? 'N/A' }}</small>
                    </td>
                    <td>
                        {{ optional($transaction->owner)->name ?? 'N/A' }}<br>
                        <small>{{ optional($transaction->owner)->phone ?? 'N/A' }}</small>
                    </td>
                    <td class="text-right">Rp {{ number_format($transaction->total_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->admin_commission ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($transaction->owner_amount ?? 0, 0, ',', '.') }}</td>
                    <td class="text-center">
                        <span class="badge 
                            @if(($transaction->status ?? '') == 'paid') badge-success
                            @elseif(($transaction->status ?? '') == 'pending') badge-warning
                            @else badge-secondary
                            @endif">
                            {{ ucfirst($transaction->status ?? 'unknown') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data transaksi</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Page Break untuk halaman kedua -->
    <div class="page-break"></div>

    <!-- Ringkasan per Pemilik -->
    <div class="section">
        <h2>Ringkasan per Pemilik Motor</h2>
        <table>
            <thead>
                <tr>
                    <th>Pemilik</th>
                    <th>Jumlah Motor</th>
                    <th>Jumlah Transaksi</th>
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
                        <small>{{ $ownerData->owner ? $ownerData->owner->phone : 'N/A' }}</small>
                    </td>
                    <td class="text-center">{{ $ownerData->motor_count ?? 0 }}</td>
                    <td class="text-center">{{ $ownerData->transaction_count ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format($ownerData->total_revenue ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($ownerData->owner_earned ?? 0, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($ownerData->admin_earned ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" class="text-center">Tidak ada data pemilik</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Top Motors -->
    @if(!empty($topMotors) && count($topMotors) > 0)
    <div class="section">
        <h2>Motor Terpopuler</h2>
        <table>
            <thead>
                <tr>
                    <th>Motor</th>
                    <th>Jumlah Booking</th>
                    <th>Total Pendapatan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($topMotors as $motorData)
                @if(isset($motorData['motor']))
                <tr>
                    <td>
                        <strong>{{ $motorData['motor']->brand ?? 'N/A' }} {{ $motorData['motor']->model ?? '' }}</strong><br>
                        <small>{{ $motorData['motor']->plate_number ?? 'N/A' }}</small>
                    </td>
                    <td class="text-center">{{ $motorData['booking_count'] ?? 0 }}</td>
                    <td class="text-right">Rp {{ number_format($motorData['total_revenue'] ?? 0, 0, ',', '.') }}</td>
                </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Dokumen ini digenerate secara otomatis oleh Sistem Penyewaan Motor</p>
        <p>© {{ date('Y') }} - Semua hak dilindungi</p>
    </div>
</body>
</html>