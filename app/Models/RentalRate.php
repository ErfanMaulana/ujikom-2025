<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RentalRate extends Model
{
    use HasFactory;

    protected $fillable = [
        'motor_id',
        'daily_rate',
        'weekly_rate',
        'monthly_rate'
    ];

    protected $casts = [
        'daily_rate' => 'decimal:2',
        'weekly_rate' => 'decimal:2',
        'monthly_rate' => 'decimal:2',
    ];

    // Relationships
    public function motor()
    {
        return $this->belongsTo(Motor::class);
    }

    // Helper methods
    public function getRateByType($durationType)
    {
        switch ($durationType) {
            case 'daily':
                return $this->daily_rate;
            case 'weekly':
                return $this->weekly_rate;
            case 'monthly':
                return $this->monthly_rate;
            default:
                return $this->daily_rate;
        }
    }
}
