<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DoctorSchedule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'doctor_id',
        'day_of_week', // Changed from 'date'
        'start_time',
        'end_time',
        'is_available',
        'notes', // Added 'notes'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'day_of_week' => 'integer', // Cast day_of_week to integer
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

    /**
     * Get the day of the week name from its integer representation.
     */
    public function getDayNameAttribute(): string
    {
        return [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ][$this->day_of_week] ?? 'Unknown';
    }
}
