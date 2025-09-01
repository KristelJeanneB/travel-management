<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;

class PaymentController extends Controller
{
    // Show the payment form
    public function showPaymentForm()
    {
        return view('payment');
    }

    // Store payment form submission
    public function confirmPayment(Request $request)
    {
        $validated = $request->validate([
            'payer_name' => 'required|string|max:255',
            'payer_email' => 'required|email|max:255',
            'amount' => 'required|numeric|min:150',
        ]);

        Payment::create([
            'payer_name' => $validated['payer_name'],
            'payer_email' => $validated['payer_email'],
            'amount' => $validated['amount'],
            'payment_method' => 'GCash',
            'status' => 'pending',
            'user_id' => auth()->check() ? auth()->id() : null, // Optional
        ]);

        return redirect()->route('payment')->with('success', 'Payment confirmation received! Thank you.');
    }

    // ✅ Return all payments for admin dashboard
    public function getPaymentsData()
    {
        $payments = Payment::latest()->get()->map(function ($payment) {
            return [
                'id' => $payment->id,
                'user_name' => $payment->payer_name,
                'amount' => '₱' . number_format($payment->amount, 2),
                'status' => $payment->status,
            ];
        });

        return response()->json($payments);
    }

    // ✅ Confirm payment via AJAX
    public function confirmPaymentById($id)
    {
        $payment = Payment::find($id);

        if (!$payment) {
            return response()->json(['success' => false, 'message' => 'Payment not found.'], 404);
        }

        if ($payment->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Payment is not pending.'], 400);
        }

        $payment->status = 'confirmed';
        $payment->save();

        return response()->json(['success' => true]);
    }
}
