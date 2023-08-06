<?php

namespace App\Jobs;

use App\Mail\SubscriberVerification;
use App\Models\Subscriber;
use Illuminate\Bus\Queueable;
use App\Mail\SubscriptionSuccess;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldBeUnique;

class Subscription implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(public Subscriber $subscriber, public string $action)
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->{$this->action}();
    }

    protected function success(): void
    {
        $this->subscriber->update([
            'is_verified' => true,
            'verification_code' => null,
            'expires_at' => null,
        ]);

        Mail::to($this->subscriber->email)
            ->send(new SubscriptionSuccess($this->subscriber));
    }

    protected function verify(): void
    {
        Mail::to($this->subscriber->email)
            ->send(new SubscriberVerification($this->subscriber));
    }
}
