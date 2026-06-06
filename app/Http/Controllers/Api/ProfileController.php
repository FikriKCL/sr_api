<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'home_latitude' => $user->home_latitude,
            'home_longitude' => $user->home_longitude,

            'reservation_count' => $user->reservations()->count(),
            'waiting_list_count' => $user->waitingLists()->count(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'nullable|string|max:20',
            'home_latitude' => 'nullable|numeric',
            'home_longitude' => 'nullable|numeric',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data' => $user
        ]);
    }
}