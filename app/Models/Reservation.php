<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Reservation extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'doctor_id',
        'service_id',
        'schedule_id',
        'scheduled_date',
        'scheduled_time',
        'status',
        'payment_status',
        'xendit_invoice_id',
        'payment_amount',
        'approved_by',
        'completed_by',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'scheduled_date' => 'date',
        'payment_amount' => 'decimal:2',
    ];

    /**
     * Get the user who made the reservation.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the doctor for the reservation.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Get the service chosen for the reservation.
     */
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    /**
     * Get the doctor schedule associated with the reservation.
     */
    public function schedule()
    {
        return $this->belongsTo(DoctorSchedule::class, 'schedule_id');
    }

    /**
     * Get the staff user who approved the reservation.
     */
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the staff user who completed the reservation.
     */
    public function completer()
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    /**
     * Get the recipes for the reservation.
     */
    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    /**
     * Get the doctor notes for the reservation.
     */
    public function doctorNotes()
    {
        return $this->hasMany(DoctorNote::class);
    }
}

