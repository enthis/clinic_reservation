<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class Recipe extends Model
{
    use HasFactory, SoftDeletes; // Use SoftDeletes trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'prescription_item_id',
        'dose',
        'notes',
    ];

    /**
     * Get the reservation that the recipe belongs to.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }

    /**
     * Get the prescription item associated with the recipe.
     */
    public function prescriptionItem()
    {
        return $this->belongsTo(PrescriptionItem::class);
    }
}

