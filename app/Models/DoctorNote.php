<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class DoctorNote extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'doctor_id',
        'note_content',
    ];

    /**
     * Get the reservation that the note belongs to.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the doctor who wrote the note.
     */
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }
}
