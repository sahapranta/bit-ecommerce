<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Jobs\Subscription;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function subscribe(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers',
            'name' => 'nullable|min:3|max:255',
            'phone' => 'nullable|min:9|max:15',
        ]);

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'name' => $request->name,
            'phone' => $request->phone,
            'verification_code' => Str::uuid(),
        ]);

        dispatch(new Subscription($subscriber, 'verify'))->afterResponse();

        return $this->respond(
            'Your email is successfully subscribed.',
            route: 'home'
        );
    }


    public function verify(Subscriber $subscriber)
    {
        if ($subscriber->is_verified) {
            return redirect()->route('home')->with([
                'message' => 'Your email is already verified.',
                'type' => 'success',
            ]);
        }

        if ($subscriber->expires_at && $subscriber->expires_at->isPast()) {
            return redirect()->route('home')->with([
                'message' => 'Your email verification link is expired.',
                'type' => 'error',
            ]);
        }

        dispatch(new Subscription($subscriber, 'success'))->afterResponse();

        return redirect()->route('home')->with([
            'message' => 'Your email is successfully verified.',
            'type' => 'success',
        ]);
    }

    public function unsubscribe(Request $request)
    {
        $request->validate([
            'mail' => 'required|email|exists:subscribers',
        ]);

        $subscriber = Subscriber::where('mail', $request->email)->first();

        if ($subscriber->is_unsubscribed) {
            return redirect()->route('home')->with([
                'message' => 'Your email is already unsubscribed.',
                'type' => 'success',
            ]);
        }

        $subscriber->update([
            'is_unsubscribed' => true,
            'is_active' => false,
        ]);

        return redirect()->route('home')->with([
            'message' => 'Your email is successfully unsubscribed.',
            'type' => 'success',
        ]);
    }
}
