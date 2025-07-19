<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Doctor extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'specialty',
        'phone_number',
    ];

    /**
     * Get the user account associated with the doctor.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the schedules for the doctor.
     */
    public function schedules()
    {
        return $this->hasMany(DoctorSchedule::class);
    }

    /**
     * Get the reservations for the doctor.
     */
    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    /**
     * Get the notes written by the doctor.
     */
    public function doctorNotes()
    {
        return $this->hasMany(DoctorNote::class);
    }
}

