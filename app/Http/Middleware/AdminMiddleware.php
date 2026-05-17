<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user->role === 'admin') {
            return $next($request);
        }

        if ($user->role === 'tenant') {
            return redirect()->route('tenant.dashboard');
        }

        return $this->logoutInvalidRole($request);
    }

    private function logoutInvalidRole(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->withErrors([
            'email' => 'Role akun tidak valid. Silakan login kembali.',
        ]);
    }
}
