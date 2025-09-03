<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class AdminAuthenticate
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         
        if (!Auth::guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        Cookie::queue(Cookie::forget('app_locale'));
        Cookie::queue('app_locale', 'ko', 60 * 24 * 30); 

        App::setLocale('ko');

        return $next($request);
    }
}
