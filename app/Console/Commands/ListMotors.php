<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Motor;

class ListMotors extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'motor:list {--status= : Filter by status}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List semua motor beserta statusnya';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $query = Motor::with('owner');
        
        if ($this->option('status')) {
            $query->where('status', $this->option('status'));
        }
        
        $motors = $query->get();

        if ($motors->isEmpty()) {
            $this->info('Tidak ada motor ditemukan.');
            return Command::SUCCESS;
        }

        $headers = ['ID', 'Brand', 'Model', 'CC', 'Year', 'Color', 'Pemilik', 'Status', 'Plate Number'];
        $rows = [];

        foreach ($motors as $motor) {
            $rows[] = [
                $motor->id,
                $motor->brand,
                $motor->model ?? '-',
                $motor->type_cc,
                $motor->year ?? '-',
                $motor->color ?? '-',
                $motor->owner->name ?? 'N/A',
                $motor->status,
                $motor->plate_number ?? 'N/A'
            ];
        }

        $this->table($headers, $rows);
        $this->info("\nTotal: {$motors->count()} motor");

        return Command::SUCCESS;
    }
}
