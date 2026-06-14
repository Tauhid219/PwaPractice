@extends('frontend.layouts.master')
@section('title', $level->name . ' - ' . __('Question & Answer'))

@section('content')
    <!-- Header -->
    <header class="flex items-center gap-3 mb-6">
        <a href="{{ route('category.levels', $category->slug) }}" class="w-11 h-11 rounded-2xl bg-white nb-sm flex items-center justify-center text-slate-800 hover:text-slate-800 transition decoration-none shrink-0">
            <i class="fa-solid fa-arrow-left text-lg"></i>
        </a>
        <div>
            <p class="text-[10px] font-extrabold text-slate-400 mb-0 uppercase tracking-wider">{{ __('Study') }}</p>
            <h1 class="text-xl font-extrabold text-slate-800 mb-0 font-sans">📖 {{ __('Read Aloud') }}</h1>
        </div>
    </header>

    <!-- Reading Progress Tracker -->
    <div class="bg-white rounded-3xl nb-sm p-4 mb-6 text-center">
        <div class="w-full h-4 bg-slate-100 border-2 border-slate-900 rounded-full overflow-hidden">
            <div id="progress-bar" class="h-full bg-sky-400 transition-all duration-300" style="width: 0%;"></div>
        </div>
        <p id="progress-text" class="mt-2 text-sm font-extrabold text-slate-650 mb-0">
            0 / {{ $questions->total() }} {{ __('read') }}
        </p>
    </div>

    <!-- Accordion deck -->
    <div class="space-y-4" id="questionsAccordion">
        @forelse($questions as $index => $question)
            @php
                // Get the primary answer variation to display
                $displayAnswer = explode('|', $question->answer_text)[0];
            @endphp
            <div class="bg-white rounded-2xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] overflow-hidden question-block" data-id="{{ $question->id }}">
                <!-- Header trigger button -->
                <div class="acc-btn w-full p-4 flex items-center gap-3 text-left cursor-pointer select-none" data-id="{{ $question->id }}">
                    <span class="w-8 h-8 rounded-full bg-amber-300 nb-sm flex items-center justify-center font-extrabold text-sm shrink-0">
                        {{ $questions->firstItem() + $index }}
                    </span>
                    <span class="flex-1 font-extrabold text-sm text-slate-800 font-sans pr-2">
                        {{ $question->question_text }}
                    </span>
                    
                    <!-- Read mark checkbox icon -->
                    <i class="fa-solid fa-circle-check text-emerald-500 text-xl hidden read-check me-1" id="check-{{ $question->id }}"></i>

                    <!-- Voice TTS Button -->
                    <button class="speak shrink-0 w-9 h-9 rounded-xl bg-sky-100 border-2 border-slate-900 flex items-center justify-center text-slate-800 hover:bg-sky-200 outline-none" data-text="{{ $question->question_text }} {{ $displayAnswer }}">
                        <i class="fa-solid fa-volume-high text-xs"></i>
                    </button>
                    
                    <!-- Toggle Chevron -->
                    <i class="fa-solid fa-chevron-down arrow transition-transform duration-200 text-slate-500"></i>
                </div>
                
                <!-- Body (Initially hidden except the first one) -->
                <div id="collapse-{{ $question->id }}" class="acc-body {{ $index == 0 ? '' : 'hidden' }} px-4 pb-4 pt-0">
                    <div class="p-3 rounded-xl bg-emerald-50 border-2 border-emerald-300 text-sm font-extrabold text-emerald-800 leading-relaxed font-sans mb-3">
                        <span class="text-emerald-600 block text-xs uppercase mb-1">{{ __('Answer') }}:</span>
                        {{ $displayAnswer }}
                    </div>
                    
                    <div class="text-right">
                        <button class="mark-read-btn px-4 py-2 rounded-xl bg-white hover:bg-emerald-50 border-2 border-slate-900 font-extrabold text-xs text-slate-800 cursor-pointer transition active:translate-y-0.5" data-id="{{ $question->id }}" data-next-id="{{ $index < count($questions)-1 ? $questions[$index+1]->id : '' }}">
                            <i class="fa-solid fa-check me-1 text-emerald-600"></i> {{ __('Mark as read') }}
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-12 bg-white rounded-3xl nb p-6">
                <div class="text-5xl mb-2">😅</div>
                <h3 class="font-extrabold text-lg">{{ __('Sorry, no questions found!') }}</h3>
            </div>
        @endforelse
    </div>

    <!-- Custom Neobrutalist Styled Laravel Pagination -->
    @if ($questions->hasPages())
        <div class="flex justify-center mt-6">
            <nav class="flex items-center gap-1.5" role="navigation" aria-label="Pagination">
                {{-- Previous Page Link --}}
                @if ($questions->onFirstPage())
                    <span class="w-10 h-10 rounded-xl bg-slate-100 border-2 border-slate-400 text-slate-400 flex items-center justify-center cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </span>
                @else
                    <a href="{{ $questions->previousPageUrl() }}" class="w-10 h-10 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                        <i class="fa-solid fa-chevron-left text-xs"></i>
                    </a>
                @endif

                {{-- Pagination Elements --}}
                @foreach ($questions->links()->elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span class="w-10 h-10 rounded-xl border-2 border-transparent text-slate-500 flex items-center justify-center font-bold">
                            {{ $element }}
                        </span>
                    @endif

                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $questions->currentPage())
                                <span class="w-10 h-10 rounded-xl bg-amber-300 border-2 border-slate-900 text-slate-900 font-extrabold flex items-center justify-center shadow-[2px_2px_0px_#0f172a]">
                                    {{ $page }}
                                </span>
                            @else
                                <a href="{{ $url }}" class="w-10 h-10 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 font-extrabold flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach

                {{-- Next Page Link --}}
                @if ($questions->hasMorePages())
                    <a href="{{ $questions->nextPageUrl() }}" class="w-10 h-10 rounded-xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 flex items-center justify-center decoration-none shadow-[2px_2px_0px_#0f172a] active:translate-y-0.5 active:shadow-none">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </a>
                @else
                    <span class="w-10 h-10 rounded-xl bg-slate-100 border-2 border-slate-400 text-slate-400 flex items-center justify-center cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-xs"></i>
                    </span>
                @endif
            </nav>
        </div>
    @endif

    <!-- Quiz CTA Trigger -->
    <div class="mt-8 mb-12 text-center flex flex-col items-center">
        @auth
            <a href="{{ route('quiz.start', ['slug' => $category->slug, 'level' => $level->id]) }}" class="hidden w-full py-4 rounded-3xl bg-orange-500 hover:bg-orange-600 text-white text-base font-extrabold nb shadow-[4px_4px_0px_#0f172a] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none transition decoration-none mb-4" id="quiz-btn">
                {{ __('Play Quiz') }} <i class="fa-solid fa-arrow-right ms-2 bouncy inline-block"></i>
            </a>
        @else
            <a href="{{ route('login') }}" class="hidden w-full py-4 rounded-3xl bg-orange-500 hover:bg-orange-600 text-white text-base font-extrabold nb shadow-[4px_4px_0px_#0f172a] hover:-translate-y-0.5 active:translate-y-0.5 active:shadow-none transition decoration-none mb-4" id="quiz-btn">
                {{ __('Play Quiz') }} <i class="fa-solid fa-arrow-right ms-2 bouncy inline-block"></i>
            </a>
        @endauth
        <a href="{{ route('category.levels', $category->slug) }}" class="px-4 py-2 rounded-2xl bg-white hover:bg-amber-50 border-2 border-slate-900 text-slate-800 font-extrabold text-sm decoration-none">
            <i class="fa-solid fa-arrow-left me-1"></i> {{ __('Back to levels list') }}
        </a>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const totalQuestions = {{ $questions->total() }};
            const levelId = {{ $level->id }};
            const categoryId = {{ $category->id }};
            const storageKey = `readProgress_cat${categoryId}_l${levelId}`;
            
            let localRead = JSON.parse(localStorage.getItem(storageKey)) || [];
            let serverRead = @json($readQuestionIds ?? []);
            
            // Sync read lists
            let readQuestions = [...new Set([...localRead, ...serverRead])];
            localStorage.setItem(storageKey, JSON.stringify(readQuestions));

            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const quizBtn = document.getElementById('quiz-btn');
            const readButtons = document.querySelectorAll('.mark-read-btn');
            const accordions = document.querySelectorAll('.acc-btn');

            // --- Handlers for custom Accordion toggle ---
            accordions.forEach(btn => {
                btn.addEventListener('click', (e) => {
                    if (e.target.closest('.speak')) return;
                    
                    const qId = btn.getAttribute('data-id');
                    const body = document.getElementById('collapse-' + qId);
                    const arrow = btn.querySelector('.arrow');
                    
                    // Close other accordions
                    document.querySelectorAll('.acc-body').forEach(el => {
                        if (el.id !== 'collapse-' + qId) {
                            el.classList.add('hidden');
                            const parentTrigger = el.previousElementSibling;
                            const parentArrow = parentTrigger.querySelector('.arrow');
                            if (parentArrow) parentArrow.style.transform = '';
                        }
                    });

                    // Toggle current accordion
                    body.classList.toggle('hidden');
                    arrow.style.transform = body.classList.contains('hidden') ? '' : 'rotate(180deg)';
                });
            });

            // --- Voice Speech synthesis (TTS) ---
            document.querySelectorAll('.speak').forEach(s => {
                s.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const text = s.getAttribute('data-text');
                    try {
                        const u = new SpeechSynthesisUtterance(text);
                        u.lang = 'bn-BD';
                        speechSynthesis.cancel();
                        speechSynthesis.speak(u);
                    } catch(err) {
                        console.error('Speech synthesis failed:', err);
                    }
                });
            });

            // --- Progress Updates ---
            function updateUI() {
                const progressPercentage = totalQuestions > 0 ? (readQuestions.length / totalQuestions) * 100 : 0;
                progressBar.style.width = progressPercentage + '%';
                progressText.innerText = readQuestions.length + ' / ' + totalQuestions + ' ' + '{{ __('read') }}';

                // Display checkmarks and disable read buttons for read questions
                readQuestions.forEach(qId => {
                    const checkIcon = document.getElementById('check-' + qId);
                    if (checkIcon) checkIcon.classList.remove('hidden');
                    
                    const btn = document.querySelector(`.mark-read-btn[data-id="${qId}"]`);
                    if (btn) {
                        btn.classList.remove('bg-white', 'hover:bg-emerald-50');
                        btn.classList.add('bg-emerald-500', 'text-white');
                        btn.innerHTML = '<i class="fa-solid fa-check-double me-1"></i> ' + '{{ __('Read done') }}';
                        btn.disabled = true;
                    }
                });

                // Show Quiz button if all read
                if (readQuestions.length >= totalQuestions && totalQuestions > 0) {
                    quizBtn.classList.remove('hidden');
                    quizBtn.classList.add('bouncy');
                }
            }

            updateUI();

            // --- Mark as read click triggers ---
            readButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const qId = parseInt(this.getAttribute('data-id'));
                    const nextId = this.getAttribute('data-next-id');
                    
                    if (!readQuestions.includes(qId)) {
                        readQuestions.push(qId);
                        localStorage.setItem(storageKey, JSON.stringify(readQuestions));
                        updateUI();
                        
                        // Sync with backend database
                        if ({{ auth()->check() ? 'true' : 'false' }}) {
                            fetch('{{ route('mark.read') }}', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                },
                                body: JSON.stringify({ question_id: qId })
                            }).catch(err => console.error('Sync failed:', err));
                        }
                        
                        // Collapse current, open next accordion
                        const currentBody = document.getElementById('collapse-' + qId);
                        if (currentBody) {
                            currentBody.classList.add('hidden');
                            const currentTrigger = currentBody.previousElementSibling;
                            const currentArrow = currentTrigger.querySelector('.arrow');
                            if (currentArrow) currentArrow.style.transform = '';
                        }

                        if (nextId) {
                            const nextBody = document.getElementById('collapse-' + nextId);
                            if (nextBody) {
                                nextBody.classList.remove('hidden');
                                const nextTrigger = nextBody.previousElementSibling;
                                const nextArrow = nextTrigger.querySelector('.arrow');
                                if (nextArrow) nextArrow.style.transform = 'rotate(180deg)';
                                
                                // Smooth scroll to next trigger
                                nextTrigger.scrollIntoView({ behavior: 'smooth', block: 'center' });
                            }
                        }
                    }
                });
            });
        });
    </script>
@endsection
