<?php

namespace App\Http\Middleware;

use Closure;    
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrafficLogger
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('api/crypto-prices')) {
           return $response;
        }

        Log::channel('traffic')->info('Traffic log', [
            'ip_address' => $request->ip(),
            'user_id' => auth()->user()?->id,
            'admin_id' => auth()->guard('admin')->user()?->id,
            'url' => $request->fullUrl(),
            'method' => $request->method(),
            'user_agent' => $request->header('User-Agent'),
            'status_code'  => $response->getStatusCode(),
            'timestamp' => now(),
        ]);        

        return $response;
    }
}
