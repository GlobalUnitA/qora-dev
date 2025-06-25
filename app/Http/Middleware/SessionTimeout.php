<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class SessionTimeout
{
    protected $timeout = 1440;

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $login_at = session('login_at');

            if (!$login_at) {
                session(['login_at' => now()]);
            } else {
                
                if (now()->diffInMinutes($login_at) > $this->timeout) {
                   
                    Auth::logout();
                    Session::invalidate();
                    Session::regenerateToken();

                    return redirect('/login');
                }
            }
        }

        return $next($request);
    }
}
