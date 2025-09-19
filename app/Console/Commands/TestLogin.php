<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class TestLogin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:login';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test login functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing dashboard views...');
        
        try {
            // Test if views exist
            $views = [
                'pemilik.dashboard',
                'penyewa.dashboard', 
                'admin.dashboard'
            ];
            
            foreach ($views as $view) {
                if (view()->exists($view)) {
                    $this->info("✓ View {$view} exists");
                } else {
                    $this->error("✗ View {$view} not found");
                }
            }
            
            // Test controller methods
            $pemilik = User::where('role', 'pemilik')->first();
            if ($pemilik) {
                $this->info('Testing PemilikController with user: ' . $pemilik->email);
                
                // Simulate auth user
                auth()->login($pemilik);
                
                try {
                    $controller = new \App\Http\Controllers\PemilikController();
                    $response = $controller->dashboard();
                    $this->info('✓ PemilikController@dashboard works');
                } catch (\Exception $e) {
                    $this->error('✗ PemilikController@dashboard failed: ' . $e->getMessage());
                }
            }
            
        } catch (\Exception $e) {
            $this->error('Test failed: ' . $e->getMessage());
        }
    }
}
