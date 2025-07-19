<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'reservation_id',
        'gateway_transaction_id',
        'order_id',
        'amount',
        'currency',
        'payment_gateway',
        'payment_method',
        'transaction_status',
        'transaction_time',
        'raw_response',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'transaction_time' => 'datetime',
        // 'raw_response' => 'array', // You might cast this to array if you always store valid JSON and want it auto-parsed
    ];

    /**
     * Get the reservation that the payment belongs to.
     */
    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}
