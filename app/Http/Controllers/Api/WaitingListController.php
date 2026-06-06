<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WaitingListController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return WaitingList::with([
            'court.location'
        ])
        ->where(
            'user_id',
            auth()->id()
        )
        ->get();
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'court_id' => 'required',
            'reservation_date' => 'required|date',
            'requested_time' => 'required'
        ]);

        $position =
            WaitingList::where(
                'court_id',
                $validated['court_id']
            )->count() + 1;

        return WaitingList::create([
            'user_id' => auth()->id(),
            ...$validated,
            'position' => $position
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
