<?php

namespace App\Console\Commands;

use App\Models\Payment;
use App\Services\BlockService;
use Illuminate\Console\Command;
use App\Models\UnknownTransaction;
use App\Events\ConfirmedPaymentEvent;
use App\Events\UnconfirmedPaymentEvent;
use App\Events\UnknownTransactionEvent;

class CheckPayment extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bitcoin:checkpayment';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check for bitcoin payments';

    /**
     * Execute the console command.
     */
    public function handle(BlockService $block): void
    {
        if(config('blockio.api_key') == null || config('blockio.pin') == null) {
            $this->error('PLEASE SET BLOCKIO API KEY .ENV FILE');
            return;
        }

        $this->checkPayment($block);
        $this->checkConfirmation($block);
    }

    /**
     * Check for bitcoin payments.
     */
    private function checkPayment($block)
    {
        $prepayments = Payment::active()->unpaid()->get();

        if ($prepayments->isEmpty()) {
            return;
        }

        $addresses = $prepayments->pluck('address')->implode(',');
        $response = $block->getTransactionsByAddresses('received', $addresses);

        if ($response && $response['status'] != 'success') {
            return;
        }

        $transactions = data_get($response, 'data.txs', []);

        foreach ($transactions as $transaction) {
            $txid = $transaction['txid'];
            $amount = $transaction['amount'];
            $address = $transaction['address'];
            $confirmations = $transaction['confirmations'];

            $payment = $prepayments->where('address', $address)->where('amount', $amount)->first();

            if ($payment) {
                $payment->txid = $txid;
                $payment->received = $amount;
                $payment->confirmations = $confirmations;

                if ($payment->confirmations >= config('blockio.confirmation', 3)) {
                    $payment->is_paid = true;
                    $payment->paid_at = now();
                    $payment->order->update(['is_paid' => true]);
                    event(new ConfirmedPaymentEvent($payment));
                } else {
                    event(new UnconfirmedPaymentEvent($payment));
                }

                $payment->save();
            } else {
                // wrong amount is paid - we don't know for what order
                // is that payment and this is unknown transaction
                // so we will save it as unknown transaction

                $unknownTx = UnknownTransaction::firstOrCreate(
                    ['txid' => $txid,],
                    [
                        'address' => $address,
                        'amount' => $amount,
                        'confirmations' => $confirmations,
                    ]
                );

                event(new UnknownTransactionEvent($unknownTx));
            }
        }
    }

    /**
     * Check for bitcoin confirmations.
     */
    private function checkConfirmation($block)
    {
        //Check for Prepayments with transaction in blockchain
        // (these are paid), but we need number of confirmations
        $payments = Payment::active()->notConfirmed()->get();

        if ($payments->isEmpty()) {
            return;
        }

        $addresses = $payments->pluck('address')->implode(',');
        $response = $block->getTransactionsByAddresses('received', $addresses);

        if ($response && $response['status'] != 'success') {
            return;
        }

        $transactions = data_get($response, 'data.txs', []);

        foreach ($transactions as $transaction) {
            $txid = $transaction['txid'];
            $confirmations = $transaction['confirmations'];

            $payment = $payments->where('txid', $txid)->first();

            if ($payment) {
                $payment->confirmations = $confirmations;

                if ($payment->confirmations >= config('blockio.confirmation', 3)) {
                    $payment->is_paid = true;
                    $payment->paid_at = now();
                    $payment->order->update(['is_paid' => true]);
                    event(new ConfirmedPaymentEvent($payment));
                }

                $payment->save();
            }
        }
    }
}
