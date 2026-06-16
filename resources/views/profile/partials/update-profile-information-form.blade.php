<form id="send-verification" method="post" action="{{ route('verification.send') }}">
    @csrf
</form>

<form method="post" action="{{ route('profile.update') }}" class="space-y-4">
    @csrf
    @method('patch')

    <div>
        <label for="name" class="block text-slate-800 font-extrabold text-xs mb-1.5 font-sans">{{ __('Full Name') }}</label>
        <input type="text" class="w-full px-4 py-2.5 rounded-2xl bg-white border-2 border-slate-900 text-slate-800 font-extrabold text-sm focus:outline-none focus:ring-0 font-sans" id="name" name="name" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name">
        @error('name')
            <div class="text-rose-500 font-extrabold text-xs mt-1.5 font-sans"><small>{{ $message }}</small></div>
        @enderror
    </div>

    <div>
        <label for="email" class="block text-slate-800 font-extrabold text-xs mb-1.5 font-sans">{{ __('Email Address') }}</label>
        <input type="email" class="w-full px-4 py-2.5 rounded-2xl bg-white border-2 border-slate-900 text-slate-800 font-extrabold text-sm focus:outline-none focus:ring-0 font-sans" id="email" name="email" value="{{ old('email', $user->email) }}" required autocomplete="username">
        @error('email')
            <div class="text-rose-500 font-extrabold text-xs mt-1.5 font-sans"><small>{{ $message }}</small></div>
        @enderror

        @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
            <div class="mt-2 text-amber-600 font-bold text-xs font-sans">
                {{ __('Your email address is unverified.') }}
                <button form="send-verification" class="text-indigo-650 hover:underline font-extrabold p-0 border-0 bg-transparent cursor-pointer">{{ __('Click here to re-send the verification email.') }}</button>
                @if (session('status') === 'verification-link-sent')
                    <div class="text-emerald-600 font-extrabold mt-1">
                        {{ __('A new verification link has been sent to your email address.') }}
                    </div>
                @endif
            </div>
        @endif
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-5 py-2.5 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer">{{ __('Save') }}</button>
        @if (session('status') === 'profile-updated')
            <span class="text-emerald-500 font-extrabold text-xs font-sans flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> {{ __('Profile updated successfully') }}
            </span>
        @endif
    </div>
</form>
