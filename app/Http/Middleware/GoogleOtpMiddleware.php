<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;
use Carbon\Carbon;

class GoogleOtpMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->routeIs('otp') || $request->routeIs('otp.verify') || $request->routeIs('admin.otp') || $request->routeIs('admin.otp.verify')) {
            return $next($request);
        }

        $guard = $request->is('admin*') ? 'admin' : 'web';

        if (!auth($guard)->check()) {
            return redirect()->route($guard === 'admin' ? 'admin.login' : 'login');
        }

        if ($guard === 'admin' && session("otp_verified_at_admin")) {
            $verified_at = Carbon::parse(session("otp_verified_at_admin"));
            if ($verified_at->gt(Carbon::now()->subHours(2))) {
                return $next($request);
            }
        }

        if (session("otp_verified_{$guard}")) {
            session()->forget("otp_verified_{$guard}");
            return $next($request);
        }

        $intendedKey = "otp.intended_url_{$guard}";
        if (!session()->has($intendedKey)) {
            session([$intendedKey => $request->fullUrl()]);
        }

        if ($guard == 'admin') {
            return redirect()->route('admin.otp');
        } else {
            return redirect()->route('otp');
        }

    }
}
