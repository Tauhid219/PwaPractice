<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View|RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->hasPermissionTo('access dashboard') || Auth::user()->hasRole('super-admin')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            return redirect()->intended(route('home', absolute: false));
        }

        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        if (Auth::check()) {
            if (Auth::user()->hasPermissionTo('access dashboard') || Auth::user()->hasRole('super-admin')) {
                return redirect()->intended(route('admin.dashboard', absolute: false));
            }

            return redirect()->intended(route('home', absolute: false));
        }
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // New users default to the 'student' role so they stay on the frontend
        $user->assignRole('student');

        event(new Registered($user));

        Auth::login($user);

        return redirect(route('home', absolute: false));
    }
}
