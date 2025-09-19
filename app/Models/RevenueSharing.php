<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RevenueSharing extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'total_amount',
        'owner_share',
        'admin_share',
        'owner_percentage',
        'admin_percentage',
        'settled_at',
        'status'
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'owner_share' => 'decimal:2',
        'admin_share' => 'decimal:2',
        'owner_percentage' => 'decimal:2',
        'admin_percentage' => 'decimal:2',
        'settled_at' => 'datetime',
    ];

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper methods
    public function isSettled()
    {
        return $this->status === 'settled';
    }

    public function isPending()
    {
        return $this->status === 'pending';
    }

    public static function calculateShares($totalAmount, $ownerPercentage = 70.00)
    {
        $adminPercentage = 100.00 - $ownerPercentage;
        $ownerShare = ($totalAmount * $ownerPercentage) / 100;
        $adminShare = ($totalAmount * $adminPercentage) / 100;

        return [
            'owner_share' => round($ownerShare, 2),
            'admin_share' => round($adminShare, 2),
            'owner_percentage' => $ownerPercentage,
            'admin_percentage' => $adminPercentage,
        ];
    }
}
