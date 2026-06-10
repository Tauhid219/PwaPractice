@extends('frontend.layouts.master')
@section('title', 'জিনিয়াস কিডস - ফলাফল')

@section('content')
    @php
        $total = $totalQuestions;
        $score = $attempt->score;
        $pct = $total > 0 ? round(($score / $total) * 100) : 0;
        $pass = $attempt->passed;
        $xp = $score * 10;
        
        $nextLevel = \App\Models\Level::where('category_id', $category->id)
            ->where('order', '>', $level->order)
            ->orderBy('order', 'asc')
            ->first();
    @endphp

    <div class="text-center pt-6 pb-12 max-w-sm mx-auto">
        <!-- Emoji Badge -->
        <div class="text-7xl mb-4 {{ $pass ? 'bouncy' : '' }}">
            {{ $pass ? '🏆' : '😅' }}
        </div>
        
        <!-- Result Message -->
        <h1 class="text-2xl font-extrabold text-slate-800 mb-1">
            {{ $pass ? 'অভিনন্দন! তুমি পাস করেছো!' : 'ইশ! আরেকটু চেষ্টা করো!' }}
        </h1>
        <p class="text-slate-500 font-bold mb-6">
            {{ $pass ? 'তুমি সত্যিই দারুণ!' : 'হার মেনো না, আবার চেষ্টা করো!' }}
        </p>

        <!-- Stats Grid -->
        <div class="grid grid-cols-3 gap-3 mb-6">
            <div class="bg-white rounded-2xl nb-sm p-3 text-center">
                <p class="text-2xl font-extrabold mb-0 {{ $pass ? 'text-emerald-500' : 'text-rose-500' }}">
                    {{ $score }}/{{ $total }}
                </p>
                <p class="text-[10px] font-extrabold text-slate-500 mt-1 mb-0">সঠিক</p>
            </div>
            <div class="bg-white rounded-2xl nb-sm p-3 text-center">
                <p class="text-2xl font-extrabold text-amber-500 mb-0">+{{ $xp }}</p>
                <p class="text-[10px] font-extrabold text-slate-500 mt-1 mb-0">XP</p>
            </div>
            <div class="bg-white rounded-2xl nb-sm p-3 text-center">
                <!-- Elapsed time calculation (fallback to default if not saved) -->
                <p class="text-2xl font-extrabold text-sky-500 mb-0">
                    {{ $attempt->created_at->diffInSeconds($attempt->updated_at) ?: 25 }}s
                </p>
                <p class="text-[10px] font-extrabold text-slate-500 mt-1 mb-0">সময়</p>
            </div>
        </div>

        <!-- Navigation Buttons -->
        <div class="space-y-3">
            @if($pass)
                @if($nextLevel)
                    <a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $nextLevel->id]) }}" class="w-full py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-base transition-all nb shadow-[3px_3px_0px_#000000] text-center block decoration-none">
                        পরবর্তী লেভেল আনলক করো <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                @else
                    <a href="{{ route('category.levels', $category->slug) }}" class="w-full py-4 rounded-2xl bg-emerald-500 hover:bg-emerald-600 text-white font-extrabold text-base transition-all nb shadow-[3px_3px_0px_#000000] text-center block decoration-none">
                        ক্যাটাগরিতে ফিরে যাও <i class="fa-solid fa-arrow-right ml-1"></i>
                    </a>
                @endif
                <a href="{{ route('quiz.start', ['slug' => $category->slug, 'level' => $level->id]) }}" class="w-full py-4 rounded-2xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 font-extrabold text-base transition-all shadow-[2px_2px_0px_#0f172a] text-center block decoration-none">
                    <i class="fa-solid fa-rotate-right me-1"></i> আবার পরীক্ষা দাও
                </a>
            @else
                <a href="{{ route('quiz.start', ['slug' => $category->slug, 'level' => $level->id]) }}" class="w-full py-4 rounded-2xl bg-rose-400 hover:bg-rose-500 text-white font-extrabold text-base transition-all nb shadow-[3px_3px_0px_#000000] text-center block decoration-none">
                    <i class="fa-solid fa-rotate-right me-1"></i> আবার পরীক্ষা দাও
                </a>
                <a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $level->id]) }}" class="w-full py-4 rounded-2xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 font-extrabold text-base transition-all shadow-[2px_2px_0px_#0f172a] text-center block decoration-none">
                    📖 আবার রিভিশন দাও
                </a>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Play correct/wrong sound logic on load
            @if($pass)
                if (window.sfx) window.sfx.win();
                launchConfetti();
            @else
                if (window.sfx) window.sfx.wrong();
            @endif
        });

        // Confetti Launcher (Vanilla JS Canvas/DOM shower)
        function launchConfetti() {
            const colors = ['#0ea5e9', '#10b981', '#f59e0b', '#f43f5e', '#fb923c', '#a78bfa'];
            for (let i = 0; i < 80; i++) {
                const c = document.createElement('div');
                c.className = 'confetti';
                c.style.left = Math.random() * 100 + 'vw';
                c.style.background = colors[i % colors.length];
                c.style.animation = `fall ${2 + Math.random() * 2}s linear ${Math.random() * 0.6}s forwards`;
                document.body.appendChild(c);
                setTimeout(() => c.remove(), 4500);
            }
        }
    </script>
@endsection
