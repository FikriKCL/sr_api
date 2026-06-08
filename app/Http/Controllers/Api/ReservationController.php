<?php

namespace App\Http\Controllers\Api;

use App\Models\Court;
use App\Models\Reservation;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function index()
     {
         return Reservation::with([
                'court.location',
                'payment'
        ])
        ->where('user_id', auth()->id())
        ->latest()
        ->get();
     }

    public function show(Reservation $reservation)
    {
        return $reservation->load([
            'user',
            'court.location',
            'payment'
        ]);
    }

 public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'court_id' => 'required|exists:courts,id',
            'reservation_date' => 'required|date',
            'start_time' => 'required|date_format:H:i:s',
            'end_time' => 'required|date_format:H:i:s|after:start_time',
        ]);

        $conflict = Reservation::where('court_id', $validated['court_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($validated) {
                $query->where('start_time', '<', $validated['end_time'])
                    ->where('end_time', '>', $validated['start_time']);
            })
            ->exists();

        if ($conflict) {
            return response()->json([
                'message' => 'Jadwal sudah dibooking pada waktu tersebut.'
            ], 409);
        }

        $court = Court::findOrFail($validated['court_id']);

        $reservation = Reservation::create([
            'user_id' => $validated['user_id'],
            'court_id' => $validated['court_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration' => 1,
            'total_price' => $court->price_per_hour,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Reservation berhasil dibuat.',
            'data' => $reservation,
        ], 201);
    }

    private function getRecommendedSlots($court, $date, $duration)
    {
        $openHour = strtotime($court->location->open_hour);
        $closeHour = strtotime($court->location->close_hour);

        $existingBookings = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $recommendedSlots = [];
            for (
                $time = $openHour;
                $time + ($duration * 3600) <= $closeHour;
                $time += 3600
            ) {
            $start = date('H:i:s', $time);
            $end = date('H:i:s', $time + ($duration * 3600));

            $isConflict = $existingBookings->contains(function ($booking) use ($start, $end) {
                return $booking->start_time < $end &&
                    $booking->end_time > $start;
            });

            if (!$isConflict) {
                $recommendedSlots[] = [
                    'start_time' => $start,
                    'end_time' => $end,
                ];
            }

            if (count($recommendedSlots) >= 3) {
                break;
            }
        }

        return $recommendedSlots;
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Reservation cancelled'
        ]);
    }
}
