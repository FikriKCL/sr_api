<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index(string $id){
        $courtlist = Court::with('court_name','price_per_hour','status')->get()->toJson();

        return $courtlist;
    }

    public function show(){
        return $court->load('location');
    }

    public function availableSlots(Court $court, Request $request)
    {
        $date = $request->date;

        $bookings = Reservation::where(
            'court_id',
            $court->id
        )
        ->where(
            'reservation_date',
            $date
        )
        ->whereIn('status', [
            'pending',
            'approved'
        ])
        ->get();

        return response()->json([
            'court' => $court,
            'bookings' => $bookings
        ]);
    }
}
