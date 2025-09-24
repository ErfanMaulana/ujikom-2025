<?php

namespace App\Observers;

use App\Models\Booking;
use App\Models\Motor;
use Carbon\Carbon;

class BookingObserver
{
    /**
     * Handle the Booking "created" event.
     */
    public function created(Booking $booking): void
    {
        // Tidak perlu update status motor saat booking dibuat (masih pending)
    }

    /**
     * Handle the Booking "updated" event.
     */
    public function updated(Booking $booking): void
    {
        // Jika status booking berubah, update status motor
        if ($booking->wasChanged('status')) {
            $this->updateMotorStatus($booking);
        }
    }

    /**
     * Handle the Booking "deleted" event.
     */
    public function deleted(Booking $booking): void
    {
        // Jika booking dihapus, pastikan motor kembali available (jika tidak ada booking lain)
        $this->updateMotorStatus($booking, true);
    }

    /**
     * Handle the Booking "restored" event.
     */
    public function restored(Booking $booking): void
    {
        // Update status motor jika booking direstore
        $this->updateMotorStatus($booking);
    }

    /**
     * Handle the Booking "force deleted" event.
     */
    public function forceDeleted(Booking $booking): void
    {
        // Pastikan motor kembali available jika booking dihapus permanen
        $this->updateMotorStatus($booking, true);
    }

    /**
     * Update motor status berdasarkan booking
     */
    private function updateMotorStatus(Booking $booking, bool $isDeleted = false)
    {
        $motor = $booking->motor;
        $today = Carbon::today();
        $todayString = $today->format('Y-m-d');

        // Jika booking dihapus atau dibatalkan
        if ($isDeleted || in_array($booking->status, ['cancelled'])) {
            // Cek apakah masih ada booking aktif lain untuk motor ini
            $hasActiveBooking = $motor->bookings()
                ->where('id', '!=', $booking->id)
                ->where('status', 'confirmed')
                ->where('start_date', '<=', $todayString)
                ->where('end_date', '>=', $todayString)
                ->exists();

            if (!$hasActiveBooking && $motor->status === 'rented' && $motor->isVerified()) {
                $motor->update(['status' => 'available']);
            }
            return;
        }

        // Jika booking dikonfirmasi dan tanggal mulai adalah hari ini atau sudah lewat
        if ($booking->status === 'confirmed' && 
            Carbon::parse($booking->start_date)->lte($today) && 
            Carbon::parse($booking->end_date)->gte($today)) {
            
            if ($motor->status !== 'rented') {
                $motor->update(['status' => 'rented']);
            }
            
            // Update booking status menjadi active jika start_date adalah hari ini
            if (Carbon::parse($booking->start_date)->equalTo($today) && $booking->status === 'confirmed') {
                $booking->update(['status' => 'active']);
            }
        }

        // Jika booking selesai, cek apakah motor harus kembali available
        if ($booking->status === 'completed') {
            // Cek apakah masih ada booking aktif lain
            $hasActiveBooking = $motor->bookings()
                ->where('id', '!=', $booking->id)
                ->where('status', 'confirmed')
                ->where('start_date', '<=', $todayString)
                ->where('end_date', '>=', $todayString)
                ->exists();

            if (!$hasActiveBooking && $motor->status === 'rented' && $motor->isVerified()) {
                $motor->update(['status' => 'available']);
            }
        }
    }
}
