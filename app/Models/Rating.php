<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'motor_id', 
        'booking_id',
        'rating',
        'review',
        'rating_type',
        'is_verified'
    ];

    protected $casts = [
        'rating' => 'integer',
        'is_verified' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the user that made the rating
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the motor that was rated
     */
    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    /**
     * Get the booking associated with this rating
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    /**
     * Scope for verified ratings only
     */
    public function scopeVerified($query)
    {
        return $query->where('is_verified', true);
    }

    /**
     * Scope for specific rating type
     */
    public function scopeByType($query, $type)
    {
        return $query->where('rating_type', $type);
    }

    /**
     * Get rating as stars display
     */
    public function getStarsAttribute()
    {
        return str_repeat('★', $this->rating) . str_repeat('☆', 5 - $this->rating);
    }

    /**
     * Get formatted rating display
     */
    public function getFormattedRatingAttribute()
    {
        return $this->rating . '/5 stars';
    }

    /**
     * Check if rating can be edited (within 24 hours)
     */
    public function canBeEdited()
    {
        return $this->created_at->diffInHours(now()) <= 24;
    }
}
