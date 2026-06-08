<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'reservation_id' => 'required|exists:reservations,id',
            'payment_method' => 'required|string|max:50',
            'amount' => 'required|integer|min:0',
        ]);

        $payment = Payment::create([
            'reservation_id' =>
                $validated['reservation_id'],

            'payment_option_id' =>
                $validated['payment_option_id'],

            'amount' =>
                $validated['amount'],

            'status' => 'pending',
        ]);

        return response()->json([
            'message' => 'Payment created successfully',
            'data' => $payment,
        ], 201);
    }
}