<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Cache;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PaymentService
{
    public function __construct()
    {
        //
    }

    public function charge(Order $order)
    {
        // dd($order->id);
        $payment = Payment::create([
            'user_id' => User::find(2)->id,
            'order_id' => $order->id,
            'address' => $this->getCurrentUserAddress(),
            'amount' => $order->btc_total,
        ]);

        // return $payment;
    }

    public function getCurrentUserAddress()
    {
        // $address = auth()->user()->btc_address;
        $address = User::find(2)->btc_address;

        if (!$address) {
            $address = $this->generateAddress();
        }

        return $address;
    }

    public function generateAddress()
    {
        $btc = \BlockService::createAddress(label: Str::studly(User::find(2)->name));

        User::find(2)->update([
            'btc_address' => optional($btc)->data?->address,
        ]);

        return $btc->data->address;
    }

    public function getPaymentLink(Payment $payment): string
    {
        // bitcoin:mjSk1Ny9spzU2fouzYgLqGUD8U41iR35QN?amount=0.10&label=Example+Merchant&message=Order+of+flowers+%26+chocolates&r=https://example.com/pay.php/invoice%3Dda39a3ee
        $params = array_merge(
            ['amount' => $payment->amount],
            $this->buildParams($payment)
        );

        $query = http_build_query($params);

        return "bitcoin:{$payment->address}?{$query}";
    }


    public function buildParams($payment)
    {
        $label = $payment->order_id;

        return Cache::remember($label, 60 * 60 * 24, function () use ($label, $payment) {
            return [
                'label' => $label,
                'message' => 'Order of ' . $payment->order->items->count() . ' items total ' . $payment->order->total . ' ' . \AppSettings::get('currency_code', 'GBP'),
                'r' => route('payments.callback', $payment->id),
            ];
        });
    }

    public function getPaymentLinkQr(Payment $payment)
    {
        return QrCode::size(120)
            ->backgroundColor(255, 255, 255, 0)
            ->BTC($payment->address, $payment->amount, $this->buildParams($payment));
    }
}
