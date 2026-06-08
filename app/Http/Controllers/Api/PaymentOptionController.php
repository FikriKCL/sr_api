<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentOption;
use Illuminate\Http\Request;

class PaymentOptionController extends Controller
{
    /**
     * Display a listing of payment options.
     */
    public function index()
    {
        $paymentOptions = PaymentOption::where(
            'status',
            'active'
        )->get();

        return response()->json([
            'success' => true,
            'data' => $paymentOptions,
        ]);
    }

    /**
     * Display a single payment option.
     */
    public function show(PaymentOption $paymentOption)
    {
        return response()->json([
            'success' => true,
            'data' => $paymentOption,
        ]);
    }
}