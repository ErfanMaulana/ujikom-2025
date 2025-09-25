# Panduan Export Laporan Keuangan ke PDF

## Cara Menggunakan Fitur Export PDF

### 1. Akses Halaman Laporan Keuangan
- Login sebagai admin
- Navigasi ke menu **Laporan** → **Laporan Keuangan**
- URL: `/admin/financial-report`

### 2. Filter Data (Opsional)
Sebelum export, Anda dapat memfilter data berdasarkan periode:
- **Dari Tanggal**: Pilih tanggal mulai periode laporan
- **Sampai Tanggal**: Pilih tanggal akhir periode laporan
- Klik tombol **Filter Laporan** untuk menerapkan filter
- Gunakan **Reset Filter** untuk menghapus filter dan melihat semua data

### 3. Export ke PDF
- Setelah data ditampilkan sesuai filter yang diinginkan
- Klik tombol **Export PDF** (tombol merah dengan ikon PDF)
- File PDF akan langsung diunduh ke komputer Anda
- Nama file otomatis: `Laporan_Keuangan_YYYY-MM-DD_HH-mm-ss.pdf`

### 4. Isi Laporan PDF
Laporan PDF akan berisi:

#### Halaman 1:
- **Header**: Judul laporan, periode data, tanggal cetak
- **Ringkasan Keuangan**: Total pendapatan, bagian pemilik, komisi admin, jumlah transaksi
- **Daftar Transaksi**: Detail semua transaksi dalam periode yang dipilih

#### Halaman 2:
- **Ringkasan per Pemilik**: Statistik pendapatan per pemilik motor
- **Motor Terpopuler**: Top 5 motor dengan pendapatan tertinggi
- **Footer**: Informasi dokumen dan copyright

### 5. Kualitas dan Format PDF
- Format: A4 Portrait
- Font: DejaVu Sans (mendukung karakter Indonesia)
- Resolusi: Print quality
- Ukuran file: Dioptimalkan untuk sharing dan archiving

### 6. Tips Penggunaan
- **Filter Periode**: Gunakan filter untuk laporan bulanan/tahunan yang spesifik
- **Data Real-time**: Export akan menggunakan data terbaru saat tombol diklik
- **Browser Compatibility**: Didukung semua browser modern
- **Mobile Friendly**: Dapat diakses dari perangkat mobile

### 7. Troubleshooting
Jika mengalami masalah:
- Pastikan browser mengizinkan download otomatis
- Periksa popup blocker tidak memblokir download
- Refresh halaman jika export tidak response
- Hubungi admin sistem jika masalah berlanjut

### 8. Keamanan
- Hanya admin yang dapat mengakses fitur export
- Data sensitif dilindungi dengan autentikasi
- PDF tidak berisi data yang dapat diedit

---
**Catatan**: Fitur ini dibuat untuk memudahkan pembuatan laporan berkala dan dokumentasi keuangan sistem penyewaan motor.