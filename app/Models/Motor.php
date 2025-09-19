<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Motor extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'brand',
        'cc',
        'type_cc',
        'plate_number',
        'status',
        'photo',
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

    // Helper methods
    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isRented()
    {
        return $this->status === 'rented';
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

    public function getModelAttribute()
    {
        return $this->type_cc; // Using type_cc as model for now
    }

    public function getYearAttribute()
    {
        return '2023'; // Default year for now
    }

    public function getIsVerifiedAttribute()
    {
        return !is_null($this->verified_at);
    }
}
