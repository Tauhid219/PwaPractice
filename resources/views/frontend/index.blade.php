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
                    <div class="px-3 py-1 rounded-full bg-amber-300 text-slate-900 nb-sm font-extrabold text-xs">
                        🔥 {{ auth()->user()->current_streak }} {{ __('day streak') }}
                    </div>
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
    
    @php
        $colors = ['emerald', 'sky', 'amber', 'orange', 'rose'];
        $colorMap = [
            'sky' => ['border' => 'border-sky-500', 'btn' => 'bg-sky-400 hover:bg-sky-500', 'hex_bg' => 'bg-gradient-to-b from-sky-200 to-sky-400'],
            'emerald' => ['border' => 'border-emerald-500', 'btn' => 'bg-emerald-400 hover:bg-emerald-500', 'hex_bg' => 'bg-gradient-to-b from-emerald-200 to-emerald-400'],
            'amber' => ['border' => 'border-amber-500', 'btn' => 'bg-amber-400 hover:bg-amber-500', 'hex_bg' => 'bg-gradient-to-b from-amber-200 to-amber-400'],
            'orange' => ['border' => 'border-orange-500', 'btn' => 'bg-orange-400 hover:bg-orange-500', 'hex_bg' => 'bg-gradient-to-b from-orange-200 to-orange-400'],
            'rose' => ['border' => 'border-rose-500', 'btn' => 'bg-rose-400 hover:bg-rose-500', 'hex_bg' => 'bg-gradient-to-b from-rose-200 to-rose-400'],
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

        $mappedCategories = $categories->values()->map(function($category, $index) use ($colors, $colorMap, $emojis) {
            $colorKey = $colors[$index % count($colors)];
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
            
            return (object)[
                'category' => $category,
                'color' => $color,
                'emoji' => $emoji,
                'totalLevels' => $totalLevels,
                'doneLevels' => $doneLevels,
                'progressPercent' => $progressPercent,
            ];
        });
        
        $hexRows = [];
        $isTwo = true;
        $idx = 0;
        while($idx < $mappedCategories->count()) {
            $take = $isTwo ? 2 : 3;
            
            $rowItems = [];
            foreach($mappedCategories->slice($idx, $take) as $item) {
                $rowItems[] = $item;
            }
            
            // Pad the row with null placeholders if it's the last row and has missing items.
            // This guarantees the flex layout always maintains the correct honeycomb alignment.
            if (count($rowItems) == 1 && $take == 3) {
                // If it's a 3-item row with only 1 item, center it by padding both sides
                $rowItems = [null, $rowItems[0], null];
            } else {
                while(count($rowItems) < $take) {
                    $rowItems[] = null;
                }
            }
            
            $hexRows[] = $rowItems;
            $idx += $take;
            $isTwo = !$isTwo;
        }
    @endphp

    <!-- Mobile Categories View (Hexagon Layout) -->
    <div class="block sm:hidden pb-12 mt-2">
        <div class="flex flex-col items-center">
            @forelse($hexRows as $rowIndex => $row)
                <div class="flex justify-center gap-3 relative" style="z-index: {{ 20 - $rowIndex }}; {{ $rowIndex > 0 ? 'margin-top: -20px;' : '' }}">
                    @foreach($row as $item)
                        @if($item)
                            <div style="filter: drop-shadow(0 4px 6px rgba(0,0,0,0.15));">
                                <a href="{{ route('category.levels', $item->category->slug) }}" 
                                   class="relative flex flex-col items-center justify-center text-center text-slate-800 decoration-none transition-transform active:scale-95 w-[110px] h-[124px]"
                                   style="-webkit-mask-image: url(&quot;data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130 146'%3E%3Cpolygon points='65,10 120,41.5 120,104.5 65,136 10,104.5 10,41.5' fill='black' stroke='black' stroke-width='22' stroke-linejoin='round'/%3E%3C/svg%3E&quot;); -webkit-mask-size: 100% 100%; mask-image: url(&quot;data:image/svg+xml;charset=utf-8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 130 146'%3E%3Cpolygon points='65,10 120,41.5 120,104.5 65,136 10,104.5 10,41.5' fill='black' stroke='black' stroke-width='22' stroke-linejoin='round'/%3E%3C/svg%3E&quot;); mask-size: 100% 100%;">
                                    
                                    <!-- Background -->
                                    <div class="absolute inset-0 {{ $item->color['hex_bg'] }} opacity-90 transition-opacity"></div>
                                    <div class="absolute inset-0 bg-gradient-to-b from-white/40 to-transparent"></div>
                                    
                                    <!-- Inner Content -->
                                    <div class="relative z-10 flex flex-col items-center w-full px-2 pt-2">
                                        <div class="text-3xl mb-1 filter drop-shadow-md">{{ $item->emoji }}</div>
                                        <h3 class="font-bold text-[11px] leading-tight text-slate-900 mb-1 line-clamp-2 w-full">{{ $item->category->name }}</h3>
                                        
                                        <!-- Progress -->
                                        <div class="w-10 h-1.5 bg-white/60 rounded-full overflow-hidden mt-1 shadow-inner">
                                            <div class="h-full {{ $item->color['btn'] }}" style="width: {{ $item->progressPercent }}%"></div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        @else
                            <div class="w-[110px] h-[124px] opacity-0 pointer-events-none"></div>
                        @endif
                    @endforeach
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl nb p-6 w-full">
                    <div class="text-5xl mb-2">😅</div>
                    <h3 class="font-extrabold text-lg">{{ __('Sorry, no subjects found!') }}</h3>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Desktop Categories View -->
    <div class="hidden sm:grid sm:grid-cols-2 lg:grid-cols-3 gap-4 pb-12">
        @forelse($mappedCategories as $item)
            <a href="{{ route('category.levels', $item->category->slug) }}" class="cat-card text-left bg-white rounded-3xl p-4 nb hover:-translate-y-1 active:translate-y-0 transition-transform border-l-[6px] {{ $item->color['border'] }} decoration-none text-slate-800 flex flex-col justify-between">
                <div>
                    <div class="text-4xl mb-2">{{ $item->emoji }}</div>
                    <h3 class="font-extrabold text-base leading-tight mb-2 text-slate-900">{{ $item->category->name }}</h3>
                    <p class="text-xs text-slate-500 line-clamp-2 mb-3">{{ $item->category->description }}</p>
                </div>
                <div>
                    <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden mb-1">
                        <div class="h-full {{ $item->color['btn'] }}" style="width: {{ $item->progressPercent }}%"></div>
                    </div>
                    <p class="text-[11px] font-extrabold text-slate-500 mb-3">
                        {{ $item->doneLevels }}/{{ $item->totalLevels }} {{ __('Levels') }}
                    </p>
                    <span class="inline-block w-full text-center py-2 rounded-xl {{ $item->color['btn'] }} text-white text-xs font-extrabold nb-sm">
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
