<?php

namespace App\Http\Controllers;

use App\Events\NotifyAdmin;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('frontend.user.profile', compact('user'));
    }

    public function dashboard(Request $request)
    {
        $user = Auth::user();

        // $order = $user->orders()->count();
        // $orderPaid = $user->orders()->where('status', 'paid')->count();
        // $expense = $user->orders()->where('status', 'paid')->sum('total');

        $summary = Order::query()
            ->where('user_id', $user->id)
            ->selectRaw("COUNT(id) as total_orders,
                        COUNT(CASE WHEN is_paid = 1 THEN 1 ELSE 0 END) as total_paid,
                        SUM(CASE WHEN is_paid = 0 THEN total ELSE 0 END) as total_expense")
            ->first();

        // $products = $user->orders()->count();
        $reviews = $user->reviews()->count();
        // $reviews = 10;

        return view('frontend.user.dashboard', compact('user', 'summary', 'reviews'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        abort_if($user->id != $request->user_id, 403, 'User profile does not match.');

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:300',
        ]);

        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time() . '.' . $avatar->getClientOriginalExtension();
            $avatar->storeAs('public/avatars', $avatarName);
            $user->profile_picture = $avatarName;
        }

        $user->name = $request->name;

        if ($user->email != $request->email) {
            $user->email_verified_at = null;
            $user->email = $request->email;
            $user->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->save();
        }

        return redirect()->back()->with([
            'message' => 'Profile updated successfully.',
            'alert' => 'success',
        ]);
    }


    public function orderHistory()
    {
        $user = Auth::user();

        $orders = Order::where('user_id', $user->id)
            ->with(['items'])
            ->orderBy('id', 'desc')
            ->paginate(15);

        return view('frontend.user.order-history', compact('orders'));
    }

    public function  orderDetails($order)
    {
        $order = Auth::user()->orders()->with('products')->findOrFail($order);
        return view('frontend.user.order-details', compact('order'));
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required|string|min:8',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = Auth::user();

        if (!password_verify($request->old_password, $user->password)) {
            return redirect()->back()->with([
                'message' => 'Old password does not match.',
                'alert' => 'error',
            ]);
        }

        $user->password = bcrypt($request->password);

        $user->save();

        return redirect()->back()->with([
            'message' => 'Password updated successfully.',
            'alert' => 'error',
        ]);
    }

    public function notifications(Request $request)
    {
        $notifications = Auth::user()->notifications()->paginate(15);

        return view('frontend.user.notifications', compact('notifications'));
    }

    public function markNotificationAsRead($notification)
    {
        $notification = Auth::user()->notifications()->findOrFail($notification);

        $notification->markAsRead();

        return redirect()->back()->with([
            'message' => 'Notification marked as read.',
            'alert' => 'success',
        ]);
    }

    public function markAllNotificationsAsRead()
    {
        Auth::user()->unreadNotifications->markAsRead();

        return redirect()->back()->with([
            'message' => 'All notifications marked as read.',
            'alert' => 'success',
        ]);
    }

    public function deleteReadNotifications()
    {
        Auth::user()->readNotifications()->delete();

        return redirect()->back()->with([
            'message' => 'Read notifications deleted.',
            'alert' => 'success',
        ]);
    }

    public function deleteAllNotifications()
    {
        Auth::user()->notifications()->delete();

        return redirect()->back()->with([
            'message' => 'All notifications deleted.',
            'alert' => 'success',
        ]);
    }

    public function trackOrder()
    {
        return view('frontend.user.track-order');
    }

    public function addresses()
    {
        return view('frontend.user.address');
    }

    public function support()
    {
        $supports = Auth::user()->supports()->latest()->paginate(15);
        return view('frontend.user.support', compact('supports'));
    }

    public function supportSave(Request $request)
    {
        $request->validate([
            'issue' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        $user = Auth::user();

        $user->supports()->create([
            'type' => $request->issue,
            'description' => $request->description,
        ]);

        event(new NotifyAdmin([
            'message' => 'New support ticket created by ' . $user->name,
        ]));

        return redirect()->back()->with([
            'message' => 'Support ticket created successfully.',
            'alert' => 'success',
        ]);
    }
}
