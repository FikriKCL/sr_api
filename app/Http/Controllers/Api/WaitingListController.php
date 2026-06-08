<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WaitingList;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    /**
     * GET /waiting-lists — list waiting list entries for the authenticated user
     */
    public function index()
    {
        return response()->json(
            WaitingList::with(['court.location'])
                ->where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->get()
        );
    }

    /**
     * POST /waiting-lists — join a waiting list
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id'         => 'required|exists:courts,id',
            'reservation_date' => 'required|date',
            'requested_time'   => 'required|date_format:H:i',
        ]);

        $position = WaitingList::where('court_id', $validated['court_id'])
            ->where('reservation_date', $validated['reservation_date'])
            ->count() + 1;

        $waitingList = WaitingList::create([
            'user_id'          => auth()->id(),
            'court_id'         => $validated['court_id'],
            'reservation_date' => $validated['reservation_date'],
            'requested_time'   => $validated['requested_time'],
            'position'         => $position,
        ]);

        return response()->json([
            'message'  => 'Successfully joined waiting list.',
            'data'     => $waitingList->load('court.location'),
            'position' => $position,
        ], 201);
    }

    /**
     * DELETE /waiting-lists/{waitingList} — leave the waiting list
     */
    public function destroy(WaitingList $waitingList)
    {
        $waitingList->delete();

        return response()->json(['message' => 'Removed from waiting list']);
    }
}
