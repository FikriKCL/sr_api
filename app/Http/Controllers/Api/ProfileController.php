<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    /**
     * GET /profile — returns the authenticated user's profile
     * Route: Route::get('/profile', [ProfileController::class, 'me']);
     */
    public function me(Request $request)
    {
        $user = $request->user();

        return response()->json([
            'id'                  => $user->id,
            'name'                => $user->name,
            'email'               => $user->email,
            'phone'               => $user->phone,
            'home_latitude'       => $user->home_latitude,
            'home_longitude'      => $user->home_longitude,
            'reservation_count'   => $user->reservations()->count(),
            'waiting_list_count'  => $user->waitingLists()->count(),
        ]);
    }

    /**
     * PUT /profile — updates the authenticated user's profile
     * Route: Route::put('/profile', [ProfileController::class, 'updateMe']);
     */
    public function updateMe(Request $request)
    {
        $user = $request->user();

        $validated = $request->validate([
            'name'            => 'required|string|max:255',
            'phone'           => 'nullable|string|max:20',
            'home_latitude'   => 'nullable|numeric',
            'home_longitude'  => 'nullable|numeric',
        ]);

        $user->update($validated);

        return response()->json([
            'message' => 'Profile updated successfully',
            'data'    => $user,
        ]);
    }
}
