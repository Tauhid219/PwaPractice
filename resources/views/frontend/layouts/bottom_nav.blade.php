<nav class="md:hidden fixed bottom-0 inset-x-0 z-40 bg-white border-t-4 border-slate-900 px-2 py-2 flex justify-around items-center">
    <!-- Home -->
    <a href="{{ url('/') }}" class="flex flex-col items-center justify-center px-3 py-1.5 rounded-2xl font-extrabold decoration-none text-slate-850 {{ Request::is('/') ? 'bg-amber-300 nb-sm' : '' }}">
        <i class="fa-solid fa-house text-lg"></i>
        <span class="text-[10px] mt-0.5">হোম</span>
    </a>

    <!-- Live Exam -->
    <a href="{{ route('live-exams.index') }}" class="flex flex-col items-center justify-center px-3 py-1.5 rounded-2xl font-extrabold decoration-none text-slate-850 {{ Request::routeIs('live-exams.*') ? 'bg-amber-300 nb-sm' : '' }}">
        <i class="fa-solid fa-stopwatch text-lg"></i>
        <span class="text-[10px] mt-0.5">পরীক্ষা</span>
    </a>

    @auth
        <!-- Profile / Progress -->
        <a href="{{ route('profile.progress') }}" class="flex flex-col items-center justify-center px-3 py-1.5 rounded-2xl font-extrabold decoration-none text-slate-850 position-relative {{ Request::routeIs('profile.progress') ? 'bg-amber-300 nb-sm' : '' }}">
            @if(auth()->user()->current_streak > 0)
                <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center rounded-full bg-rose-500 text-[9px] text-white font-extrabold border border-slate-900">
                    🔥
                </span>
            @endif
            <i class="fa-solid fa-user-astronaut text-lg"></i>
            <span class="text-[10px] mt-0.5">প্রোফাইল</span>
        </a>

        @if(auth()->user()->can('access dashboard') || auth()->user()->hasRole('super-admin'))
            <!-- Admin -->
            <a href="{{ route('admin.dashboard') }}" class="flex flex-col items-center justify-center px-3 py-1.5 rounded-2xl font-extrabold decoration-none text-slate-850 bg-sky-200 border-2 border-slate-900 shadow-[2px_2px_0px_#0f172a]">
                <i class="fa-solid fa-gears text-lg"></i>
                <span class="text-[10px] mt-0.5">অ্যাডমিন</span>
            </a>
        @endif
    @else
        <!-- Login -->
        <a href="{{ route('login') }}" class="flex flex-col items-center justify-center px-3 py-1.5 rounded-2xl font-extrabold decoration-none text-slate-850 {{ Request::routeIs('login') ? 'bg-amber-300 nb-sm' : '' }}">
            <i class="fa-solid fa-right-to-bracket text-lg"></i>
            <span class="text-[10px] mt-0.5">লগইন</span>
        </a>
    @endauth
</nav>
