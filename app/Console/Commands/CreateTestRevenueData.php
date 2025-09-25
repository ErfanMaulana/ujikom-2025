<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Motor;
use App\Models\Booking;
use App\Models\RevenueSharing;

class CreateTestRevenueData extends Command
{
    protected $signature = 'test:create-revenue';
    protected $description = 'Create test revenue data for Eka';

    public function handle()
    {
        $eka = User::where('email', 'eka@gmail.com')->first();
        
        if (!$eka) {
            $this->error('User Eka not found');
            return;
        }

        $this->info("Found Eka (ID: {$eka->id})");

        $motors = Motor::where('owner_id', $eka->id)->get();
        $this->info("Motors owned: {$motors->count()}");

        if ($motors->isEmpty()) {
            $this->error('No motors found for Eka');
            return;
        }

        $motor = $motors->first();
        $this->info("Using motor: {$motor->brand} ({$motor->plate_number})");

        $bookings = Booking::where('motor_id', $motor->id)->get();
        $this->info("Bookings found: {$bookings->count()}");

        if ($bookings->isEmpty()) {
            $this->error('No bookings found for this motor');
            return;
        }

        foreach ($bookings as $booking) {
            $existing = RevenueSharing::where('booking_id', $booking->id)->first();
            
            if (!$existing) {
                RevenueSharing::create([
                    'booking_id' => $booking->id,
                    'owner_id' => $eka->id,
                    'total_amount' => 150000,
                    'owner_amount' => 105000,
                    'admin_commission' => 45000,
                    'owner_percentage' => 70.00,
                    'admin_percentage' => 30.00,
                    'status' => 'paid',
                    'settled_at' => now()
                ]);
                
                $this->info("Created revenue sharing for booking {$booking->id}");
            } else {
                $this->info("Revenue sharing already exists for booking {$booking->id}");
            }
        }

        $totalRevenues = RevenueSharing::whereHas('booking.motor', function($q) use ($eka) {
            $q->where('owner_id', $eka->id);
        })->count();

        $this->info("Total revenue records for Eka: {$totalRevenues}");
        $this->info('Done!');
    }
}