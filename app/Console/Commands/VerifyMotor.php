<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;
use App\Models\RentalRate;

class VerifyMotor extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:verify {--id= : ID motor yang akan diverifikasi} {--all : Verifikasi semua motor pending} {--daily_rate= : Tarif harian} {--weekly_rate= : Tarif mingguan} {--monthly_rate= : Tarif bulanan}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Verifikasi motor untuk memungkinkan penyewaan';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if ($this->option('all')) {
            return $this->verifyAllPendingMotors();
        }

        if ($this->option('id')) {
            return $this->verifyMotorById($this->option('id'));
        }

        // Jika tidak ada option, tampilkan menu interaktif
        return $this->interactiveVerification();
    }

    private function verifyAllPendingMotors()
    {
        $pendingMotors = Motor::where('status', 'pending_verification')->get();

        if ($pendingMotors->isEmpty()) {
            $this->info('Tidak ada motor yang menunggu verifikasi.');
            return Command::SUCCESS;
        }

        $this->info("Ditemukan {$pendingMotors->count()} motor yang menunggu verifikasi:");

        foreach ($pendingMotors as $motor) {
            $this->line("- ID: {$motor->id} | {$motor->brand} {$motor->type_cc} | Pemilik: {$motor->owner->name}");
        }

        if ($this->confirm('Verifikasi semua motor ini?')) {
            foreach ($pendingMotors as $motor) {
                $motor->update(['status' => 'available']);
                $this->info("✓ Motor {$motor->brand} {$motor->type_cc} (ID: {$motor->id}) berhasil diverifikasi");
            }

            $this->info("\n🎉 Semua motor berhasil diverifikasi dan siap disewa!");
        } else {
            $this->info('Verifikasi dibatalkan.');
        }

        return Command::SUCCESS;
    }

    private function verifyMotorById($id)
    {
        $motor = Motor::find($id);

        if (!$motor) {
            $this->error("Motor dengan ID {$id} tidak ditemukan.");
            return Command::FAILURE;
        }

        if ($motor->status === 'available') {
            $this->info("Motor {$motor->brand} {$motor->type_cc} sudah terverifikasi.");
            return Command::SUCCESS;
        }

        $this->info("Motor ditemukan:");
        $this->table(['Field', 'Value'], [
            ['ID', $motor->id],
            ['Brand', $motor->brand],
            ['Type CC', $motor->type_cc],
            ['Pemilik', $motor->owner->name],
            ['Status', $motor->status],
            ['Plate Number', $motor->plate_number ?? 'N/A'],
        ]);

        // Check if pricing parameters are provided
        $dailyRate = $this->option('daily_rate');
        $weeklyRate = $this->option('weekly_rate');
        $monthlyRate = $this->option('monthly_rate');

        if ($dailyRate) {
            // Verify with pricing
            if ($this->confirm('Verifikasi motor ini dengan harga yang ditentukan?')) {
        // Auto-calculate weekly and monthly if not provided
        if (!$weeklyRate) {
            $weeklyRate = $dailyRate * 7 * 0.9; // 10% discount for weekly
        }
        if (!$monthlyRate) {
            $monthlyRate = $dailyRate * 30 * 0.8; // 20% discount for monthly
        }                // Update motor status
                $motor->update([
                    'status' => 'available',
                    'verified_at' => now()
                ]);

                // Create or update rental rate
                RentalRate::updateOrCreate(
                    ['motor_id' => $motor->id],
                    [
                        'daily_rate' => $dailyRate,
                        'weekly_rate' => $weeklyRate,
                        'monthly_rate' => $monthlyRate
                    ]
                );

                $this->info("✓ Motor {$motor->brand} {$motor->type_cc} berhasil diverifikasi!");
                $this->table(['Tarif', 'Harga'], [
                    ['Harian', 'Rp ' . number_format($dailyRate, 0, ',', '.')],
                    ['Mingguan', 'Rp ' . number_format($weeklyRate, 0, ',', '.')],
                    ['Bulanan', 'Rp ' . number_format($monthlyRate, 0, ',', '.')]
                ]);
            } else {
                $this->info('Verifikasi dibatalkan.');
            }
        } else {
            // Interactive pricing input
            if ($this->confirm('Verifikasi motor ini?')) {
                $dailyRate = $this->ask('Masukkan tarif harian (Rp)', 75000);
                $weeklyRate = $this->ask('Masukkan tarif mingguan (Rp)', $dailyRate * 7 * 0.9);
                $monthlyRate = $this->ask('Masukkan tarif bulanan (Rp)', $dailyRate * 30 * 0.8);

                // Update motor status
                $motor->update([
                    'status' => 'available',
                    'verified_at' => now()
                ]);

                // Create or update rental rate
                RentalRate::updateOrCreate(
                    ['motor_id' => $motor->id],
                    [
                        'daily_rate' => $dailyRate,
                        'weekly_rate' => $weeklyRate,
                        'monthly_rate' => $monthlyRate
                    ]
                );

                $this->info("✓ Motor {$motor->brand} {$motor->type_cc} berhasil diverifikasi dengan harga:");
                $this->table(['Tarif', 'Harga'], [
                    ['Harian', 'Rp ' . number_format($dailyRate, 0, ',', '.')],
                    ['Mingguan', 'Rp ' . number_format($weeklyRate, 0, ',', '.')],
                    ['Bulanan', 'Rp ' . number_format($monthlyRate, 0, ',', '.')]
                ]);
            } else {
                $this->info('Verifikasi dibatalkan.');
            }
        }

        return Command::SUCCESS;
    }

    private function interactiveVerification()
    {
        $pendingMotors = Motor::where('status', 'pending_verification')->get();

        if ($pendingMotors->isEmpty()) {
            $this->info('Tidak ada motor yang menunggu verifikasi.');
            return Command::SUCCESS;
        }

        $this->info("Motor yang menunggu verifikasi:");
        $choices = [];
        foreach ($pendingMotors as $motor) {
            $choices[] = "ID: {$motor->id} - {$motor->brand} {$motor->type_cc} (Pemilik: {$motor->owner->name})";
        }
        $choices[] = 'Verifikasi semua';
        $choices[] = 'Batal';

        $selected = $this->choice('Pilih motor yang akan diverifikasi:', $choices);

        if ($selected === 'Batal') {
            $this->info('Verifikasi dibatalkan.');
            return Command::SUCCESS;
        }

        if ($selected === 'Verifikasi semua') {
            return $this->verifyAllPendingMotors();
        }

        // Extract ID dari pilihan
        preg_match('/ID: (\d+)/', $selected, $matches);
        if (isset($matches[1])) {
            return $this->verifyMotorById($matches[1]);
        }

        $this->error('Terjadi kesalahan dalam memproses pilihan.');
        return Command::FAILURE;
    }
}
