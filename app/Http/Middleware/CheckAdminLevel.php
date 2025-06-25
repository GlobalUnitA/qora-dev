<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminLevel
{
   
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  int  $minLevel
     * @return mixed
     */
    public function handle($request, Closure $next, $level)
    {
        $admin = Auth::guard('admin')->user();

        if (!$admin || $admin->admin_level < $level) {

            if ($request->ajax() || $request->wantsJson()) {

                return response()->json([
                    'status' => 'error',
                    'message' => '권한이 없습니다.',
                    'url' => route('admin'),
                ]);
    
            }

            return redirect()->route('admin');
        }

        return $next($request);
    }
}
