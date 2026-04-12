<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.login');
    }

    /**
     * Display the admin login view.
     */
    public function createAdmin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }
        return view('auth.admin-login');
    }

    /**
     * Helper to redirect based on role.
     */
    protected function redirectBasedOnRole(): RedirectResponse
    {
        if (Auth::user()->hasPermissionTo('access dashboard') || Auth::user()->hasRole('super-admin')) {
            return redirect()->intended(route('admin.dashboard', absolute: false));
        }
        return redirect()->intended(route('home', absolute: false));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole();
        }

        $request->authenticate();

        $request->session()->regenerate();

        return $this->redirectBasedOnRole();
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
