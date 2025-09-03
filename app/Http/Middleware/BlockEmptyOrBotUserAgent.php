<?php 

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class BlockEmptyOrBotUserAgent
{
    protected array $botAllowList = [
        'googlebot',
        'bingbot',
        'yahoo! slurp',
    ];

    protected array $botKeywords = [
        'bot',
        'crawler',
        'spider',
        'curl',
        'wget',
        'python-requests',
        'http-client',
        'libwww',
        'scrapy',
        'go-http-client',
        'axios',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $userAgent = strtolower($request->userAgent() ?? '');

        foreach ($this->botAllowList as $allowed) {
            if (str_contains($userAgent, $allowed)) {
                return $next($request); 
            }
        }

        if (empty($userAgent)) {
            Log::channel('traffic')->warning('Blocked empty User-Agent', [
                'ip' => $request->ip(),
                'url' => $request->fullUrl(),
            ]);
            return response('Blocked: Empty User-Agent', 403);
        }

        foreach ($this->botKeywords as $keyword) {
            if (str_contains($userAgent, $keyword)) {
                Log::channel('traffic')->warning('Blocked bot User-Agent', [
                    'ip' => $request->ip(),
                    'user_agent' => $userAgent,
                    'url' => $request->fullUrl(),
                ]);
                return response('Blocked: Bot User-Agent', 403);
            }
        }

        return $next($request);
    }
}
