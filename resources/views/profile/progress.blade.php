@extends('frontend.layouts.master')
@section('title', __('Genius Kids - My Progress'))

@section('content')
    <!-- Profile Header Banner (Gamified) -->
    <header class="rounded-3xl bg-gradient-to-br from-orange-300 to-rose-400 nb p-5 text-white mb-6 text-center relative">
        <!-- Edit Profile Settings Button -->
        <a href="{{ route('profile.edit') }}" class="absolute top-4 right-4 text-white hover:text-orange-100 text-xl decoration-none" title="{{ __('Update Profile') }}">
            <i class="fa-solid fa-user-gear"></i>
        </a>
        
        <!-- Interactive Avatar Display -->
        <button id="avatarBtn" class="avatar-display w-24 h-24 mx-auto rounded-3xl bg-white text-6xl flex items-center justify-center nb-sm mb-3 hover:-translate-y-1 transition outline-none cursor-pointer">
            {{ session('avatar_emoji', $user->avatar_emoji ?? '🐼') }}
        </button>
        <h1 class="text-2xl font-extrabold text-white mb-1 font-sans">{{ $user->name }}</h1>
        @php
            $completedCount = $progressStats['total_completed_levels'];
            if ($completedCount >= 10) {
                $rank = __('Senior Genius 👑');
            } elseif ($completedCount >= 5) {
                $rank = __('Junior Genius 🌟');
            } else {
                $rank = __('Novice Genius 🌱');
            }
        @endphp
        <p class="text-sm opacity-95 font-extrabold text-white mb-0 uppercase tracking-wide font-sans">
            {{ __('Level') }} {{ $completedCount }} • {{ $rank }}
        </p>
    </header>

    <!-- Interactive Avatar Picker Slider -->
    <h2 class="font-extrabold mb-2 text-xs text-slate-500 font-sans">{{ __('Change Your Avatar') }}</h2>
    <div class="flex gap-3 mb-6 overflow-x-auto no-scrollbar pb-2">
        @php
            $avatars = ['🐼','🦁','🐱','🐰','🦊','🐵','🐯','🐧'];
        @endphp
        @foreach($avatars as $av)
            <button data-av="{{ $av }}" class="av-pick shrink-0 w-14 h-14 rounded-2xl bg-white border-2 border-slate-900 text-3xl flex items-center justify-center shadow-[2px_2px_0px_#0f172a] hover:-translate-y-0.5 transition cursor-pointer active:translate-y-0.5 outline-none">
                {{ $av }}
            </button>
        @endforeach
    </div>

    <!-- User Metrics Stats Grid -->
    <div class="grid grid-cols-3 gap-3 mb-6">
        <div class="bg-sky-100 rounded-2xl nb-sm p-3 text-center">
            <p class="text-2xl font-extrabold text-sky-600 mb-0 font-sans">{{ $quizAttempts->total() }}</p>
            <p class="text-[10px] font-extrabold text-slate-650 mt-1 mb-0">{{ __('Quiz') }}</p>
        </div>
        <div class="bg-amber-100 rounded-2xl nb-sm p-3 text-center">
            <p class="text-2xl font-extrabold text-amber-600 mb-0 font-sans">{{ $user->current_streak }}🔥</p>
            <p class="text-[10px] font-extrabold text-slate-650 mt-1 mb-0">{{ __('Streak') }}</p>
        </div>
        <div class="bg-emerald-100 rounded-2xl nb-sm p-3 text-center">
            <p class="text-2xl font-extrabold text-emerald-600 mb-0 font-sans">{{ $quizAttempts->sum('score') * 10 }}</p>
            <p class="text-[10px] font-extrabold text-slate-650 mt-1 mb-0">XP</p>
        </div>
    </div>

    <!-- Achievements Badge Grid -->
    <h2 class="text-lg font-extrabold mb-4 flex items-center gap-2 text-slate-800">🎖️ {{ __('Achievement Badges') }}</h2>
    <div class="grid grid-cols-2 gap-4 mb-6">
        @php
            $badges = [
                [
                    'emoji' => '🔥', 
                    'name' => config('quiz.milestones.streak_bronze') . ' ' . __('Day Streak'), 
                    'unlocked' => $user->current_streak >= config('quiz.milestones.streak_bronze'),
                    'desc' => __('Regular Learner')
                ],
                [
                    'emoji' => '🎯', 
                    'name' => __('First Quiz Victory'), 
                    'unlocked' => $quizAttempts->where('passed', true)->count() > 0,
                    'desc' => __('Pass a quiz by answering')
                ],
                [
                    'emoji' => '💯', 
                    'name' => __('Century Quiz'), 
                    'unlocked' => $quizAttempts->total() >= config('quiz.milestones.quiz_century'),
                    'desc' => __('Completed 100 quizzes')
                ],
                [
                    'emoji' => '👑', 
                    'name' => __('Level Master'), 
                    'unlocked' => $completedCount >= config('quiz.milestones.level_master'),
                    'desc' => __('Completed 10+ levels')
                ],
            ];
        @endphp

        @foreach($badges as $badge)
            <div class="bg-white rounded-2xl nb-sm p-4 text-center transition hover:-translate-y-0.5 {{ $badge['unlocked'] ? '' : 'opacity-40 grayscale' }}">
                <div class="text-4xl mb-2">{{ $badge['emoji'] }}</div>
                <p class="text-xs font-extrabold text-slate-800 mb-0.5 font-sans">{{ $badge['name'] }}</p>
                <p class="text-[9px] font-extrabold text-slate-450 mb-2 font-sans">{{ $badge['desc'] }}</p>
                <span class="px-2 py-0.5 rounded-lg text-[9px] font-extrabold {{ $badge['unlocked'] ? 'bg-emerald-100 text-emerald-700' : 'bg-slate-100 text-slate-400' }}">
                    {{ $badge['unlocked'] ? __('✅ Unlocked') : __('🔒 Locked') }}
                </span>
            </div>
        @endforeach
    </div>

    <!-- Layout Columns for History & Subject-wise Progress -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-12">
        <!-- Quiz History Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-4 h-full">
                <h3 class="text-base font-extrabold text-slate-800 mb-4 border-bottom pb-2 font-sans"><i class="fa-solid fa-clock-rotate-left me-1"></i> {{ __('Quiz History') }}</h3>
                
                <div class="space-y-3">
                    @forelse($quizAttempts as $attempt)
                        <div class="bg-slate-50 rounded-2xl border-2 border-slate-900 p-3 flex justify-between items-center gap-3">
                            <div class="flex-1">
                                <div class="font-extrabold text-sm text-slate-800 font-sans mb-1">
                                    {{ $attempt->category->name ?? 'Category' }}
                                </div>
                                <span class="px-2 py-0.5 text-[10px] rounded-lg bg-sky-100 border border-slate-900 font-extrabold text-sky-700">
                                    {{ $attempt->level->name ?? 'Level' }}
                                </span>
                                <div class="text-[9px] font-extrabold text-slate-400 mt-1.5 font-sans">
                                    {{ $attempt->created_at->format('d M, Y • h:i A') }}
                                </div>
                            </div>
                            <div class="text-right shrink-0">
                                <div class="font-extrabold text-base {{ $attempt->passed ? 'text-emerald-500' : 'text-rose-500' }} font-sans mb-1.5">
                                    {{ $attempt->score }} / {{ $attempt->total_questions }}
                                </div>
                                <span class="px-2 py-1 rounded-xl text-[10px] border-2 border-slate-900 font-extrabold text-white {{ $attempt->passed ? 'bg-emerald-500' : 'bg-rose-400' }}">
                                    {{ $attempt->passed ? __('Passed') : __('Failed') }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="text-4xl mb-2">🐢</div>
                            <p class="text-slate-450 font-bold mb-0 font-sans">{{ __('You have not played any quizzes yet!') }}</p>
                        </div>
                    @endforelse
                </div>

                <!-- Custom Pagination buttons -->
                @if ($quizAttempts->hasPages())
                    <div class="flex justify-center mt-6">
                        <nav class="flex items-center gap-1.5" role="navigation" aria-label="Pagination">
                            {{-- Previous Page Link --}}
                            @if ($quizAttempts->onFirstPage())
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border-2 border-slate-400 text-slate-400 flex items-center justify-center cursor-not-allowed">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </span>
                            @else
                                <a href="{{ $quizAttempts->previousPageUrl() }}" class="w-8 h-8 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                                    <i class="fa-solid fa-chevron-left text-[10px]"></i>
                                </a>
                            @endif

                            {{-- Active/Info indicators --}}
                            <span class="text-xs font-extrabold text-slate-650 font-sans mx-1">
                                {{ __('Page') }} {{ $quizAttempts->currentPage() }} / {{ $quizAttempts->lastPage() }}
                            </span>

                            {{-- Next Page Link --}}
                            @if ($quizAttempts->hasMorePages())
                                <a href="{{ $quizAttempts->nextPageUrl() }}" class="w-8 h-8 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </a>
                            @else
                                <span class="w-8 h-8 rounded-xl bg-slate-100 border-2 border-slate-400 text-slate-400 flex items-center justify-center cursor-not-allowed">
                                    <i class="fa-solid fa-chevron-right text-[10px]"></i>
                                </span>
                            @endif
                        </nav>
                    </div>
                @endif
            </div>
        </div>

        <!-- Subject Progress List Column -->
        <div>
            <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-4 h-full">
                <h3 class="text-base font-extrabold text-slate-800 mb-4 border-bottom pb-2 font-sans"><i class="fa-solid fa-chart-pie me-1"></i> {{ __('Subject-wise Progress') }}</h3>
                
                <div class="space-y-4 overflow-y-auto no-scrollbar" style="max-height: 480px;">
                    @forelse($userProgress->groupBy('category_id') as $categoryId => $progresses)
                        <div class="bg-slate-50 border-2 border-slate-900 rounded-2xl p-3">
                            <h4 class="text-xs font-extrabold text-slate-500 uppercase tracking-wider mb-2 font-sans">
                                {{ $progresses->first()->category->name ?? 'Category' }}
                            </h4>
                            <div class="space-y-2">
                                @foreach($progresses as $progress)
                                    <div class="flex justify-between items-center bg-white p-2 rounded-xl border border-slate-200">
                                        <span class="text-xs font-bold text-slate-750 font-sans">{{ $progress->level->name ?? 'Level' }}</span>
                                        @if($progress->status == 'completed')
                                            <span class="px-2 py-0.5 rounded bg-emerald-100 text-emerald-800 border border-emerald-300 text-[9px] font-extrabold">
                                                {{ __('Completed') }}
                                            </span>
                                        @elseif($progress->status == 'active')
                                            <span class="px-2 py-0.5 rounded bg-amber-100 text-amber-800 border border-amber-300 text-[9px] font-extrabold">
                                                {{ __('Ongoing') }}
                                            </span>
                                        @else
                                            <span class="px-2 py-0.5 rounded bg-slate-100 text-slate-400 border border-slate-300 text-[9px] font-extrabold">
                                                {{ __('Locked') }}
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <p class="text-slate-450 font-bold mb-0 font-sans">{{ __('No progress history available!') }}</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- Client-side persistent avatar script -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const avatarBtn = document.getElementById('avatarBtn');
            const pickerButtons = document.querySelectorAll('.av-pick');

            // Set current active avatar style
            const updateActiveAvatarPickStyle = (selectedAvatar) => {
                pickerButtons.forEach(btn => {
                    const av = btn.getAttribute('data-av');
                    if (av === selectedAvatar) {
                        btn.className = "av-pick shrink-0 w-14 h-14 rounded-2xl bg-amber-300 border-2 border-slate-900 text-3xl flex items-center justify-center shadow-[2px_2px_0px_#0f172a] -translate-y-0.5 transition outline-none";
                    } else {
                        btn.className = "av-pick shrink-0 w-14 h-14 rounded-2xl bg-white border-2 border-slate-900 text-3xl flex items-center justify-center shadow-[2px_2px_0px_#0f172a] hover:-translate-y-0.5 transition cursor-pointer active:translate-y-0.5 outline-none";
                    }
                });
            };

            // Read currently saved avatar
            const currentAv = '{{ session('avatar_emoji', $user->avatar_emoji ?? '🐼') }}';
            localStorage.setItem('user_avatar', currentAv);
            updateActiveAvatarPickStyle(currentAv);

            // Handler to click avatar pickers
            pickerButtons.forEach(btn => {
                btn.addEventListener('click', () => {
                    const chosenEmoji = btn.getAttribute('data-av');
                    
                    // Save to local storage
                    localStorage.setItem('user_avatar', chosenEmoji);
                    
                    // Update all avatar nodes dynamically on page
                    document.querySelectorAll('.avatar-display').forEach(el => {
                        el.textContent = chosenEmoji;
                    });

                    updateActiveAvatarPickStyle(chosenEmoji);

                    // Sync avatar to Laravel session and database via AJAX call
                    fetch('{{ route('profile.avatar.update') }}', {
                        method: 'PATCH',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({ avatar_emoji: chosenEmoji })
                    }).catch(err => console.error('Failed to sync avatar with session:', err));
                });
            });
        });
    </script>
@endsection
