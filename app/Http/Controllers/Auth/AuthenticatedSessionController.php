<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password salah.',
            ]);
        }

        $request->session()->regenerate();

        $redirectPath = $this->dashboardPath($request->user());

        if ($redirectPath === null) {
            return $this->logoutInvalidRole($request);
        }

        return redirect()->to($redirectPath);
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function dashboard(Request $request): RedirectResponse
    {
        $redirectPath = $this->dashboardPath($request->user());

        if ($redirectPath === null) {
            return $this->logoutInvalidRole($request);
        }

        return redirect()->to($redirectPath);
    }

    private function dashboardPath(User $user): ?string
    {
        return match ($user->role) {
            'admin' => route('admin.dashboard'),
            'tenant' => route('tenant.dashboard'),
            default => null,
        };
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
