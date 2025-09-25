<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pendapatan - {{ $owner->name }}</title>
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
        .owner-info {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #dee2e6;
        }
        .owner-info h3 {
            margin: 0 0 10px 0;
            color: #2c3e50;
            font-size: 16px;
        }
        .owner-info p {
            margin: 3px 0;
            font-size: 11px;
        }
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            margin-bottom: 30px;
        }
        .summary-card {
            flex: 1;
            min-width: 180px;
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 15px;
            background-color: #f8f9fa;
        }
        .summary-card h3 {
            margin: 0 0 10px 0;
            font-size: 12px;
            color: #495057;
        }
        .summary-card .amount {
            font-size: 16px;
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
            font-size: 10px;
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
            font-size: 9px;
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
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        .page-break {
            page-break-before: always;
        }
        .revenue-breakdown {
            background-color: #e8f4fd;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .revenue-breakdown h4 {
            margin: 0 0 10px 0;
            color: #0d6efd;
            font-size: 14px;
        }
        .revenue-item {
            display: flex;
            justify-content: space-between;
            margin: 5px 0;
            font-size: 11px;
        }
        .revenue-item strong {
            color: #2c3e50;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENDAPATAN PEMILIK</h1>
        <p><strong>FannRental - Sistem Penyewaan Motor</strong></p>
        <p>Periode: {{ $dateRange }}</p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Owner Information -->
    <div class="owner-info">
        <h3>Informasi Pemilik Motor</h3>
        <p><strong>Nama:</strong> {{ $owner->name }}</p>
        <p><strong>Email:</strong> {{ $owner->email }}</p>
        <p><strong>No. HP:</strong> {{ $owner->phone ?? '-' }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Pendapatan Anda</h3>
            <div class="amount">Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</div>
            <small>Bagian Anda (70%)</small>
        </div>
        <div class="summary-card">
            <h3>Total Transaksi</h3>
            <div class="amount">{{ $summary['total_bookings'] }}</div>
            <small>Booking Selesai</small>
        </div>
        <div class="summary-card">
            <h3>Pendapatan Kotor</h3>
            <div class="amount">Rp {{ number_format($summary['total_gross_revenue'], 0, ',', '.') }}</div>
            <small>Sebelum Bagi Hasil</small>
        </div>
    </div>

    <!-- Revenue Breakdown -->
    <div class="revenue-breakdown">
        <h4>Rincian Bagi Hasil</h4>
        <div class="revenue-item">
            <span>Total Pendapatan Kotor:</span>
            <strong>Rp {{ number_format($summary['total_gross_revenue'], 0, ',', '.') }}</strong>
        </div>
        <div class="revenue-item">
            <span>Bagian Pemilik ({{ $summary['owner_percentage'] }}%):</span>
            <strong>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</strong>
        </div>
        <div class="revenue-item">
            <span>Komisi Admin ({{ $summary['admin_percentage'] }}%):</span>
            <strong>Rp {{ number_format($summary['total_admin_commission'], 0, ',', '.') }}</strong>
        </div>
    </div>

    <!-- Detailed Transactions -->
    <div class="section">
        <h2>Detail Transaksi</h2>
        @if($revenues->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">No</th>
                        <th style="width: 15%;">Tanggal</th>
                        <th style="width: 20%;">Motor</th>
                        <th style="width: 17%;">Penyewa</th>
                        <th style="width: 15%;">Pendapatan Kotor</th>
                        <th style="width: 15%;">Bagian Anda</th>
                        <th style="width: 10%;">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($revenues as $index => $revenue)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td>{{ $revenue->created_at->format('d/m/Y') }}</td>
                            <td>
                                <strong>{{ $revenue->booking->motor->brand }}</strong><br>
                                <small>{{ $revenue->booking->motor->plate_number }}</small>
                            </td>
                            <td>
                                {{ $revenue->booking->user->name }}<br>
                                <small>{{ $revenue->booking->user->phone ?? '-' }}</small>
                            </td>
                            <td class="text-right">Rp {{ number_format($revenue->total_amount, 0, ',', '.') }}</td>
                            <td class="text-right">
                                <strong>Rp {{ number_format($revenue->owner_amount, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                @if($revenue->status === 'paid')
                                    <span class="badge badge-success">Dibayar</span>
                                @else
                                    <span class="badge badge-warning">Pending</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #e9ecef; font-weight: bold;">
                        <td colspan="4" class="text-right"><strong>TOTAL:</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($summary['total_gross_revenue'], 0, ',', '.') }}</strong></td>
                        <td class="text-right"><strong>Rp {{ number_format($summary['total_revenue'], 0, ',', '.') }}</strong></td>
                        <td class="text-center">-</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="text-center" style="padding: 40px; color: #6c757d;">
                <p><strong>Tidak ada data transaksi untuk periode yang dipilih.</strong></p>
                <p>Silakan pilih periode lain atau mulai menyewakan motor Anda.</p>
            </div>
        @endif
    </div>

    @if($revenues->count() > 0)
        <!-- Additional Information -->
        <div class="section">
            <h2>Informasi Tambahan</h2>
            <div style="background-color: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6;">
                <h4 style="margin-top: 0; color: #495057;">Ketentuan Bagi Hasil:</h4>
                <ul style="margin: 10px 0; padding-left: 20px; font-size: 11px;">
                    <li>Pemilik motor mendapat <strong>70%</strong> dari total pendapatan kotor</li>
                    <li>Admin platform mendapat <strong>30%</strong> sebagai komisi operasional</li>
                    <li>Pembayaran dilakukan setelah masa sewa selesai dan kendaraan dikembalikan</li>
                    <li>Laporan ini mencakup semua transaksi yang telah selesai dalam periode yang dipilih</li>
                </ul>
                
                <h4 style="color: #495057;">Kontak Support:</h4>
                <p style="margin: 10px 0; font-size: 11px;">
                    Jika ada pertanyaan terkait laporan ini, silakan hubungi tim support FannRental:<br>
                    <strong>Email:</strong> support@fannrental.com<br>
                    <strong>WhatsApp:</strong> +62 812-3456-7890
                </p>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>FannRental - Sistem Penyewaan Motor</strong></p>
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p style="margin-top: 10px; font-size: 9px; color: #adb5bd;">
            Dokumen ini bersifat rahasia dan hanya untuk pemilik motor yang bersangkutan
        </p>
    </div>
</body>
</html>