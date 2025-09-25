<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penyewa - {{ $user->name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            color: #333;
        }
        .header p {
            margin: 5px 0;
            color: #666;
        }
        .summary {
            margin: 20px 0;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
        }
        .summary h3 {
            margin-top: 0;
            color: #333;
        }
        .summary-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .summary-item {
            background: white;
            padding: 10px;
            border-radius: 3px;
            border-left: 4px solid #007bff;
        }
        .summary-item strong {
            color: #333;
        }
        .section {
            margin: 30px 0;
        }
        .section h3 {
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        .table th, .table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .table th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            padding: 3px 8px;
            border-radius: 3px;
            color: white;
            font-size: 10px;
        }
        .status.completed { background-color: #28a745; }
        .status.active { background-color: #007bff; }
        .status.cancelled { background-color: #dc3545; }
        .status.pending { background-color: #ffc107; color: #333; }
        .footer {
            margin-top: 40px;
            text-align: center;
            color: #666;
            font-size: 10px;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AKTIVITAS PENYEWA</h1>
        <p><strong>Nama Penyewa:</strong> {{ $user->name }}</p>
        <p><strong>Email:</strong> {{ $user->email }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ \Carbon\Carbon::now()->format('d/m/Y H:i:s') }}</p>
    </div>

    <div class="summary">
        <h3>Ringkasan Aktivitas</h3>
        <div class="summary-grid">
            <div class="summary-item">
                <strong>Total Booking:</strong><br>
                {{ $summary['total_bookings'] }} transaksi
            </div>
            <div class="summary-item">
                <strong>Booking Selesai:</strong><br>
                {{ $summary['completed_bookings'] }} transaksi
            </div>
            <div class="summary-item">
                <strong>Total Pengeluaran:</strong><br>
                Rp {{ number_format($summary['total_spending'], 0, ',', '.') }}
            </div>
            <div class="summary-item">
                <strong>Rata-rata Rating:</strong><br>
                {{ number_format($summary['average_rating_given'], 1) }}/5
            </div>
        </div>
    </div>

    <div class="section">
        <h3>Riwayat Booking</h3>
        @if($bookings->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Motor</th>
                        <th>Pemilik</th>
                        <th>Status</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bookings as $index => $booking)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $booking->created_at->format('d/m/Y') }}</td>
                        <td>{{ $booking->motor ? $booking->motor->name : 'Motor tidak tersedia' }}</td>
                        <td>{{ $booking->motor && $booking->motor->owner ? $booking->motor->owner->name : 'N/A' }}</td>
                        <td>
                            <span class="status {{ $booking->status }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>Rp {{ number_format((float)($booking->price ?? 0), 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Belum ada riwayat booking</div>
        @endif
    </div>

    <div class="section">
        <h3>Riwayat Rating</h3>
        @if($ratings->count() > 0)
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Motor</th>
                        <th>Rating</th>
                        <th>Komentar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ratings as $index => $rating)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $rating->created_at->format('d/m/Y') }}</td>
                        <td>{{ $rating->motor ? $rating->motor->name : 'Motor tidak tersedia' }}</td>
                        <td>{{ $rating->rating }}/5</td>
                        <td>{{ $rating->comment ?: '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">Belum ada rating yang diberikan</div>
        @endif
    </div>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh sistem FannRental</p>
        <p>© {{ date('Y') }} FannRental - Sistem Rental Motor</p>
    </div>
</body>
</html>