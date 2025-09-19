<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Relationships
    public function ownedMotors()
    {
        return $this->hasMany(Motor::class, 'owner_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'renter_id');
    }

    public function verifiedMotors()
    {
        return $this->hasMany(Motor::class, 'verified_by');
    }

    public function confirmedBookings()
    {
        return $this->hasMany(Booking::class, 'confirmed_by');
    }

    // Helper methods
    public function isPenyewa()
    {
        return $this->role === 'penyewa';
    }

    public function isPemilik()
    {
        return $this->role === 'pemilik';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }
}
