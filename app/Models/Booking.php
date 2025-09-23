<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'renter_id',
        'motor_id',
        'package_type',
        'duration_days',
        'start_date',
        'end_date',
        'duration_type',
        'price',
        'status',
        'confirmed_at',
        'confirmed_by',
        'notes'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'confirmed_at' => 'datetime',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function renter()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'renter_id');
    }

    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    public function confirmedBy()
    {
        return $this->belongsTo(User::class, 'confirmed_by');
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function revenueSharing()
    {
        return $this->hasOne(RevenueSharing::class);
    }

    // Helper methods
    public function getDurationInDays()
    {
        return Carbon::parse($this->start_date)->diffInDays(Carbon::parse($this->end_date)) + 1;
    }

    public function isActive()
    {
        return $this->status === 'active' && 
               Carbon::now()->between(Carbon::parse($this->start_date), Carbon::parse($this->end_date));
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->status === 'confirmed';
    }

    public function isCompleted()
    {
        return $this->status === 'completed';
    }
}
