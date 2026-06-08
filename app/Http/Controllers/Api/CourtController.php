<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;

class CourtController extends Controller
{
    public function index()
    {
        $courts = Court::with('location')
            ->where('status', 'active')
            ->get();

        return response()->json([
            'data' => $courts
        ]);
    }

    public function availableSlots(Court $court, Request $request)
    {
        $date = $request->query('date');

        if (!$date) {
            return response()->json([
                'message' => 'Date is required'
            ], 422);
        }

        $court->load('location');

        $openHour = strtotime($court->location->open_hour);
        $closeHour = strtotime($court->location->close_hour);

        $bookings = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $slots = [];

        for ($time = $openHour; $time + 3600 <= $closeHour; $time += 3600) {
            $start = date('H:i:s', $time);
            $end = date('H:i:s', $time + 3600);

            $isBooked = $bookings->contains(function ($booking) use ($start, $end) {
                return $booking->start_time < $end &&
                       $booking->end_time > $start;
            });

            $slots[] = [
                'start_time' => $start,
                'end_time' => $end,
                'available' => !$isBooked,
            ];
        }

        return response()->json([
            'data' => $slots
        ]);
    }
}