@extends('frontend.layouts.master')
@section('title', __('Genius Kids - Quiz Guidebook'))

@section('content')
    <!-- Kid-Friendly Header Block -->
    <header class="rounded-3xl bg-gradient-to-br from-sky-300 to-sky-500 nb p-5 text-white mb-6">
        <div class="flex items-center justify-between flex-wrap gap-4">
            <div class="flex items-center gap-3">
                <div class="w-14 h-14 rounded-2xl bg-white text-3xl flex items-center justify-center nb-sm shrink-0">
                    @auth
                        {{ session('avatar_emoji', auth()->user()->avatar_emoji ?? '🐼') }}
                    @else
                        🐼
                    @endauth
                </div>
                <div>
                    <p class="text-xs opacity-90 font-extrabold text-white mb-0">{{ __('Hello,') }}</p>
                    <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">
                        @auth
                            {{ auth()->user()->name }}! 👋
                        @else
                            {{ __('Genius Kids!') }} 👋
                        @endauth
                    </h1>
                </div>
            </div>
            <div class="text-right flex items-center gap-2 sm:flex-col sm:items-end sm:gap-0">
                @auth
                    @if(auth()->user()->current_streak > 0)
                        <div class="px-3 py-1 rounded-full bg-amber-300 text-slate-900 nb-sm font-extrabold text-xs">
                            🔥 {{ auth()->user()->current_streak }} {{ __('day streak') }}
                        </div>
                    @endif
                    <div class="mt-0 sm:mt-2 px-3 py-1 rounded-full bg-emerald-400 text-white nb-sm font-extrabold text-xs border-white">
                        ⭐ {{ auth()->user()->quizAttempts()->sum('score') * 10 }} XP
                    </div>
                @else
                    <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-full bg-amber-300 hover:bg-amber-400 text-slate-900 nb-sm font-extrabold text-xs decoration-none">
                        {{ __('Login 🚀') }}
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <h2 class="text-xl font-extrabold mb-4 flex items-center gap-2 text-slate-800">{{ __('Choose Subject') }}</h2>
    
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-12">
        @php
            $colors = ['emerald', 'sky', 'amber', 'orange', 'rose'];
            $colorMap = [
                'sky' => ['border' => 'border-sky-500', 'btn' => 'bg-sky-400 hover:bg-sky-500'],
                'emerald' => ['border' => 'border-emerald-500', 'btn' => 'bg-emerald-400 hover:bg-emerald-500'],
                'amber' => ['border' => 'border-amber-500', 'btn' => 'bg-amber-400 hover:bg-amber-500'],
                'orange' => ['border' => 'border-orange-500', 'btn' => 'bg-orange-400 hover:bg-orange-500'],
                'rose' => ['border' => 'border-rose-500', 'btn' => 'bg-rose-400 hover:bg-rose-500'],
            ];
            $emojis = [
                'quran' => '📖',
                'prophet' => '🕌',
                'nabi' => '🕌',
                'hadith' => '📜',
                'hadith-sahaba' => '📜',
                'islamic' => '🌙',
                'history' => '🏛️',
                'education' => '✏️',
                'sports' => '⚽',
                'science' => '🔬',
                'world' => '🌍',
                'bangladesh' => '🇧🇩',
            ];
        @endphp

        @forelse($categories as $category)
            @php
                $colorKey = $colors[$loop->index % count($colors)];
                $color = $colorMap[$colorKey];
                
                $slug = Str::slug($category->slug);
                $emoji = '📚';
                foreach($emojis as $key => $val) {
                    if (str_contains($slug, $key)) {
                        $emoji = $val;
                        break;
                    }
                }

                $totalLevels = $category->levels->count();
                $doneLevels = 0;
                if (auth()->check()) {
                    $doneLevels = auth()->user()->userProgress()
                        ->where('category_id', $category->id)
                        ->where('status', 'completed')
                        ->count();
                }
                $progressPercent = $totalLevels > 0 ? round(($doneLevels / $totalLevels) * 100) : 0;
            @endphp

            <a href="{{ route('category.levels', $category->slug) }}" class="cat-card text-left bg-white rounded-3xl p-4 nb hover:-translate-y-1 active:translate-y-0 transition-transform border-l-[6px] {{ $color['border'] }} decoration-none text-slate-800 flex flex-col justify-between">
                <div>
                    <div class="text-4xl mb-2">{{ $emoji }}</div>
                    <h3 class="font-extrabold text-base leading-tight mb-2 text-slate-900">{{ $category->name }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $category->description }}</p>
                </div>
                <div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mb-1">
                        <div class="h-full {{ $color['btn'] }}" style="width: {{ $progressPercent }}%"></div>
                    </div>
                    <p class="text-[11px] font-extrabold text-slate-500 mb-3">
                        {{ $doneLevels }}/{{ $totalLevels }} {{ __('Levels') }}
                    </p>
                    <span class="inline-block w-full text-center py-2 rounded-xl {{ $color['btn'] }} text-white text-xs font-extrabold nb-sm">
                        {{ __('Start') }}
                    </span>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center py-12 bg-white rounded-3xl nb p-6">
                <div class="text-5xl mb-2">😅</div>
                <h3 class="font-extrabold text-lg">{{ __('Sorry, no subjects found!') }}</h3>
            </div>
        @endforelse
    </div>
@endsection
