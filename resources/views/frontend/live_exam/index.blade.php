@extends('frontend.layouts.master')
@section('title', 'লাইভ কুইজ পরীক্ষা সমূহ')

@section('content')
    <!-- Kid-Friendly Header Block -->
    <header class="rounded-3xl bg-gradient-to-br from-indigo-300 to-purple-500 nb p-5 text-white mb-6">
        <div class="flex items-center gap-3">
            <div class="w-14 h-14 rounded-2xl bg-white text-3xl flex items-center justify-center nb-sm shrink-0">
                🏆
            </div>
            <div>
                <p class="text-xs opacity-90 font-extrabold text-white mb-0">মেধা যাচাই লড়াকু</p>
                <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">
                    লাইভ কুইজ পরীক্ষা সমূহ
                </h1>
            </div>
        </div>
    </header>

    <div class="text-center max-w-lg mx-auto mb-6">
        <h2 class="text-lg font-extrabold text-slate-800 mb-1">আসন্ন এবং চলমান পরীক্ষাসমূহ</h2>
        <p class="text-xs text-slate-500 font-extrabold">সরাসরি অংশ নাও, ভালো স্কোর করো এবং বিশেষ জিনিয়াস মেডেল জেতো! 🌟</p>
    </div>

    @if(session('error'))
        <div class="mb-4 bg-rose-100 border-2 border-slate-900 text-rose-800 px-4 py-3 rounded-2xl font-extrabold text-xs">
            {{ session('error') }}
        </div>
    @endif
    @if(session('success'))
        <div class="mb-4 bg-emerald-100 border-2 border-slate-900 text-emerald-800 px-4 py-3 rounded-2xl font-extrabold text-xs">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-12">
        @forelse($activeExams as $exam)
            <div class="bg-white rounded-3xl p-5 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] hover:-translate-y-1 transition-transform flex flex-col justify-between">
                <div>
                    <div class="text-4xl mb-3 text-center">📝</div>
                    <h3 class="font-extrabold text-base text-slate-900 text-center mb-2 font-sans">{{ $exam->title }}</h3>
                    <p class="text-xs text-slate-500 text-center line-clamp-3 mb-4 font-sans">{{ $exam->description ?? 'কোনো বিবরণ দেওয়া নেই।' }}</p>
                    
                    <div class="bg-slate-50 rounded-2xl border-2 border-slate-200 p-3 mb-4 space-y-2">
                        <div class="flex items-center gap-2 text-xs text-slate-600 font-extrabold font-sans">
                            <i class="fa-solid fa-calendar-alt text-purple-500 text-sm w-4"></i>
                            <span>{{ $exam->start_time->format('d M, Y') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-600 font-extrabold font-sans">
                            <i class="fa-solid fa-clock text-purple-500 text-sm w-4"></i>
                            <span>{{ $exam->start_time->format('h:i A') }} - {{ $exam->end_time->format('h:i A') }}</span>
                        </div>
                        <div class="flex items-center gap-2 text-xs text-slate-600 font-extrabold font-sans">
                            <i class="fa-solid fa-hourglass-half text-purple-500 text-sm w-4"></i>
                            <span>সময়: {{ $exam->duration_minutes }} মিনিট</span>
                        </div>
                    </div>
                </div>

                <div>
                    @php
                        $hasAttempted = \App\Models\LiveExamAttempt::where('user_id', auth()->id())->where('live_exam_id', $exam->id)->exists();
                        $now = now();
                    @endphp

                    @if($hasAttempted || $now->isAfter($exam->end_time))
                        <a href="{{ route('live-exams.results', $exam->id) }}" class="inline-block w-full text-center py-2.5 rounded-xl bg-emerald-400 hover:bg-emerald-500 text-white font-extrabold text-xs decoration-none border-2 border-slate-900 shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none">
                            ফলাফল দেখুন <i class="fa-solid fa-trophy ms-1"></i>
                        </a>
                    @elseif($now->isBefore($exam->start_time))
                        <a href="{{ route('live-exams.show', $exam->id) }}" class="inline-block w-full text-center py-2.5 rounded-xl bg-slate-200 hover:bg-slate-300 text-slate-600 font-extrabold text-xs decoration-none border-2 border-slate-900 shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none">
                            শুরু হয়নি (বিস্তারিত)
                        </a>
                    @else
                        <a href="{{ route('live-exams.show', $exam->id) }}" class="inline-block w-full text-center py-2.5 rounded-xl bg-amber-300 hover:bg-amber-400 text-slate-900 font-extrabold text-xs decoration-none border-2 border-slate-900 shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none pulse-animation">
                            বিস্তারিত দেখুন <i class="fa-solid fa-circle-play ms-1"></i>
                        </a>
                    @endif
                </div>
            </div>
        @empty
            <div class="col-span-full bg-white rounded-3xl p-8 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] text-center">
                <div class="text-5xl mb-3">📭</div>
                <h3 class="font-extrabold text-base text-slate-800 mb-1">বর্তমানে কোনো লাইভ পরীক্ষা নেই।</h3>
                <p class="text-xs text-slate-400 font-bold">নতুন পরীক্ষার আপডেটের জন্য চোখ রাখো!</p>
            </div>
        @endforelse
    </div>
@endsection
