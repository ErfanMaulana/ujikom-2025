<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;
use App\Models\RentalRate;
use App\Models\Booking;
use App\Models\Payment;
use Illuminate\Support\Facades\Storage;

class CleanupMotorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:cleanup {--force : Force delete without confirmation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleanup all motor data (motors, rental rates, bookings, payments)';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧹 Motor Data Cleanup Tool');
        $this->line('This will delete ALL motor-related data:');
        $this->line('- All motors');
        $this->line('- All rental rates');
        $this->line('- All bookings');
        $this->line('- All payments');
        $this->line('- Motor photos from storage');
        $this->newLine();

        // Show current data count
        $motorCount = Motor::count();
        $rentalRateCount = RentalRate::count();
        $bookingCount = Booking::count();
        $paymentCount = Payment::count();

        $this->table(['Data Type', 'Current Count'], [
            ['Motors', $motorCount],
            ['Rental Rates', $rentalRateCount],
            ['Bookings', $bookingCount],
            ['Payments', $paymentCount],
        ]);

        if ($motorCount == 0 && $rentalRateCount == 0 && $bookingCount == 0 && $paymentCount == 0) {
            $this->info('✅ No data to cleanup. Database is already clean.');
            return Command::SUCCESS;
        }

        // Confirm deletion
        if (!$this->option('force')) {
            if (!$this->confirm('⚠️  Are you sure you want to delete ALL motor data? This action cannot be undone!')) {
                $this->info('❌ Cleanup cancelled.');
                return Command::SUCCESS;
            }
        }

        $this->info('🚀 Starting cleanup process...');

        // Delete payments first (foreign key dependency)
        if ($paymentCount > 0) {
            Payment::truncate();
            $this->info("✅ Deleted {$paymentCount} payments");
        }

        // Delete bookings
        if ($bookingCount > 0) {
            Booking::truncate();
            $this->info("✅ Deleted {$bookingCount} bookings");
        }

        // Delete rental rates
        if ($rentalRateCount > 0) {
            RentalRate::truncate();
            $this->info("✅ Deleted {$rentalRateCount} rental rates");
        }

        // Delete motor photos from storage
        $photosDeleted = 0;
        Motor::whereNotNull('photo')->chunk(100, function ($motors) use (&$photosDeleted) {
            foreach ($motors as $motor) {
                if ($motor->photo && Storage::disk('public')->exists($motor->photo)) {
                    Storage::disk('public')->delete($motor->photo);
                    $photosDeleted++;
                }
            }
        });

        if ($photosDeleted > 0) {
            $this->info("✅ Deleted {$photosDeleted} motor photos from storage");
        }

        // Delete motors
        if ($motorCount > 0) {
            Motor::query()->delete();
            $this->info("✅ Deleted {$motorCount} motors");
        }

        // Clean up empty storage directories
        $this->cleanupStorageDirectories();

        $this->newLine();
        $this->info('🎉 Cleanup completed successfully!');
        $this->info('📊 All motor data has been removed from the database.');

        return Command::SUCCESS;
    }

    private function cleanupStorageDirectories()
    {
        $directories = ['motors'];
        
        foreach ($directories as $dir) {
            $path = "public/{$dir}";
            if (Storage::exists($path)) {
                $files = Storage::files($path);
                if (empty($files)) {
                    // Directory is empty, we can leave it or clean it
                    $this->info("📁 Storage directory '{$dir}' is now empty");
                }
            }
        }
    }
}
