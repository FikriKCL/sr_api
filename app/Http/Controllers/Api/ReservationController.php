<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
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

        $reservation = Reservation::create([
            'user_id' => $validated['user_id'],
            'court_id' => $validated['court_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time' => $validated['start_time'],
            'end_time' => $validated['end_time'],
            'duration' => 2,
            'total_price' => 100000,
            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Reservation berhasil dibuat.',
            'data' => $reservation
        ], 201);
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
