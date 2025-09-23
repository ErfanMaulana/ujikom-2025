<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;
use App\Models\User;

class TestMotorStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:test-setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Setup motor test untuk verifikasi';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Ubah motor ID 1 kembali ke pending_verification
        Motor::where('id', 1)->update(['status' => 'pending_verification']);
        $this->info('Motor ID 1 diubah ke status pending_verification');

        // Cek status semua motor
        $motors = Motor::all(['id', 'brand', 'type_cc', 'status']);
        $this->table(['ID', 'Brand', 'Type CC', 'Status'], $motors->toArray());

        return Command::SUCCESS;
    }
}
