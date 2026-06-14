@extends('frontend.layouts.master')
@section('title', $category->name . ' - ' . __('Levels'))

@section('content')
    <!-- Category Header Card -->
    @php
        $colors = ['emerald', 'sky', 'amber', 'orange', 'rose'];
        // Pick a color based on category ID to keep it consistent
        $colorKey = $colors[$category->id % count($colors)];
        
        $colorMap = [
            'sky' => ['bg' => 'from-sky-300 to-sky-500', 'btn' => 'bg-sky-400 hover:bg-sky-500', 'btnDark' => 'bg-sky-600'],
            'emerald' => ['bg' => 'from-emerald-300 to-emerald-500', 'btn' => 'bg-emerald-400 hover:bg-emerald-500', 'btnDark' => 'bg-emerald-600'],
            'amber' => ['bg' => 'from-amber-300 to-amber-500', 'btn' => 'bg-amber-400 hover:bg-amber-500', 'btnDark' => 'bg-emerald-600'],
            'orange' => ['bg' => 'from-orange-300 to-orange-500', 'btn' => 'bg-orange-400 hover:bg-orange-500', 'btnDark' => 'bg-orange-600'],
            'rose' => ['bg' => 'from-rose-300 to-rose-500', 'btn' => 'bg-rose-400 hover:bg-rose-500', 'btnDark' => 'bg-rose-600'],
        ];
        $color = $colorMap[$colorKey];
        
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
        $slug = Str::slug($category->slug);
        $emoji = '📚';
        foreach($emojis as $key => $val) {
            if (str_contains($slug, $key)) {
                $emoji = $val;
                break;
            }
        }
        
        $totalLevels = $levels->count();
        $doneLevels = 0;
        if (auth()->check()) {
            $doneLevels = auth()->user()->userProgress()
                ->where('category_id', $category->id)
                ->where('status', 'completed')
                ->count();
        }
    @endphp

    <header class="flex items-center gap-3 mb-6">
        <a href="{{ url('/') }}" class="w-11 h-11 rounded-2xl bg-white nb-sm flex items-center justify-center text-slate-800 hover:text-slate-800 transition decoration-none shrink-0">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div class="flex-1">
            <p class="text-[10px] font-extrabold text-slate-400 mb-0 uppercase tracking-wider">{{ __('Subject') }}</p>
            <h1 class="text-xl font-extrabold text-slate-800 mb-0 font-sans">{{ $emoji }} {{ $category->name }}</h1>
        </div>
        <span class="px-3 py-1.5 rounded-2xl {{ $color['btn'] }} text-white font-extrabold nb-sm text-sm shrink-0">
            {{ $doneLevels }}/{{ $totalLevels }}
        </span>
    </header>

    <!-- Duolingo-style Vertical Path Map -->
    <div class="relative py-8 max-w-sm mx-auto">
        <!-- Dotted Path Line -->
        <div class="absolute inset-y-0 left-1/2 -translate-x-1/2 w-1 path-line"></div>
        
        <div class="relative flex flex-col gap-10">
            @forelse($levels as $index => $level)
                @php
                    $isLocked = true;
                    if ($level->is_free || $level->order === 1) {
                        $isLocked = false;
                    } elseif (auth()->check()) {
                        $progress = \App\Models\UserProgress::where('user_id', auth()->id())
                                        ->where('category_id', $category->id)
                                        ->where('level_id', $level->id)
                                        ->first();
                        if ($progress && $progress->status !== 'locked') {
                            $isLocked = false;
                        }
                    }
                    
                    $isCompleted = false;
                    if (auth()->check()) {
                        $progress = \App\Models\UserProgress::where('user_id', auth()->id())
                                        ->where('category_id', $category->id)
                                        ->where('level_id', $level->id)
                                        ->first();
                        if ($progress && $progress->status === 'completed') {
                            $isCompleted = true;
                        }
                    }
                    
                    $isActive = !$isLocked && !$isCompleted;
                    $alignmentClass = $index % 2 === 0 ? 'justify-start' : 'justify-end';
                @endphp

                <div class="relative flex {{ $alignmentClass }} w-full px-4">
                    @if($isLocked)
                        <!-- Locked Level -->
                        <div class="relative w-24 h-24 rounded-full flex flex-col items-center justify-center font-extrabold border-4 border-slate-900 bg-slate-200 text-slate-400 cursor-not-allowed">
                            <i class="fa-solid fa-lock text-xl"></i>
                            <span class="text-[10px] mt-1">{{ __('Level') }} {{ $level->order }}</span>
                        </div>
                    @else
                        <!-- Unlocked Level -->
                        <button 
                            class="level-node relative w-24 h-24 rounded-full flex flex-col items-center justify-center font-extrabold border-4 border-slate-900 text-white transition-all active:translate-y-1
                            {{ $isCompleted ? 'bg-emerald-400 hover:bg-emerald-500' : 'bg-orange-400 hover:bg-orange-500 bouncy pulse-glow' }}"
                            data-level-id="{{ $level->id }}"
                            data-level-name="{{ $level->name }}"
                            data-study-url="{{ route('level.questions', ['slug' => $category->slug, 'level' => $level->id]) }}"
                            data-quiz-url="{{ route('quiz.start', ['slug' => $category->slug, 'level' => $level->id]) }}"
                        >
                            @if($isCompleted)
                                <i class="fa-solid fa-check text-2xl"></i>
                            @else
                                <i class="fa-solid fa-play text-2xl ms-1"></i>
                            @endif
                            <span class="text-[10px] mt-1">{{ __('Level') }} {{ $level->order }}</span>
                        </button>
                    @endif
                </div>
            @empty
                <div class="text-center py-12 bg-white rounded-3xl nb p-6">
                    <div class="text-5xl mb-2">😅</div>
                    <h3 class="font-extrabold text-lg">{{ __('Sorry, no levels found!') }}</h3>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Playful Neobrutalist Level Options Modal -->
    <div id="lvlPopup" class="hidden fixed inset-0 z-50 bg-black/40 flex items-center justify-center p-4">
        <div class="pop bg-white rounded-3xl nb p-6 w-full max-w-sm text-center">
            <div class="text-5xl mb-2">🎯</div>
            <h3 id="popupTitle" class="font-extrabold text-lg mb-1 text-slate-800">{{ __('Start Level!') }}</h3>
            <p class="text-sm text-slate-500 mb-5">{{ __('What would you like to do now?') }}</p>
            
            <a id="popupStudyBtn" href="#" class="w-full mb-3 py-4 rounded-2xl bg-sky-400 text-white font-extrabold nb-sm hover:-translate-y-1 transition text-center block decoration-none text-base">
                {{ __('📖 I want to read (Study)') }}
            </a>
            <a id="popupQuizBtn" href="#" class="w-full mb-3 py-4 rounded-2xl bg-emerald-500 text-white font-extrabold nb-sm hover:-translate-y-1 transition text-center block decoration-none text-base">
                {{ __('🎮 Play Quiz (Quiz)') }}
            </a>
            
            <button onclick="closePopup()" class="text-slate-500 font-bold text-sm bg-transparent border-0 mt-3 outline-none cursor-pointer">
                {{ __('Cancel') }}
            </button>
        </div>
    </div>

    <script>
        // Modal Trigger scripts
        document.addEventListener('DOMContentLoaded', () => {
            const levelNodes = document.querySelectorAll('.level-node');
            const popup = document.getElementById('lvlPopup');
            const popupTitle = document.getElementById('popupTitle');
            const studyBtn = document.getElementById('popupStudyBtn');
            const quizBtn = document.getElementById('popupQuizBtn');

            levelNodes.forEach(node => {
                node.addEventListener('click', () => {
                    const levelName = node.getAttribute('data-level-name');
                    const studyUrl = node.getAttribute('data-study-url');
                    const quizUrl = node.getAttribute('data-quiz-url');

                    // Set modal contents
                    popupTitle.textContent = `${levelName} ${'{{ __('Start Level!') }}'.replace('Level!', '')}`;
                    studyBtn.setAttribute('href', studyUrl);
                    quizBtn.setAttribute('href', quizUrl);

                    // Show popup
                    popup.classList.remove('hidden');
                });
            });

            // Close popup when clicking outside the inner box
            popup.addEventListener('click', (e) => {
                if (e.target === popup) {
                    closePopup();
                }
            });
        });

        function closePopup() {
            const popup = document.getElementById('lvlPopup');
            if (popup) {
                popup.classList.add('hidden');
            }
        }
    </script>
@endsection
