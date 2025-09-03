<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = $request->cookie('app_locale', config('app.locale')); 

        app()->setLocale($locale);

        return $next($request);
    }
}