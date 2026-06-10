@extends('frontend.layouts.master')
@section('title', $level->name . ' - কুইজ খেলো')

@section('content')
    <!-- Quiz Header -->
    <header class="mb-4">
        <div class="flex items-center gap-3 mb-3">
            <a href="{{ route('category.levels', $category->slug) }}" class="w-11 h-11 rounded-2xl bg-white nb-sm flex items-center justify-center text-slate-800 hover:text-slate-800 decoration-none shrink-0">
                <i class="fa-solid fa-xmark text-lg"></i>
            </a>
            <div class="flex-1 h-4 rounded-full bg-white nb-sm overflow-hidden">
                <div id="progressBar" class="h-full bg-sky-400 transition-all duration-300" style="width: 0%;"></div>
            </div>
            <!-- Countdown Clock -->
            <span class="px-3 py-1.5 rounded-xl bg-rose-455 text-white font-extrabold nb-sm text-sm shrink-0 flex items-center gap-1.5">
                <i class="fa-solid fa-clock"></i>
                <span id="countdown">30s</span>
            </span>
        </div>
        <div class="flex justify-between items-center">
            <p id="progressText" class="text-sm font-extrabold text-slate-500 mb-0">প্রশ্ন ১ / {{ count($questions) }}</p>
            <p id="scoreText" class="text-sm font-extrabold text-emerald-600 mb-0">সঠিক: ০</p>
        </div>
    </header>

    <!-- Form for Laravel Quiz Submission -->
    <form action="{{ route('quiz.submit', ['slug' => $category->slug, 'level' => $level->id]) }}" method="POST" id="quizForm" class="pb-24">
        @csrf
        
        @foreach($questions as $index => $question)
            @php
                $options = $question->shuffledOptions();
                $labels = ['ক', 'খ', 'গ'];
            @endphp
            
            <div class="question-container" id="q_{{ $index }}" data-correct="{{ trim($question->answer_text) }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                <!-- Question Text Card -->
                <div class="bg-white rounded-3xl nb p-6 mb-5 min-h-[120px] flex items-center justify-center text-center">
                    <h2 class="text-lg sm:text-xl font-extrabold leading-snug text-slate-900 mb-0">
                        {{ $question->question_text }}
                    </h2>
                </div>

                <!-- Hidden Input to store selection -->
                <div class="hidden">
                    @foreach($options as $optIndex => $option)
                        <input type="radio" name="answers[{{ $question->id }}]" id="real_opt_{{ $optIndex }}_{{ $question->id }}" value="{{ $option }}" required>
                    @endforeach
                </div>

                <!-- Playful neobrutalist options buttons -->
                <div class="space-y-3 quiz-options">
                    @foreach($options as $optIndex => $option)
                        <button type="button" 
                            class="opt-btn w-full p-4 rounded-2xl bg-white border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] hover:-translate-y-1 active:translate-y-0 transition-all text-slate-800 font-extrabold text-left flex items-center gap-3 outline-none cursor-pointer"
                            data-val="{{ $option }}" 
                            data-label-id="real_opt_{{ $optIndex }}_{{ $question->id }}"
                        >
                            <span class="w-9 h-9 rounded-xl bg-amber-300 border-2 border-slate-900 flex items-center justify-center text-slate-900 shrink-0">
                                {{ $labels[$optIndex] }}
                            </span>
                            <span class="flex-1 text-sm sm:text-base font-sans">{{ $option }}</span>
                        </button>
                    @endforeach
                </div>
            </div>
        @endforeach
    </form>

    <!-- Interactive Feedback Bottom Banner -->
    <div id="feedback" class="hidden fixed bottom-24 md:bottom-6 inset-x-0 mx-auto max-w-3xl px-4 z-30">
        <div id="fbBox" class="rounded-3xl nb p-4 flex items-center justify-between gap-3 text-white">
            <div>
                <h3 id="fbTitle" class="font-extrabold text-base sm:text-lg mb-1"></h3>
                <p id="fbText" class="text-xs sm:text-sm font-semibold mb-0"></p>
            </div>
            <button id="nextBtn" class="px-5 py-3 rounded-2xl bg-white hover:bg-slate-50 border-2 border-slate-900 text-slate-900 font-extrabold text-sm transition-all active:translate-y-0.5 shrink-0 cursor-pointer">
                পরের প্রশ্ন <i class="fa-solid fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const questionsCount = {{ count($questions) }};
            let currentQIndex = 0;
            let currentScore = 0;
            let answered = false;
            let timerSeconds = 30;
            let timerInterval = null;

            const countdown = document.getElementById('countdown');
            const progressBar = document.getElementById('progressBar');
            const progressText = document.getElementById('progressText');
            const scoreText = document.getElementById('scoreText');
            const feedback = document.getElementById('feedback');
            const fbBox = document.getElementById('fbBox');
            const fbTitle = document.getElementById('fbTitle');
            const fbText = document.getElementById('fbText');
            const nextBtn = document.getElementById('nextBtn');
            const quizForm = document.getElementById('quizForm');

            // --- Countdown Timer ---
            function startTimer() {
                clearInterval(timerInterval);
                timerSeconds = 30;
                countdown.textContent = `${timerSeconds}s`;
                
                timerInterval = setInterval(() => {
                    timerSeconds--;
                    countdown.textContent = `${timerSeconds}s`;
                    
                    if (timerSeconds <= 0) {
                        clearInterval(timerInterval);
                        handleTimeout();
                    }
                }, 1000);
            }

            function stopTimer() {
                clearInterval(timerInterval);
            }

            // --- Progress Bar updates ---
            function updateProgressBar() {
                const percent = (currentQIndex / questionsCount) * 100;
                progressBar.style.width = `${percent}%`;
                progressText.textContent = `প্রশ্ন ${currentQIndex + 1} / ${questionsCount}`;
            }

            // --- Click option triggers ---
            function setupQuestionInteraction(qIndex) {
                const container = document.getElementById('q_' + qIndex);
                const optionBtns = container.querySelectorAll('.opt-btn');
                const correctAnswer = container.getAttribute('data-correct').trim();
                answered = false;

                optionBtns.forEach(btn => {
                    btn.addEventListener('click', function() {
                        if (answered) return;
                        answered = true;
                        stopTimer();

                        const selectedVal = this.getAttribute('data-val').trim();
                        const radioId = this.getAttribute('data-label-id');
                        
                        // Select hidden Laravel input radio button
                        const radioInput = document.getElementById(radioId);
                        if (radioInput) radioInput.checked = true;

                        const isCorrect = (selectedVal === correctAnswer);

                        // Disable all other options
                        optionBtns.forEach(b => b.setAttribute('disabled', 'true'));

                        if (isCorrect) {
                            // Correct state: correct tone, emerald green coloring, checkmark icon
                            if (window.sfx) window.sfx.correct();
                            this.classList.remove('bg-white');
                            this.classList.add('bg-emerald-500', 'text-white', 'pop');
                            this.insertAdjacentHTML('beforeend', '<span class="ml-auto text-xl shrink-0">🎉</span>');
                            currentScore++;
                            scoreText.textContent = `সভিক: ${currentScore}`;
                            showFeedback(true, correctAnswer);
                        } else {
                            // Wrong state: wrong tone, red coloring, shake animation, highlight correct
                            if (window.sfx) window.sfx.wrong();
                            this.classList.remove('bg-white');
                            this.classList.add('bg-rose-500', 'text-white', 'shake');
                            this.insertAdjacentHTML('beforeend', '<span class="ml-auto text-xl shrink-0">❌</span>');
                            
                            // Highlight correct option
                            optionBtns.forEach(b => {
                                if (b.getAttribute('data-val').trim() === correctAnswer) {
                                    b.classList.remove('bg-white');
                                    b.classList.add('bg-emerald-500', 'text-white');
                                }
                            });
                            showFeedback(false, correctAnswer);
                        }
                    });
                });
            }

            // --- Timeout Handler ---
            function handleTimeout() {
                if (answered) return;
                answered = true;
                stopTimer();

                const container = document.getElementById('q_' + currentQIndex);
                const optionBtns = container.querySelectorAll('.opt-btn');
                const correctAnswer = container.getAttribute('data-correct').trim();

                // Select the first radio input by default (or let validation fail on backend, but here we select first option so form is valid)
                const firstRadioId = optionBtns[0].getAttribute('data-label-id');
                const radioInput = document.getElementById(firstRadioId);
                if (radioInput) radioInput.checked = true;

                // Disable all option clicks
                optionBtns.forEach(b => {
                    b.setAttribute('disabled', 'true');
                    // Highlight correct option in green
                    if (b.getAttribute('data-val').trim() === correctAnswer) {
                        b.classList.remove('bg-white');
                        b.classList.add('bg-emerald-500', 'text-white');
                    }
                });

                if (window.sfx) window.sfx.wrong();
                
                // Show timeout feedback
                feedback.classList.remove('hidden');
                fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-rose-400 text-white';
                fbTitle.textContent = '⏱️ সময় শেষ!';
                fbText.textContent = 'দ্রুত উত্তর দেওয়ার চেষ্টা করো। সঠিক উত্তরটি দেখে নাও!';
                
                setupNextButton();
            }

            // --- Feedback Banner UI ---
            function showFeedback(isCorrect, correctAnswer) {
                feedback.classList.remove('hidden');
                
                if (isCorrect) {
                    fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-emerald-500 text-white';
                    fbTitle.textContent = '🎉 অসাধারণ হয়েছে!';
                    fbText.textContent = 'তুমি সঠিক উত্তর দিয়েছো!';
                } else {
                    fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-rose-500 text-white';
                    fbTitle.textContent = '😅 ইশ্!';
                    fbText.textContent = `সঠিক উত্তর: ${correctAnswer}`;
                }

                setupNextButton();
            }

            // --- Setup Next Button Click Handler ---
            function setupNextButton() {
                // If it is the last question, change next button text to submit
                if (currentQIndex + 1 >= questionsCount) {
                    nextBtn.innerHTML = 'কুইজ জমা দাও <i class="fa-solid fa-paper-plane ml-1"></i>';
                    nextBtn.onclick = () => {
                        feedback.classList.add('hidden');
                        quizForm.submit();
                    };
                } else {
                    nextBtn.innerHTML = 'পরের প্রশ্ন <i class="fa-solid fa-arrow-right ml-1"></i>';
                    nextBtn.onclick = () => {
                        feedback.classList.add('hidden');
                        
                        // Hide current question
                        document.getElementById('q_' + currentQIndex).style.display = 'none';
                        
                        // Move to next question
                        currentQIndex++;
                        document.getElementById('q_' + currentQIndex).style.display = 'block';
                        
                        updateProgressBar();
                        setupQuestionInteraction(currentQIndex);
                        startTimer();
                    };
                }
            }

            // --- Boot ---
            updateProgressBar();
            setupQuestionInteraction(0);
            startTimer();
        });
    </script>
@endsection
