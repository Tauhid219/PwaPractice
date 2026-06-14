@extends('frontend.layouts.master')
@section('title', __('Email Verification') . ' | ' . __('Genius Kids - Quiz Guidebook'))

@section('content')
<div class="max-w-md mx-auto py-6">
    <!-- Form Card -->
    <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-6 mb-12">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">✉️</div>
            <h1 class="text-xl font-extrabold text-slate-900 font-sans">{{ __('Email Verification') }}</h1>
            <p class="text-xs text-slate-500 font-extrabold">{{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 bg-emerald-100 border-2 border-slate-900 text-emerald-800 px-3.5 py-2.5 rounded-xl text-xs font-extrabold text-center">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="space-y-4">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="w-full py-3 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer flex items-center justify-center gap-1.5">
                    {{ __('Resend Verification Email') }} <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}" class="text-center">
                @csrf
                <button type="submit" class="text-xs font-extrabold text-rose-500 hover:underline decoration-none cursor-pointer">
                    {{ __('Logout ↩️') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
