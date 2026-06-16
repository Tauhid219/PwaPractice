<nav class="hidden md:flex fixed top-0 inset-x-0 z-40 bg-white border-b-4 border-slate-900 px-6 py-3 items-center justify-between">
    <!-- Brand Logo -->
    <a href="{{ url('/') }}" class="flex items-center gap-2 text-slate-900 hover:text-slate-900 decoration-none">
        <img src="{{ asset('favicon.png') }}" alt="Genius Kids Logo" class="h-10 w-auto object-contain">
        <span class="font-extrabold text-xl font-sans">{{ __('Genius Kids!') }}</span>
    </a>

    <!-- Top Nav Links -->
    <div class="flex gap-3">
        <a href="{{ url('/') }}" class="nav-btn px-4 py-2 rounded-2xl font-extrabold border-2 border-slate-900 {{ Request::is('/') ? 'bg-amber-300 shadow-[4px_4px_0px_#000000] -translate-y-0.5' : 'bg-white hover:bg-amber-50' }} transition-all text-slate-900 decoration-none">
            <i class="fa-solid fa-house me-1"></i> {{ __('Home') }}
        </a>
        <a href="{{ route('live-exams.index') }}" class="nav-btn px-4 py-2 rounded-2xl font-extrabold border-2 border-slate-900 {{ Request::routeIs('live-exams.*') ? 'bg-amber-300 shadow-[4px_4px_0px_#000000] -translate-y-0.5' : 'bg-white hover:bg-amber-50' }} transition-all text-slate-900 decoration-none">
            <i class="fa-solid fa-stopwatch me-1"></i> {{ __('Exams') }}
        </a>
        @auth
            <a href="{{ route('profile.progress') }}" class="nav-btn px-4 py-2 rounded-2xl font-extrabold border-2 border-slate-900 {{ Request::routeIs('profile.*') ? 'bg-amber-300 shadow-[4px_4px_0px_#000000] -translate-y-0.5' : 'bg-white hover:bg-amber-50' }} transition-all text-slate-900 decoration-none">
                <i class="fa-solid fa-user-astronaut me-1"></i> {{ __('Profile') }}
            </a>
            @if(auth()->user()->can('access dashboard') || auth()->user()->hasRole('super-admin'))
                <a href="{{ route('admin.dashboard') }}" class="nav-btn px-4 py-2 rounded-2xl font-extrabold border-2 border-slate-900 bg-sky-200 hover:bg-sky-300 transition-all text-slate-900 decoration-none">
                    <i class="fa-solid fa-gears me-1"></i> {{ __('Admin Panel') }}
                </a>
            @endif
        @endauth
    </div>

    <!-- Right Actions: Streak, XP, Locale Toggle & PWA Install/Auth -->
    <div class="flex items-center gap-3">
        <!-- Locale Switcher -->
        <a href="{{ route('locale.change', App::getLocale() === 'bn' ? 'en' : 'bn') }}" class="px-3 py-2 rounded-2xl bg-sky-100 hover:bg-sky-200 border-2 border-slate-900 text-slate-800 font-extrabold text-sm decoration-none">
            {{ App::getLocale() === 'bn' ? 'English' : 'বাংলা' }}
        </a>

        @auth
            <!-- Streak -->
            <span class="px-3 py-1.5 rounded-full bg-amber-100 border-2 border-slate-900 font-extrabold text-sm text-slate-800 shadow-sm">
                🔥 {{ auth()->user()->current_streak }} {{ __('day streak') }}
            </span>

            <!-- XP Points -->
            <span class="px-3 py-1.5 rounded-full bg-emerald-100 border-2 border-slate-900 font-extrabold text-sm text-slate-800 shadow-sm">
                ⭐ {{ auth()->user()->quizAttempts()->sum('score') * 10 }} XP
            </span>

            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}" class="inline m-0">
                @csrf
                <button type="submit" class="px-4 py-2 rounded-2xl bg-rose-400 hover:bg-rose-500 border-2 border-slate-900 text-white font-extrabold text-sm transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none">
                    {{ __('Logout') }}
                </button>
            </form>
        @else
            <a href="{{ route('login') }}" class="px-4 py-2 rounded-2xl bg-emerald-400 hover:bg-emerald-500 border-2 border-slate-900 text-white font-extrabold text-sm transition-all shadow-[3px_3px_0px_#000000] text-center decoration-none">
                {{ __('Login 🚀') }}
            </a>
        @endauth

        <!-- Install Button (PWA) -->
        <a href="javascript:void(0)" id="install-btn" class="hidden px-4 py-2 rounded-2xl bg-sky-400 hover:bg-sky-500 border-2 border-slate-900 text-white font-extrabold text-sm transition-all shadow-[3px_3px_0px_#000000] decoration-none">
            {{ __('Install App') }} <i class="fa fa-download ms-1"></i>
        </a>
    </div>
</nav>
