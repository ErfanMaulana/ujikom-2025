<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Bookings - FannRental</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 11px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 15px;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
            margin-bottom: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 22px;
            color: #2c3e50;
        }
        .header p {
            margin: 5px 0;
            color: #7f8c8d;
            font-size: 12px;
        }
        .summary-cards {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 25px;
        }
        .summary-card {
            flex: 1;
            min-width: 120px;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 10px;
            background-color: #f8f9fa;
            text-align: center;
        }
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 11px;
            color: #495057;
        }
        .summary-card .number {
            font-size: 16px;
            font-weight: bold;
            color: #2c3e50;
        }
        .section {
            margin-bottom: 25px;
        }
        .section h2 {
            font-size: 16px;
            color: #2c3e50;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        table th,
        table td {
            border: 1px solid #dee2e6;
            padding: 6px;
            text-align: left;
            font-size: 9px;
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
            padding: 2px 5px;
            border-radius: 3px;
            font-size: 8px;
            font-weight: bold;
            color: white;
        }
        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }
        .badge-info {
            background-color: #17a2b8;
        }
        .badge-success {
            background-color: #28a745;
        }
        .badge-primary {
            background-color: #007bff;
        }
        .badge-danger {
            background-color: #dc3545;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #6c757d;
            border-top: 1px solid #dee2e6;
            padding-top: 12px;
        }
        .filter-info {
            background-color: #e8f4fd;
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 20px;
            border-left: 4px solid #0d6efd;
        }
        .filter-info h4 {
            margin: 0 0 5px 0;
            color: #0d6efd;
            font-size: 12px;
        }
        .filter-info p {
            margin: 0;
            font-size: 10px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN BOOKINGS</h1>
        <p><strong>FannRental - Sistem Penyewaan Motor</strong></p>
        <p>Dicetak pada: {{ now()->format('d F Y, H:i') }} WIB</p>
    </div>

    <!-- Filter Information -->
    <div class="filter-info">
        <h4>Filter yang Diterapkan:</h4>
        <p>{{ $summary['filter_text'] }}</p>
    </div>

    <!-- Summary Cards -->
    <div class="summary-cards">
        <div class="summary-card">
            <h3>Total Bookings</h3>
            <div class="number">{{ $summary['total_bookings'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Pending</h3>
            <div class="number">{{ $summary['pending_bookings'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Confirmed</h3>
            <div class="number">{{ $summary['confirmed_bookings'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Active</h3>
            <div class="number">{{ $summary['active_bookings'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Completed</h3>
            <div class="number">{{ $summary['completed_bookings'] }}</div>
        </div>
        <div class="summary-card">
            <h3>Cancelled</h3>
            <div class="number">{{ $summary['cancelled_bookings'] }}</div>
        </div>
    </div>

    <!-- Bookings Table -->
    <div class="section">
        <h2>Detail Bookings</h2>
        @if($bookings->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th style="width: 8%;">Booking ID</th>
                        <th style="width: 18%;">Penyewa</th>
                        <th style="width: 18%;">Motor</th>
                        <th style="width: 12%;">Tanggal Booking</th>
                        <th style="width: 15%;">Periode Sewa</th>
                        <th style="width: 12%;">Total Biaya</th>
                        <th style="width: 10%;">Status</th>
                        <th style="width: 7%;">Durasi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $booking)
                        <tr>
                            <td class="text-center"><strong>#{{ $booking->id }}</strong></td>
                            <td>
                                <strong>{{ $booking->renter->name }}</strong><br>
                                <small>{{ $booking->renter->email }}</small>
                            </td>
                            <td>
                                <strong>{{ $booking->motor->brand }}</strong><br>
                                <small>{{ $booking->motor->plate_number }}</small><br>
                                <small>{{ $booking->motor->owner->name ?? 'N/A' }}</small>
                            </td>
                            <td>{{ $booking->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                {{ $booking->start_date->format('d/m/Y') }}<br>
                                <small>s/d {{ $booking->end_date->format('d/m/Y') }}</small>
                            </td>
                            <td class="text-right">
                                <strong>Rp {{ number_format($booking->total_cost, 0, ',', '.') }}</strong>
                            </td>
                            <td class="text-center">
                                @if($booking->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                @elseif($booking->status === 'confirmed')
                                    <span class="badge badge-info">Confirmed</span>
                                @elseif($booking->status === 'active')
                                    <span class="badge badge-success">Active</span>
                                @elseif($booking->status === 'completed')
                                    <span class="badge badge-primary">Completed</span>
                                @elseif($booking->status === 'cancelled')
                                    <span class="badge badge-danger">Cancelled</span>
                                @else
                                    <span class="badge badge-secondary">{{ ucfirst($booking->status) }}</span>
                                @endif
                            </td>
                            <td class="text-center">{{ $booking->duration ?? 'N/A' }} hari</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr style="background-color: #e9ecef; font-weight: bold;">
                        <td colspan="5" class="text-right"><strong>TOTAL BOOKINGS:</strong></td>
                        <td class="text-right">
                            <strong>Rp {{ number_format($bookings->sum('total_cost'), 0, ',', '.') }}</strong>
                        </td>
                        <td class="text-center"><strong>{{ $bookings->count() }}</strong></td>
                        <td class="text-center">-</td>
                    </tr>
                </tfoot>
            </table>
        @else
            <div class="text-center" style="padding: 40px; color: #6c757d;">
                <p><strong>Tidak ada data booking untuk filter yang dipilih.</strong></p>
                <p>Silakan ubah filter atau periode pencarian.</p>
            </div>
        @endif
    </div>

    @if($bookings->count() > 0)
        <!-- Status Summary -->
        <div class="section">
            <h2>Ringkasan Status</h2>
            <div style="background-color: #f8f9fa; padding: 12px; border-radius: 6px; border: 1px solid #dee2e6;">
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                    <div style="flex: 1; margin: 5px;">
                        <strong>Pending:</strong> {{ $summary['pending_bookings'] }} booking
                        ({{ $summary['total_bookings'] > 0 ? round(($summary['pending_bookings'] / $summary['total_bookings']) * 100, 1) : 0 }}%)
                    </div>
                    <div style="flex: 1; margin: 5px;">
                        <strong>Confirmed:</strong> {{ $summary['confirmed_bookings'] }} booking
                        ({{ $summary['total_bookings'] > 0 ? round(($summary['confirmed_bookings'] / $summary['total_bookings']) * 100, 1) : 0 }}%)
                    </div>
                    <div style="flex: 1; margin: 5px;">
                        <strong>Active:</strong> {{ $summary['active_bookings'] }} booking
                        ({{ $summary['total_bookings'] > 0 ? round(($summary['active_bookings'] / $summary['total_bookings']) * 100, 1) : 0 }}%)
                    </div>
                </div>
                <div style="display: flex; justify-content: space-between; flex-wrap: wrap;">
                    <div style="flex: 1; margin: 5px;">
                        <strong>Completed:</strong> {{ $summary['completed_bookings'] }} booking
                        ({{ $summary['total_bookings'] > 0 ? round(($summary['completed_bookings'] / $summary['total_bookings']) * 100, 1) : 0 }}%)
                    </div>
                    <div style="flex: 1; margin: 5px;">
                        <strong>Cancelled:</strong> {{ $summary['cancelled_bookings'] }} booking
                        ({{ $summary['total_bookings'] > 0 ? round(($summary['cancelled_bookings'] / $summary['total_bookings']) * 100, 1) : 0 }}%)
                    </div>
                    <div style="flex: 1; margin: 5px;">
                        <!-- spacer -->
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>FannRental - Sistem Penyewaan Motor</strong></p>
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d F Y, H:i') }} WIB</p>
        <p style="margin-top: 8px; font-size: 8px; color: #adb5bd;">
            Dokumen ini bersifat internal dan hanya untuk keperluan administrasi
        </p>
    </div>
</body>
</html>