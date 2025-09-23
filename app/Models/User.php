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
        'status',
        'verified_at',
        'verified_by',
        'blacklist_reason',
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
            'verified_at' => 'datetime',
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

    public function verifier()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    public function ratings()
    {
        return $this->hasMany(Rating::class);
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

    public function isVerified()
    {
        return $this->status === 'verified';
    }

    public function isBlacklisted()
    {
        return $this->status === 'blacklisted';
    }

    public function getStatusBadgeAttribute()
    {
        return match($this->status) {
            'verified' => '<span class="badge bg-success">Terverifikasi</span>',
            'blacklisted' => '<span class="badge bg-danger">Blacklist</span>',
            default => '<span class="badge bg-warning">Belum Verifikasi</span>',
        };
    }
}
