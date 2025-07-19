<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PaymentGatewayConfig extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'gateway_name',
        'mode',
        'config_key',
        'config_value',
        'is_encrypted',
    ];

    // You might want to add accessors/mutators for automatic encryption/decryption
    // For example:
    // public function getConfigValueAttribute($value)
    // {
    //     return $this->is_encrypted ? Crypt::decryptString($value) : $value;
    // }
    //
    // public function setConfigValueAttribute($value)
    // {
    //     $this->attributes['config_value'] = $this->is_encrypted ? Crypt::encryptString($value) : $value;
    // }
}
