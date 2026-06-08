<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    /**
     * GET /payments — list payments for the authenticated user
     */
    public function index()
    {
        return response()->json(
            Payment::with(['reservation.court'])
                ->whereHas('reservation', fn ($q) => $q->where('user_id', auth()->id()))
                ->get()
        );
    }

    /**
     * GET /payments/{payment}
     */
    public function show(Payment $payment)
    {
        return response()->json(
            $payment->load(['reservation.court.location'])
        );
    }

    /**
     * POST /payments/{payment}/pay — mark a payment as paid
     */
    public function pay(Payment $payment)
    {
        $payment->update([
            'status'  => 'paid',
            'paid_at' => now(),
        ]);

        return response()->json(['message' => 'Payment successful']);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'payment_option_id' => 'required|exists:payment_options,id',
            'amount' => 'required|integer|min:0',
        ]);

        $payment = Payment::create([
            'reservation_id' => $validated['reservation_id'],
            'payment_option_id' => $validated['payment_option_id'],
            'amount' => $validated['amount'],
            'status' => 'pending',
            'transaction_id' => 'TRX-' . strtoupper(Str::random(10)),
            'paid_at' => null,
        ]);

        return response()->json([
            'message' => 'Payment created successfully',
            'data' => $payment,
        ], 201);
    }
}
