<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function respond(string $message, string $alert = 'success', ?string $route = null, int $status = 200)
    {
        if (config('app.env') === 'production' && $alert === 'error') {
            $message = "Something went wrong. Please try again later.";
        }

        $msg = [
            'alert' => $alert,
            'message' => $message,
            'success' => $status === 200 ? true : false,
        ];

        if (request()->wantsJson()) {
            return response()->json($msg, $status);
        }

        return $route ? redirect()->route($route)->with($msg) : back()->with($msg);
    }

    public function backWithMessage($message, $type = 'success', bool $stop_redirect = false, array $extra = [])
    {
        if (config('app.env') === 'production' && $type === 'error') {
            $message = "Something went wrong. Please try again later.";
        }

        $msg = array_merge([
            'alert' => $type,
            'message' => $message,
        ], $extra);

        return $stop_redirect ? $msg : redirect()->back()->with($msg);
    }

    public function reject($message)
    {
        return $this->respond($message, alert: 'error', status: 400);
    }
}
