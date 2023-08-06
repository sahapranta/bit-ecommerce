<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Order;
use App\Events\NotifyAdmin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $orders = Order::latest()->with(['user'])->paginate(10);

        // event(new NotifyAdmin([
        //     'message' => 'New Payment Confirmed of ',
        //     'action' => 'No where to go'
        // ]));

        $summary = Cache::remember('dashboard_order_summary', 3600 * 6, function () {
            return Order::query()
                ->selectRaw("SUM(CASE WHEN status = 'paid' THEN 'total' ELSE 0 END) as paid,
                                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending")
                ->first();
        });

        $totalCustomers = Cache::remember('total_customers', 3600 * 6, fn () => User::where('is_admin', false)->count());

        return view('backend.dashboard', compact('orders', 'summary', 'totalCustomers'));
    }

    public function profile(Request $request)
    {
        $user = Auth::user();
        return view('frontend.user.profile', compact('user'));
    }

    public function notifications(Request $request)
    {
        $notifications = Auth::user()->notifications()->paginate(15);
        return view('backend.notifications', compact('notifications'));
    }
}
