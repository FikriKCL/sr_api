<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;
    protected $fillable = [
        'reservation_id',
        'payment_option_id',
        'amount',
        'status',
        'transaction_id',
        'paid_at',
    ];

    public function paymentOption()
    {
        return $this->belongsTo(PaymentOption::class);
    }

    protected $casts = [
        'paid_at' => 'datetime',
    ];

    public function reservation()
    {
        return $this->belongsTo(Reservation::class);
    }
}