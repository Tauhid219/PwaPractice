@extends('frontend.layouts.master')
@section('title', 'পাসওয়ার্ড রিসেট | জিনিয়াস কিডস')

@section('content')
<div class="max-w-md mx-auto py-6">
    <!-- Form Card -->
    <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-6 mb-12">
        <div class="text-center mb-6">
            <div class="text-5xl mb-2">🛠️</div>
            <h1 class="text-xl font-extrabold text-slate-900 font-sans">নতুন পাসওয়ার্ড তৈরি করো</h1>
            <p class="text-xs text-slate-500 font-extrabold">তোমার নতুন পাসওয়ার্ডটি নিচে প্রদান করো!</p>
        </div>

        <form method="POST" action="{{ route('password.store') }}" class="space-y-4">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-xs font-extrabold text-slate-650 mb-1.5">ইমেইল এড্রেস</label>
                <input type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email', $request->email) }}" 
                    required 
                    placeholder="name@email.com" 
                    class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-900 bg-white text-slate-800 text-sm font-sans focus:outline-none focus:bg-amber-50 focus:border-slate-900 placeholder:text-slate-400 transition-colors"
                >
                @error('email')
                    <p class="text-xs text-rose-500 font-extrabold mt-1.5 font-sans">{{ $message }}</p>
                @enderror
            </div>

            <!-- Password -->
            <div>
                <label for="password" class="block text-xs font-extrabold text-slate-650 mb-1.5">নতুন পাসওয়ার্ড</label>
                <input type="password" 
                    id="password" 
                    name="password" 
                    required 
                    placeholder="••••••••" 
                    class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-900 bg-white text-slate-800 text-sm font-sans focus:outline-none focus:bg-amber-50 focus:border-slate-900 placeholder:text-slate-400 transition-colors"
                >
                @error('password')
                    <p class="text-xs text-rose-500 font-extrabold mt-1.5 font-sans">{{ $message }}</p>
                @enderror
            </div>

            <!-- Confirm Password -->
            <div>
                <label for="password_confirmation" class="block text-xs font-extrabold text-slate-650 mb-1.5">নতুন পাসওয়ার্ড নিশ্চিত করো</label>
                <input type="password" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required 
                    placeholder="••••••••" 
                    class="w-full px-4 py-2.5 rounded-2xl border-2 border-slate-900 bg-white text-slate-800 text-sm font-sans focus:outline-none focus:bg-amber-50 focus:border-slate-900 placeholder:text-slate-400 transition-colors"
                >
                @error('password_confirmation')
                    <p class="text-xs text-rose-500 font-extrabold mt-1.5 font-sans">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="w-full py-3 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer flex items-center justify-center gap-1.5">
                    পাসওয়ার্ড পরিবর্তন করো <i class="fa-solid fa-key"></i>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
