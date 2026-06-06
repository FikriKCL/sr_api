<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Payment::with([
            'reservation.court'
        ])
        ->whereHas(
            'reservation',
            fn($q) => $q->where(
                'user_id',
                auth()->id()
            )
        )
        ->get();
    }

    public function show(Payment $payment)
    {
        return $payment->load([
            'reservation.court.location'
        ]);
    }

    public function pay(Payment $payment)
    {
        $payment->update([
            'status' => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json([
            'message' => 'Payment successful'
        ]);
    }
}
