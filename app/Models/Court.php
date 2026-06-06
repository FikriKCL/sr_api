<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Court extends Model
{
    use HasFactory;

    protected $fillable = [
        'location_id',
        'court_name',
        'court_type',
        'price_per_hour',
        'status'
    ];

    public function location()
    {
        return $this->belongsTo(
            FranchiseLocation::class,
            'location_id'
        );
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function waitingLists()
    {
        return $this->hasMany(WaitingList::class);
    }
}