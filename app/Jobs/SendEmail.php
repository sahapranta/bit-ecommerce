<?php

namespace App\Jobs;

use App\Mail\OrderSuccessMail;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(protected $details, protected $order)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $email = new OrderSuccessMail($this->order);
        Mail::to($this->details['email'])->send($email);
    }
}
