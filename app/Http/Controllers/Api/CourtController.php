<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\FranchiseLocation;

class CourtController extends Controller
{
    public function index(Request $request)
{
    $user = $request->user();

    if ($user &&
        $user->latitude &&
        $user->longitude) {

        $courts = Court::with('location')
            ->selectRaw("
                courts.*,
                (
                    6371 * acos(
                        cos(radians(?))
                        * cos(radians(franchise_locations.latitude))
                        * cos(
                            radians(franchise_locations.longitude)
                            - radians(?)
                        )
                        + sin(radians(?))
                        * sin(radians(franchise_locations.latitude))
                    )
                ) AS distance
            ", [
                $user->latitude,
                $user->longitude,
                $user->latitude
            ])
            ->join(
                'franchise_locations',
                'courts.location_id',
                '=',
                'franchise_locations.id'
            )
            ->get();
    } else {
        $courts = Court::with('location')->get();
    }

    return response()->json($courts);
}

    public function show(Court $court)
    {
        return response()->json([
            'data' => $court->load('location')
        ]);
    }

    public function nearest(Request $request)
{

// \Log::info('NEAREST API CALLED');

    $user = $request->user();

    $lat = $user->latitude;
    $lng = $user->longitude;

    $courts = Court::with('location')
        ->selectRaw("
            courts.*,
            (
                6371 * acos(
                    cos(radians(?))
                    * cos(radians(franchise_locations.latitude))
                    * cos(
                        radians(franchise_locations.longitude)
                        - radians(?)
                    )
                    + sin(radians(?))
                    * sin(radians(franchise_locations.latitude))
                )
            ) AS distance
        ", [$lat, $lng, $lat])
        ->join(
            'franchise_locations',
            'courts.location_id',
            '=',
            'franchise_locations.id'
        )
        ->orderBy('distance')
        ->get();

    // DEBUG
    foreach ($courts as $court) {
        \Log::info('COURT DISTANCE', [
            'court' => $court->court_name,
            'distance' => $court->distance,
        ]);
    }

    return response()->json($courts);
}

public function locations()
{
    return response()->json(
        FranchiseLocation::withCount('courts')->get()
    );
}

public function courtsByLocation($id)
{
    $courts = Court::with('location')
        ->where('location_id', $id)
        ->get();

    return response()->json($courts);
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