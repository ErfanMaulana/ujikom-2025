<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevenueSharing extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'owner_id',
        'total_amount',
        'owner_amount',
        'admin_commission',
        'owner_percentage',
        'admin_percentage',
        'settled_at',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'owner_amount' => 'decimal:2',
        'admin_commission' => 'decimal:2',
        'owner_percentage' => 'decimal:2',
        'admin_percentage' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    // Helper methods
    public function isSettled()
    {
        return $this->status === 'paid';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public static function calculateShares($totalAmount, $ownerPercentage = 70.00)
    {
        $adminPercentage = 100.00 - $ownerPercentage;
        $ownerAmount = ($totalAmount * $ownerPercentage) / 100;
        $adminCommission = ($totalAmount * $adminPercentage) / 100;

        return [
            'owner_amount' => round($ownerAmount, 2),
            'admin_commission' => round($adminCommission, 2),
            'owner_percentage' => $ownerPercentage,
            'admin_percentage' => $adminPercentage,
        ];
    }
}
