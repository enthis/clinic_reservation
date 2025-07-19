<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class DoctorSchedule extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'date',
        'start_time',
        'end_time',
        'is_available',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
    ];

    /**
     * Get the doctor that owns the schedule.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the reservation that uses this schedule.
     */
    public function reservation()
    {
        return $this->hasOne(Reservation::class, 'schedule_id');
    }
}

