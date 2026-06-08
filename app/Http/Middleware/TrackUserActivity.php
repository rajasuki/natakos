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
            $dirty = false;

            if ($user->last_seen_at === null || $user->last_seen_at->diffInMinutes(now()) >= 1) {
                $user->last_seen_at = now();
                $dirty = true;
            }

            $ip = $request->ip();
            if ($user->last_ip !== $ip) {
                $user->last_ip = $ip;
                $dirty = true;
            }

            $ua = $request->userAgent();
            if ($user->last_user_agent !== $ua) {
                $user->last_user_agent = $ua;
                $dirty = true;
            }

            if ($dirty) {
                $user->saveQuietly();
            }
        }

        return $next($request);
    }
}
