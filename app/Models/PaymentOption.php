<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentOption extends Model
{
    use HasFactory;
    protected $fillable = [
        'label',
        'icon',
        'status',
    ];

    public function paymentOption()
        {
            return $this->belongsTo(
                PaymentOption::class
            );
        }
}

