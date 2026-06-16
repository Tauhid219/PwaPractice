@extends('frontend.layouts.master')
@section('title', __('Profile'))

@section('content')
    <!-- Kid-Friendly Header Block -->
    <header class="rounded-3xl bg-gradient-to-br from-orange-300 to-rose-400 nb p-5 text-white mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('profile.progress') }}" class="w-10 h-10 rounded-2xl bg-white text-slate-800 text-xl flex items-center justify-center nb-sm hover:-translate-y-0.5 active:translate-y-0.5 transition decoration-none shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs opacity-90 font-extrabold text-white mb-0">{{ __('Profile Settings') }}</p>
                <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">
                    {{ __('Update Profile') }}
                </h1>
            </div>
        </div>
    </header>

    <div class="pb-12">
        <div class="max-w-2xl mx-auto">
            <!-- Study Progress Card -->
            <div class="bg-indigo-600 rounded-3xl p-5 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] text-white flex flex-col md:flex-row justify-between items-center gap-4 mb-6" style="background-color: #4f46e5;">
                <div class="text-center md:text-left">
                    <h4 class="text-lg font-extrabold text-white mb-1 font-sans">
                        <i class="fa fa-chart-line me-2"></i> {{ __('Study Progress') }}
                    </h4>
                    <p class="text-xs opacity-90 font-bold mb-0 font-sans">
                        {{ __('View the number of questions you have read and level completion reports here.') }}
                    </p>
                </div>
                <a href="{{ route('profile.progress') }}" class="shrink-0 px-4 py-2 rounded-2xl bg-white hover:bg-amber-50 text-slate-900 border-2 border-slate-900 font-extrabold text-xs decoration-none shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none">
                    {{ __('View Progress') }} <i class="fa-solid fa-arrow-right ms-2"></i>
                </a>
            </div>

            <div class="bg-white rounded-3xl p-5 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] mb-6">
                <h3 class="text-lg font-extrabold text-slate-800 mb-4 border-b-2 border-slate-100 pb-2 font-sans">
                    <i class="fa fa-user text-orange-500 me-2"></i> {{ __('Update Profile') }}
                </h3>
                @include('profile.partials.update-profile-information-form')
            </div>
            
            <div class="bg-white rounded-3xl p-5 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] mb-6">
                <h3 class="text-lg font-extrabold text-slate-800 mb-4 border-b-2 border-slate-100 pb-2 font-sans">
                    <i class="fa fa-lock text-orange-500 me-2"></i> {{ __('Change Password') }}
                </h3>
                @include('profile.partials.update-password-form')
            </div>
            
            <div class="text-center mt-4">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="px-5 py-2.5 rounded-2xl bg-rose-400 hover:bg-rose-500 border-2 border-slate-900 text-white font-extrabold text-xs transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer">
                        <i class="fa fa-sign-out-alt me-2"></i> {{ __('Logout') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection
