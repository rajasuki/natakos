<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackUserActivity
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($user = $request->user()) {
            $user->last_seen_at = now();
            $user->last_ip = $request->ip();
            $user->last_user_agent = $request->userAgent();
            $user->saveQuietly();
        }

        return $next($request);
    }
}
