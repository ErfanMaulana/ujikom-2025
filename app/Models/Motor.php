<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'brand',
        'model',
        'type_cc',
        'year',
        'color',
        'plate_number',
        'status',
        'photo',
        'document',
        'description',
        'verified_at',
        'verified_by'
    ];

    protected $casts = [
        'verified_at' => 'datetime',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function rentalRate()
    {
        return $this->hasOne(RentalRate::class);
    }

    public function rentalRates()
    {
        return $this->hasOne(RentalRate::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isRented()
    {
        return $this->status === 'rented';
    }

    /**
     * Check if motor is currently rented based on active bookings (realtime)
     */
    public function isCurrentlyRented()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))
            ->exists();
    }

    /**
     * Get current booking status (realtime)
     */
    public function getCurrentStatus()
    {
        // If motor is not verified, return as pending verification
        if (!$this->isVerified()) {
            return 'pending_verification';
        }

        // Check if motor has active booking today
        if ($this->isCurrentlyRented()) {
            return 'rented';
        }

        // Check if motor is in maintenance
        if ($this->status === 'maintenance') {
            return 'maintenance';
        }

        // Otherwise, available
        return 'available';
    }

    /**
     * Get current booking if any
     */
    public function getCurrentBooking()
    {
        return $this->bookings()
            ->where('status', 'confirmed')
            ->where('start_date', '<=', now()->format('Y-m-d'))
            ->where('end_date', '>=', now()->format('Y-m-d'))
            ->with(['renter'])
            ->first();
    }

    public function isVerified()
    {
        return !is_null($this->verified_at);
    }

    public function getFormattedTypeAttribute()
    {
        return str_replace('cc', ' CC', $this->type_cc);
    }

    // Accessor for backward compatibility
    public function getCcAttribute()
    {
        return str_replace('cc', '', $this->type_cc);
    }

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->verified_at);
    }

    /**
     * Check if motor is available for booking on specific date range
     */
    public function isAvailableForDates($startDate, $endDate, $excludeBookingId = null)
    {
        // Motor must be available and verified
        if (!$this->isAvailable() || !$this->isVerified()) {
            return false;
        }

        // Check for overlapping bookings
        $conflictingBookingsQuery = $this->bookings()
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    // Case 1: New booking starts during existing booking
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Case 2: New booking ends during existing booking
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Case 3: New booking completely contains existing booking
                    $q->where('start_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    // Case 4: Existing booking completely contains new booking
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
                });
            })
            ->whereIn('status', ['confirmed', 'active', 'awaiting_payment_verification']);

        // Exclude specific booking if editing
        if ($excludeBookingId) {
            $conflictingBookingsQuery->where('id', '!=', $excludeBookingId);
        }

        return $conflictingBookingsQuery->count() === 0;
    }

    /**
     * Get conflicting bookings for specific date range
     */
    public function getConflictingBookings($startDate, $endDate, $excludeBookingId = null)
    {
        $query = $this->bookings()
            ->with(['renter'])
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $startDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $endDate)
                      ->where('end_date', '>=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '>=', $startDate)
                      ->where('end_date', '<=', $endDate);
                })->orWhere(function ($q) use ($startDate, $endDate) {
                    $q->where('start_date', '<=', $startDate)
                      ->where('end_date', '>=', $endDate);
                });
            })
            ->whereIn('status', ['confirmed', 'active', 'awaiting_payment_verification']);

        if ($excludeBookingId) {
            $query->where('id', '!=', $excludeBookingId);
        }

        return $query->get();
    }

    /**
     * Get next available date after given date
     */
    public function getNextAvailableDate($afterDate = null)
    {
        $date = $afterDate ? \Carbon\Carbon::parse($afterDate) : \Carbon\Carbon::today();
        
        $latestBooking = $this->bookings()
            ->whereIn('status', ['confirmed', 'active', 'awaiting_payment_verification'])
            ->where('end_date', '>=', $date)
            ->orderBy('end_date', 'desc')
            ->first();

        if ($latestBooking) {
            return \Carbon\Carbon::parse($latestBooking->end_date)->addDay();
        }

        return $date;
    }

    /**
     * Get average rating for this motor
     */
    public function getAverageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    /**
     * Get total number of ratings
     */
    public function getTotalRatings()
    {
        return $this->ratings()->count();
    }

    /**
     * Get formatted average rating
     */
    public function getFormattedRating()
    {
        $avg = $this->getAverageRating();
        return number_format($avg, 1);
    }

    /**
     * Get rating stars display
     */
    public function getRatingStars()
    {
        $avg = $this->getAverageRating();
        $fullStars = floor($avg);
        $halfStar = ($avg - $fullStars) >= 0.5 ? 1 : 0;
        $emptyStars = 5 - $fullStars - $halfStar;
        
        return str_repeat('★', $fullStars) . 
               str_repeat('☆', $halfStar) . 
               str_repeat('☆', $emptyStars);
    }
}
