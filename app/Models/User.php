<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes, HasRoles, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'google_id', // Add google_id
        'role',      // Add role
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
    /**
     * Get the reservations for the user.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the doctors associated with this user (if this user is a doctor).
     */
    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }

    /**
     * Get the reservations approved by this user (if this user is staff).
     */
    public function approvedReservations()
    {
        return $this->hasMany(Reservation::class, 'approved_by');
    }

    /**
     * Get the reservations completed by this user (if this user is staff).
     */
    public function completedReservations()
    {
        return $this->hasMany(Reservation::class, 'completed_by');
    }

    public function canAccessPanel(Panel $panel): bool
    {
        // Allow access to the Filament panel if the user has 'admin', 'staff', or 'doctor' role.
        // You can customize these roles as needed.
        return $this->hasAnyRole(['admin', 'staff', 'doctor']);
    }
}
