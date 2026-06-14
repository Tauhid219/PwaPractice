@extends('frontend.layouts.master')
@section('title', __('Reset Password') . ' | ' . __('Genius Kids - Quiz Guidebook'))

@section('content')
<div class="max-w-md mx-auto py-6">
    <!-- Form Card -->
    <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-6 mb-12">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">🔒</div>
            <h1 class="text-xl font-extrabold text-slate-900 font-sans">{{ __('Forgot Password?') }}</h1>
            <p class="text-xs text-slate-500 font-extrabold">{{ __('No problem! Just let us know your email address and we will email you a password reset link.') }}</p>
        </div>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 bg-emerald-100 border-2 border-slate-900 text-emerald-800 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-center">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-extrabold text-slate-650 mb-1.5">{{ __('Email Address') }}</label>
                <input type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}" 
                    required 
                    autofocus 
                    placeholder="name@email.com" 
                    class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-900 bg-white text-slate-800 text-sm font-sans focus:outline-none focus:bg-amber-50 focus:border-slate-900 placeholder:text-slate-400 transition-colors"
                >
                @error('email')
                    <p class="text-xs text-rose-500 font-extrabold mt-1.5 font-sans">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer flex items-center justify-center gap-1.5">
                    {{ __('Send Reset Link') }} <i class="fa-solid fa-paper-plane"></i>
                </button>
            </div>
        </form>

        <div class="text-center mt-6 pt-4 border-t border-slate-100 text-xs font-extrabold text-slate-500">
            <a href="{{ route('login') }}" class="text-indigo-650 hover:underline decoration-none font-bold">
                {{ __('Back to Login ↩️') }}
            </a>
        </div>
    </div>
</div>
@endsection
