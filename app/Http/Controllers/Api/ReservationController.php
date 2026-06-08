<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Court;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * GET /reservations — list reservations for the authenticated user
     */
    public function index()
    {
        return response()->json(
            Reservation::with(['court.location', 'payment'])
                ->where('user_id', auth()->id())
                ->latest()
                ->get()
        );
    }

    /**
     * GET /reservations/{reservation}
     */
    public function show(Reservation $reservation)
    {
        return response()->json(
            $reservation->load(['user', 'court.location', 'payment'])
        );
    }

    /**
     * POST /reservations
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'user_id'          => 'required|exists:users,id',
            'court_id'         => 'required|exists:courts,id',
            'reservation_date' => 'required|date',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
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
            $court = Court::with('location')->findOrFail($validated['court_id']);

            $recommendedSlots = $this->getRecommendedSlots(
                $court,
                $validated['reservation_date'],
                2
            );

            return response()->json([
                'message'           => 'Jadwal sudah dibooking pada waktu tersebut.',
                'recommended_slots' => $recommendedSlots,
            ], 409);
        }

        // Calculate total price
        $court      = Court::findOrFail($validated['court_id']);
        $startHour  = (int) explode(':', $validated['start_time'])[0];
        $endHour    = (int) explode(':', $validated['end_time'])[0];
        $duration   = max(1, $endHour - $startHour);
        $totalPrice = $court->price_per_hour * $duration;

        $reservation = Reservation::create([
            'user_id'          => $validated['user_id'],
            'court_id'         => $validated['court_id'],
            'reservation_date' => $validated['reservation_date'],
            'start_time'       => $validated['start_time'],
            'end_time'         => $validated['end_time'],
            'duration'         => $duration,
            'total_price'      => $totalPrice,
            'status'           => 'pending',
        ]);

        return response()->json([
            'message' => 'Reservation berhasil dibuat.',
            'data'    => $reservation->load('court.location'),
        ], 201);
    }

    /**
     * DELETE /reservations/{reservation} — cancel a reservation
     */
    public function destroy(Reservation $reservation)
    {
        $this->authorize('delete', $reservation);

        $reservation->update(['status' => 'cancelled']);

        return response()->json(['message' => 'Reservation cancelled']);
    }

    // ── Private helpers ────────────────────────────────────────────────────────

    private function getRecommendedSlots($court, $date, $duration)
    {
        $openHour  = strtotime($court->location->open_hour);
        $closeHour = strtotime($court->location->close_hour);

        $existingBookings = Reservation::where('court_id', $court->id)
            ->where('reservation_date', $date)
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $recommendedSlots = [];

        for ($time = $openHour; $time + ($duration * 3600) <= $closeHour; $time += 3600) {
            $start = date('H:i', $time);
            $end   = date('H:i', $time + ($duration * 3600));

            $isConflict = $existingBookings->contains(function ($booking) use ($start, $end) {
                return $booking->start_time < $end && $booking->end_time > $start;
            });

            if (!$isConflict) {
                $recommendedSlots[] = ['start_time' => $start, 'end_time' => $end];
            }

            if (count($recommendedSlots) >= 3) {
                break;
            }
        }

        return $recommendedSlots;
    }
}
