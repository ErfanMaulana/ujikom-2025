<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;
use App\Models\Booking;
use Carbon\Carbon;

class UpdateMotorStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:update-status {--dry-run : Hanya menampilkan perubahan tanpa menyimpan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update status motor berdasarkan booking yang aktif hari ini';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $today = Carbon::today();
        $updatedCount = 0;

        $this->info("🔄 Memperbarui status motor untuk tanggal: " . $today->format('d M Y'));
        
        if ($isDryRun) {
            $this->warn("⚠️  Mode DRY RUN - Tidak ada perubahan yang akan disimpan");
        }

        // 1. Update motor yang harus berstatus 'rented' (ada booking aktif hari ini)
        $motorsToRent = Motor::where('status', '!=', 'rented')
            ->whereHas('bookings', function($query) use ($today) {
                $query->where('status', 'active')
                      ->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            })
            ->with(['bookings' => function($query) use ($today) {
                $query->where('status', 'active')
                      ->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            }])
            ->get();

        foreach ($motorsToRent as $motor) {
            $booking = $motor->bookings->first();
            $this->line("📍 Motor {$motor->brand} {$motor->type_cc} ({$motor->plate_number}) → RENTED");
            $this->line("   Booking: {$booking->renter->name} ({$booking->start_date->format('d M')} - {$booking->end_date->format('d M')})");
            
            if (!$isDryRun) {
                $motor->update(['status' => 'rented']);
            }
            $updatedCount++;
        }

        // 2. Update motor yang harus kembali ke 'available' (tidak ada booking aktif hari ini)
        $motorsToAvailable = Motor::where('status', 'rented')
            ->whereDoesntHave('bookings', function($query) use ($today) {
                $query->where('status', 'active')
                      ->where('start_date', '<=', $today)
                      ->where('end_date', '>=', $today);
            })
            ->where('verified_at', '!=', null) // Pastikan sudah terverifikasi
            ->get();

        foreach ($motorsToAvailable as $motor) {
            $this->line("📍 Motor {$motor->brand} {$motor->type_cc} ({$motor->plate_number}) → AVAILABLE");
            
            if (!$isDryRun) {
                $motor->update(['status' => 'available']);
            }
            $updatedCount++;
        }

        // 3. Update booking status menjadi 'active' jika tanggal mulai adalah hari ini
        $bookingsToActive = Booking::where('status', 'confirmed')
            ->where('start_date', $today)
            ->with(['motor', 'renter'])
            ->get();

        foreach ($bookingsToActive as $booking) {
            $this->line("🚀 Booking #{$booking->id} → ACTIVE (Penyewaan dimulai hari ini)");
            $this->line("   {$booking->renter->name} - {$booking->motor->brand} {$booking->motor->type_cc}");
            
            if (!$isDryRun) {
                $booking->update(['status' => 'active']);
            }
        }

        // 4. Update booking status menjadi 'completed' jika tanggal selesai adalah kemarin
        $yesterday = Carbon::yesterday();
        $bookingsToComplete = Booking::where('status', 'active')
            ->where('end_date', $yesterday)
            ->with(['motor', 'renter'])
            ->get();

        foreach ($bookingsToComplete as $booking) {
            $this->line("✅ Booking #{$booking->id} → COMPLETED (Penyewaan selesai kemarin)");
            $this->line("   {$booking->renter->name} - {$booking->motor->brand} {$booking->motor->type_cc}");
            
            if (!$isDryRun) {
                $booking->update(['status' => 'completed']);
            }
        }

        if ($isDryRun) {
            $this->info("📊 Total perubahan yang akan dilakukan: {$updatedCount} motor");
            $this->info("🔄 Jalankan tanpa --dry-run untuk menerapkan perubahan");
        } else {
            $this->info("✅ Selesai! {$updatedCount} motor berhasil diperbarui");
        }

        return 0;
    }
}
