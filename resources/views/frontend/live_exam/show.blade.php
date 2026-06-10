@extends('frontend.layouts.master')
@section('title', $exam->title)

@section('content')
    <!-- Kid-Friendly Header Block -->
    <header class="rounded-3xl bg-gradient-to-br from-indigo-300 to-purple-500 nb p-5 text-white mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('live-exams.index') }}" class="w-10 h-10 rounded-2xl bg-white text-slate-800 text-xl flex items-center justify-center nb-sm hover:-translate-y-0.5 active:translate-y-0.5 transition decoration-none">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs opacity-90 font-extrabold text-white mb-0">পরীক্ষার বিবরণ</p>
                <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">
                    {{ $exam->title }}
                </h1>
            </div>
        </div>
    </header>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 pb-12">
        <!-- Details Column -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-5">
                <h2 class="text-base font-extrabold text-slate-800 mb-3 border-bottom pb-2 font-sans">📋 পরীক্ষার বিবরণ ও নিয়মাবলী</h2>
                <p class="text-xs text-slate-500 leading-relaxed font-sans mb-4">{{ $exam->description }}</p>

                <div class="bg-sky-50 border-2 border-slate-900 rounded-2xl p-4">
                    <h3 class="text-xs font-extrabold text-sky-800 mb-2 flex items-center gap-1.5 font-sans">
                        <i class="fa-solid fa-circle-info text-sm"></i> গুরুত্বপূর্ণ নিয়মাবলী:
                    </h3>
                    <ul class="text-xs text-slate-600 font-extrabold space-y-1.5 list-disc pl-4 font-sans">
                        <li>পরীক্ষা শুরু করার পর নির্দিষ্ট সময়ের মধ্যে শেষ করতে হবে।</li>
                        <li>একবার সাবমিট করলে আর পরিবর্তন করা যাবে না।</li>
                        <li>পরীক্ষা চলাকালীন ট্যাব পরিবর্তন করা যাবে না, অন্যথায় অটো-সাবমিট হয়ে যাবে।</li>
                    </ul>
                </div>

                @if(session('error'))
                    <div class="mt-4 bg-rose-100 border-2 border-slate-900 text-rose-850 px-4 py-3 rounded-2xl font-extrabold text-xs">
                        {{ session('error') }}
                    </div>
                @endif
            </div>
        </div>

        <!-- Schedule & CTA Button Column -->
        <div>
            <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-5 text-center flex flex-col justify-between h-full">
                <div>
                    <div class="text-4xl mb-3">🕒</div>
                    <h3 class="text-base font-extrabold text-slate-800 mb-4 border-bottom pb-2 font-sans">সময়সূচী</h3>
                    
                    <div class="space-y-3 text-left">
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-2.5">
                            <span class="text-[10px] font-extrabold text-slate-400 block mb-0.5 font-sans">শুরু:</span>
                            <span class="text-xs font-extrabold text-slate-700 font-sans"><i class="fa-solid fa-calendar-check me-1 text-purple-500"></i> {{ $exam->start_time->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-2.5">
                            <span class="text-[10px] font-extrabold text-slate-400 block mb-0.5 font-sans">শেষ:</span>
                            <span class="text-xs font-extrabold text-slate-700 font-sans"><i class="fa-solid fa-calendar-xmark me-1 text-purple-500"></i> {{ $exam->end_time->format('d M Y, h:i A') }}</span>
                        </div>
                        <div class="bg-slate-50 rounded-xl border border-slate-200 p-2.5">
                            <span class="text-[10px] font-extrabold text-slate-400 block mb-0.5 font-sans">সময়:</span>
                            <span class="text-xs font-extrabold text-slate-700 font-sans"><i class="fa-solid fa-hourglass-half me-1 text-purple-500"></i> {{ $exam->duration_minutes }} মিনিট</span>
                        </div>
                    </div>
                </div>

                <div class="mt-6">
                    @php
                        $now = now();
                        $hasAttempted = \App\Models\LiveExamAttempt::where('user_id', auth()->id())->where('live_exam_id', $exam->id)->exists();
                    @endphp

                    @if($hasAttempted)
                        <div class="bg-amber-100 border-2 border-slate-900 rounded-2xl p-3 text-xs font-extrabold text-amber-850 font-sans">
                            <i class="fa-solid fa-circle-exclamation text-sm me-1"></i> তুমি ইতিমধ্যে এই পরীক্ষায় অংশ নিয়েছ।
                        </div>
                    @elseif($now->isBefore($exam->start_time))
                         <div class="bg-sky-50 border-2 border-slate-900 rounded-2xl p-3">
                             <div class="text-[10px] font-extrabold text-slate-500 mb-1 font-sans">পরীক্ষা শুরু হতে বাকি:</div>
                             <div id="exam-countdown" class="text-xl font-extrabold text-sky-700 font-sans leading-none">--:--:--</div>
                         </div>
                         <script>
                            document.addEventListener('DOMContentLoaded', function() {
                                const startTime = new Date('{{ $exam->start_time->format("Y-m-d\TH:i:s") }}').getTime();
                                const countdownDisplay = document.getElementById('exam-countdown');
                                
                                const x = setInterval(function() {
                                    const now = new Date().getTime();
                                    const distance = startTime - now;
                                    
                                    if (distance < 0) {
                                        clearInterval(x);
                                        countdownDisplay.textContent = "00:00:00";
                                        setTimeout(() => window.location.reload(), 2000);
                                        return;
                                    }
                                    
                                    const days = Math.floor(distance / (1000 * 60 * 60 * 24));
                                    const hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                                    const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    const seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    
                                    let displayText = "";
                                    if(days > 0) displayText += days + "d ";
                                    displayText += (hours < 10 ? "0"+hours : hours) + ":" + (minutes < 10 ? "0"+minutes : minutes) + ":" + (seconds < 10 ? "0"+seconds : seconds);
                                    
                                    countdownDisplay.textContent = displayText;
                                }, 1000);
                            });
                         </script>
                    @elseif($now->isAfter($exam->end_time))
                         <button class="w-full text-center py-2.5 rounded-xl bg-rose-200 border-2 border-slate-900 text-rose-500 font-extrabold text-xs cursor-not-allowed" disabled>
                             <i class="fa-solid fa-circle-xmark me-1"></i> সময় শেষ
                         </button>
                    @else
                        <a href="{{ route('live-exams.join', $exam->id) }}" class="inline-block w-full text-center py-3 rounded-xl bg-amber-350 hover:bg-amber-400 text-slate-900 font-extrabold text-sm decoration-none border-2 border-slate-900 shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none pulse-animation">
                            পরীক্ষা শুরু করো <i class="fa-solid fa-play ms-1"></i>
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
