@extends('frontend.layouts.master')
@section('title', 'ফলাফল - ' . $exam->title)

@section('content')
    <!-- Kid-Friendly Header Block -->
    <header class="rounded-3xl bg-gradient-to-br from-indigo-300 to-purple-500 nb p-5 text-white mb-6">
        <div class="flex items-center gap-3">
            <a href="{{ route('live-exams.index') }}" class="w-10 h-10 rounded-2xl bg-white text-slate-800 text-xl flex items-center justify-center nb-sm hover:-translate-y-0.5 active:translate-y-0.5 transition decoration-none shrink-0">
                <i class="fa-solid fa-arrow-left"></i>
            </a>
            <div>
                <p class="text-xs opacity-90 font-extrabold text-white mb-0">পরীক্ষার ফলাফল</p>
                <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">
                    লিডারবোর্ড ও ফলাফল
                </h1>
            </div>
        </div>
    </header>

    <div class="text-center max-w-lg mx-auto mb-6">
        <h2 class="text-lg font-extrabold text-slate-800 mb-1 font-sans">{{ $exam->title }}</h2>
        <p class="text-xs text-slate-500 font-extrabold">মোট অংশগ্রহণকারী: <span class="text-indigo-600 font-sans font-extrabold">{{ $attempts->total() }} জন</span></p>
    </div>

    @if(session('success'))
        <div class="mb-6 bg-emerald-100 border-2 border-slate-900 text-emerald-850 px-4 py-3.5 rounded-2xl font-extrabold text-center text-xs">
            <i class="fa-solid fa-circle-check text-sm me-1"></i> {{ session('success') }}
        </div>
    @endif

    <div class="max-w-4xl mx-auto pb-12">
        <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-5 sm:p-6">
            
            <!-- Current User Score Highlight Panel -->
            @if($hasAttempted && auth()->check())
                @php
                    $myAttempt = $attempts->firstWhere('user_id', auth()->id());
                @endphp
                @if($myAttempt)
                    <div class="bg-indigo-50 border-3 border-slate-900 rounded-2xl p-4 text-center mb-6 shadow-[3px_3px_0px_#0f172a]">
                        <h4 class="text-sm font-extrabold text-slate-700 mb-1 font-sans">তোমার প্রাপ্ত নম্বর</h4>
                        <div class="text-3xl font-extrabold text-indigo-600 font-sans tracking-tight mb-1">
                            {{ round($myAttempt->score) }}
                        </div>
                        <p class="text-xs text-slate-500 font-extrabold mb-0">অভিনন্দন! তুমি সফলভাবে কুইজ প্রতিযোগিতায় অংশ নিয়েছ। 🥳</p>
                    </div>
                @else
                    <div class="bg-sky-50 border-2 border-slate-900 rounded-2xl p-3 text-center mb-6 text-xs font-extrabold text-slate-600">
                        <i class="fa-solid fa-circle-notch fa-spin text-sm me-1.5 text-sky-600"></i> তোমার প্রাপ্ত নম্বর প্রসেসিং হচ্ছে অথবা এই তালিকায় নেই।
                    </div>
                @endif
            @endif

            <h3 class="text-base font-extrabold text-slate-800 mb-4 pb-2 border-bottom flex items-center justify-center gap-1.5 font-sans">
                🏆 লিডারবোর্ড (সেরা ফলাফল)
            </h3>

            <!-- Leaderboard Table -->
            <div class="overflow-x-auto no-scrollbar">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b-3 border-slate-900 text-slate-500 font-extrabold text-xs font-sans uppercase">
                            <th class="pb-3 text-center w-20">র‍্যাংক</th>
                            <th class="pb-3 pl-4">নাম</th>
                            <th class="pb-3 text-center w-24">নম্বর</th>
                            <th class="pb-3 text-center w-36">সময়</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200">
                        @forelse($attempts as $index => $attempt)
                            @php
                                $isCurrentUser = auth()->check() && auth()->id() === $attempt->user_id;
                                $rankNumber = $loop->iteration + ($attempts->currentPage() - 1) * $attempts->perPage();
                            @endphp
                            <tr class="font-sans text-xs {{ $isCurrentUser ? 'bg-amber-100 font-extrabold' : '' }}">
                                <td class="py-4 text-center">
                                    @if($rankNumber == 1)
                                        <span class="text-2xl">🥇</span>
                                    @elseif($rankNumber == 2)
                                        <span class="text-2xl">🥈</span>
                                    @elseif($rankNumber == 3)
                                        <span class="text-2xl">🥉</span>
                                    @else
                                        <span class="inline-block px-2.5 py-0.5 rounded-full bg-slate-100 border border-slate-300 font-extrabold text-[10px] text-slate-650">
                                            {{ $rankNumber }}
                                        </span>
                                    @endif
                                </td>
                                <td class="py-4 pl-4 font-extrabold text-slate-800 text-sm">
                                    <span class="flex items-center gap-1.5">
                                        {{ $attempt->user->name ?? 'অজ্ঞাত' }}
                                        @if($isCurrentUser)
                                            <span class="px-1.5 py-0.5 rounded-md bg-emerald-500 border border-slate-900 text-white text-[9px] font-extrabold">তুমি</span>
                                        @endif
                                    </span>
                                </td>
                                <td class="py-4 text-center font-extrabold text-sm text-indigo-600">
                                    {{ round($attempt->score) }}
                                </td>
                                <td class="py-4 text-center text-[10px] text-slate-450 font-bold leading-tight">
                                    <span>{{ $attempt->created_at->format('d M y, h:i A') }}</span>
                                    <span class="block text-[9px] text-slate-400 mt-0.5 font-sans">{{ $attempt->created_at->diffForHumans() }}</span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-12 text-center text-slate-450 font-bold">
                                    <div class="text-4xl mb-2">📥</div>
                                    <span>এখনো কোনো ফলাফল তৈরি হয়নি।</span>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Custom Pagination -->
            @if ($attempts->hasPages())
                <div class="flex justify-center mt-6">
                    <nav class="flex items-center gap-1.5" role="navigation" aria-label="Pagination">
                        {{-- Previous Page Link --}}
                        @if ($attempts->onFirstPage())
                            <span class="w-8 h-8 rounded-xl bg-slate-100 border-2 border-slate-400 text-slate-400 flex items-center justify-center cursor-not-allowed">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </span>
                        @else
                            <a href="{{ $attempts->previousPageUrl() }}" class="w-8 h-8 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                                <i class="fa-solid fa-chevron-left text-[10px]"></i>
                            </a>
                        @endif

                        {{-- Active/Info indicators --}}
                        <span class="text-xs font-extrabold text-slate-650 font-sans mx-1">
                            পেজ {{ $attempts->currentPage() }} / {{ $attempts->lastPage() }}
                        </span>

                        {{-- Next Page Link --}}
                        @if ($attempts->hasMorePages())
                            <a href="{{ $attempts->nextPageUrl() }}" class="w-8 h-8 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
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
@endsection
