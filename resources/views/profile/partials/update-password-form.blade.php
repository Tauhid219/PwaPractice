<form method="post" action="{{ route('password.update') }}" class="space-y-4">
    @csrf
    @method('put')

    <div>
        <label for="update_password_current_password" class="block text-slate-800 font-extrabold text-xs mb-1.5 font-sans">{{ __('Current Password') }}</label>
        <input type="password" class="w-full px-4 py-2.5 rounded-2xl bg-white border-2 border-slate-900 text-slate-800 font-extrabold text-sm focus:outline-none focus:ring-0 font-sans" id="update_password_current_password" name="current_password" autocomplete="current-password">
        @if ($errors->updatePassword->has('current_password'))
            <div class="text-rose-500 font-extrabold text-xs mt-1.5 font-sans"><small>{{ $errors->updatePassword->first('current_password') }}</small></div>
        @endif
    </div>

    <div>
        <label for="update_password_password" class="block text-slate-800 font-extrabold text-xs mb-1.5 font-sans">{{ __('New Password') }}</label>
        <input type="password" class="w-full px-4 py-2.5 rounded-2xl bg-white border-2 border-slate-900 text-slate-800 font-extrabold text-sm focus:outline-none focus:ring-0 font-sans" id="update_password_password" name="password" autocomplete="new-password">
        @if ($errors->updatePassword->has('password'))
            <div class="text-rose-500 font-extrabold text-xs mt-1.5 font-sans"><small>{{ $errors->updatePassword->first('password') }}</small></div>
        @endif
    </div>

    <div>
        <label for="update_password_password_confirmation" class="block text-slate-800 font-extrabold text-xs mb-1.5 font-sans">{{ __('Confirm Password') }}</label>
        <input type="password" class="w-full px-4 py-2.5 rounded-2xl bg-white border-2 border-slate-900 text-slate-800 font-extrabold text-sm focus:outline-none focus:ring-0 font-sans" id="update_password_password_confirmation" name="password_confirmation" autocomplete="new-password">
        @if ($errors->updatePassword->has('password_confirmation'))
            <div class="text-rose-500 font-extrabold text-xs mt-1.5 font-sans"><small>{{ $errors->updatePassword->first('password_confirmation') }}</small></div>
        @endif
    </div>

    <div class="flex items-center gap-3 pt-2">
        <button type="submit" class="px-5 py-2.5 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer">{{ __('Update Password') }}</button>
        @if (session('status') === 'password-updated')
            <span class="text-emerald-500 font-extrabold text-xs font-sans flex items-center gap-1">
                <i class="fa-solid fa-circle-check"></i> {{ __('Password updated successfully') }}
            </span>
        @endif
    </div>
</form>
