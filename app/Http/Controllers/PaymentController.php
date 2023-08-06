<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function callback(Request $request, Payment $payment)
    {
        // if ($payment->status === 'paid') {
        //     return redirect()->route('checkout.confirm', $payment->order->order_id);
        // }

        // $payment->update([
        //     'status' => 'paid',
        // ]);

        // $payment->order->update([
        //     'status' => 'paid',
        // ]);

        // return redirect()->route('checkout.confirm', $payment->order->order_id);

        // Log::info($request->all());
        Log::info("Callback from Payment ID:{$payment->id}, ORDER ID:{$payment->order_id}", [$request->all()]);

        return $this->respond('Payment received', 'success', 'home');
    }
}
