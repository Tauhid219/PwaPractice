@extends('frontend.layouts.master')
@section('title', $level->name . ' - ' . __('Play Quiz'))

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
            <span class="px-3 py-1.5 rounded-xl bg-rose-500 border-2 border-slate-900 text-white font-extrabold nb-sm text-sm shrink-0 flex items-center gap-1.5 font-sans">
                <i class="fa-solid fa-clock"></i>
                <span id="countdown">30s</span>
            </span>
        </div>
        <div class="flex justify-between items-center">
            <p id="progressText" class="text-sm font-extrabold text-slate-500 mb-0 font-sans">{{ __('Question') }} 1 / {{ count($questions) }}</p>
            <p id="scoreText" class="text-sm font-extrabold text-emerald-600 mb-0 font-sans">{{ __('Correct') }}: 0</p>
        </div>
    </header>

    <!-- Form for Laravel Quiz Submission -->
    <form action="{{ route('quiz.submit', ['slug' => $category->slug, 'level' => $level->id]) }}" method="POST" id="quizForm" class="pb-24">
        @csrf
        
        @foreach($questions as $index => $question)
            <div class="question-container" id="q_{{ $index }}" data-question-id="{{ $question->id }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                <!-- Question Text Card -->
                <div class="bg-white rounded-3xl nb p-6 mb-5 min-h-[120px] flex items-center justify-center text-center">
                    <h2 class="text-lg sm:text-xl font-extrabold leading-snug text-slate-900 mb-0 font-sans">
                        {{ $question->question_text }}
                    </h2>
                </div>

                <!-- Playful neobrutalist typed text input -->
                <div class="px-2 py-4">
                    <input type="text"
                           name="answers[{{ $question->id }}]"
                           class="quiz-input w-full p-4 rounded-2xl bg-white border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] text-slate-800 font-extrabold text-center text-lg sm:text-xl outline-none focus:border-amber-400 transition-colors placeholder-slate-400 font-sans"
                           placeholder="{{ __('Type your answer here...') }}"
                           autocomplete="off"
                           required
                    >
                    <!-- Verify button -->
                    <button type="button"
                            class="verify-btn w-full mt-5 py-4 rounded-2xl bg-amber-300 border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] hover:-translate-y-1 active:translate-y-0 transition-all text-slate-900 font-extrabold text-center outline-none cursor-pointer text-base font-sans"
                    >
                        {{ __('Verify Answer') }} <i class="fa-solid fa-circle-question ms-1"></i>
                    </button>
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
            <button id="nextBtn" class="px-5 py-3 rounded-2xl bg-white hover:bg-slate-50 border-2 border-slate-900 text-slate-900 font-extrabold text-sm transition-all active:translate-y-0.5 shrink-0 cursor-pointer font-sans">
                {{ __('Next Question') }} <i class="fa-solid fa-arrow-right ml-1"></i>
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

            // Stop Timer
            function stopTimer() {
                clearInterval(timerInterval);
            }

            // --- Progress Bar updates ---
            function updateProgressBar() {
                const percent = (currentQIndex / questionsCount) * 100;
                progressBar.style.width = `${percent}%`;
                progressText.textContent = `{{ __('Question') }} ${currentQIndex + 1} / ${questionsCount}`;
            }

            // --- Input and Verification triggers ---
            function setupQuestionInteraction(qIndex) {
                const container = document.getElementById('q_' + qIndex);
                const input = container.querySelector('.quiz-input');
                const verifyBtn = container.querySelector('.verify-btn');
                answered = false;

                // Auto focus input
                setTimeout(() => {
                    input.focus();
                }, 200);

                // Handle Enter Key
                input.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter') {
                        e.preventDefault();
                        verifyBtn.click();
                    }
                });

                verifyBtn.addEventListener('click', function() {
                    if (answered) return;
                    
                    const userTyped = input.value.trim();
                    if (!userTyped) {
                        alert('{{ __('Please write an answer!') }}');
                        return;
                    }

                    answered = true;
                    stopTimer();

                    // Set readOnly on text input to prevent editing but allow form submission
                    input.readOnly = true;
                    verifyBtn.disabled = true;

                    // AJAX answer validation
                    const questionId = container.getAttribute('data-question-id');
                    fetch('{{ route('quiz.check-answer') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({
                            question_id: questionId,
                            answer: userTyped
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        const isCorrect = data.is_correct;
                        const correctAnswer = data.correct_answer;

                        if (isCorrect) {
                            // Correct state
                            if (window.sfx) window.sfx.correct();
                            input.classList.remove('bg-white');
                            input.classList.add('bg-emerald-500', 'text-white', 'pop');
                            currentScore++;
                            scoreText.textContent = `{{ __('Correct') }}: ${currentScore}`;
                            showFeedback(true, correctAnswer);
                        } else {
                            // Incorrect state
                            if (window.sfx) window.sfx.wrong();
                            input.classList.remove('bg-white');
                            input.classList.add('bg-rose-500', 'text-white', 'shake');
                            showFeedback(false, correctAnswer);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        // If offline, provide neutral feedback
                        if (!navigator.onLine) {
                            input.classList.remove('bg-white');
                            input.classList.add('bg-slate-500', 'text-white'); // Neutral
                            showFeedbackOffline();
                        } else {
                            // Fallback logic for normal error
                            if (window.sfx) window.sfx.wrong();
                            input.classList.remove('bg-white');
                            input.classList.add('bg-rose-500', 'text-white', 'shake');
                            showFeedback(false, '');
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
                const input = container.querySelector('.quiz-input');
                const verifyBtn = container.querySelector('.verify-btn');

                // Set readOnly on text input and highlight red
                input.readOnly = true;
                verifyBtn.disabled = true;
                input.classList.remove('bg-white');
                input.classList.add('bg-rose-500', 'text-white', 'shake');

                if (window.sfx) window.sfx.wrong();
                
                // Show timeout feedback
                feedback.classList.remove('hidden');
                fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-rose-500 text-white';
                fbTitle.textContent = '⏱️ ' + (App.getLocale === 'en' ? "Time's up!" : 'সময় শেষ!');
                fbText.textContent = App.getLocale === 'en' ? 'Try to answer faster next time!' : 'দ্রুত উত্তর দেওয়ার চেষ্টা করো।';
                
                setupNextButton();
            }

            // --- Feedback Banner UI ---
            function showFeedback(isCorrect, correctAnswerAttr) {
                feedback.classList.remove('hidden');
                
                if (isCorrect) {
                    fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-emerald-500 text-white';
                    fbTitle.textContent = '🎉 ' + (App.getLocale === 'en' ? 'Awesome job!' : 'অসাধারণ হয়েছে!');
                    fbText.textContent = App.getLocale === 'en' ? 'You got it right!' : 'তুমি সঠিক উত্তর দিয়েছো!';
                } else {
                    fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-rose-500 text-white';
                    fbTitle.textContent = '😅 ' + (App.getLocale === 'en' ? 'Oops!' : 'ইশ্!');
                    fbText.textContent = App.getLocale === 'en' ? 'Your answer is incorrect!' : 'তোমার উত্তরটি সঠিক হয়নি।';
                }

                setupNextButton();
            }

            function showFeedbackOffline() {
                feedback.classList.remove('hidden');
                fbBox.className = 'rounded-3xl nb p-4 flex items-center justify-between gap-3 bg-slate-600 text-white';
                fbTitle.textContent = '💾 ' + (App.getLocale === 'en' ? 'Saved Offline' : 'অফলাইনে সেভ হয়েছে');
                fbText.textContent = App.getLocale === 'en' ? 'Your answer is drafted.' : 'আপনার উত্তর লোকালি সংরক্ষণ করা হয়েছে।';
                setupNextButton();
            }

            // --- Offline Fallback & IndexedDB ---
            const dbName = 'PwaQuizDB';
            const storeName = 'offline_quizzes';
            let db;

            function initDB() {
                return new Promise((resolve, reject) => {
                    const request = indexedDB.open(dbName, 1);
                    request.onupgradeneeded = (e) => {
                        db = e.target.result;
                        if (!db.objectStoreNames.contains(storeName)) {
                            db.createObjectStore(storeName, { keyPath: 'id', autoIncrement: true });
                        }
                    };
                    request.onsuccess = (e) => {
                        db = e.target.result;
                        resolve(db);
                    };
                    request.onerror = (e) => reject(e);
                });
            }

            async function saveQuizDraft(formData) {
                if (!db) await initDB();
                return new Promise((resolve, reject) => {
                    const tx = db.transaction(storeName, 'readwrite');
                    const store = tx.objectStore(storeName);
                    const dataObj = {};
                    formData.forEach((value, key) => dataObj[key] = value);
                    
                    store.add({
                        type: 'quiz_submit',
                        url: quizForm.action,
                        data: dataObj,
                        timestamp: new Date().getTime()
                    });
                    tx.oncomplete = () => resolve();
                    tx.onerror = (e) => reject(e);
                });
            }

            async function getQuizDrafts() {
                if (!db) await initDB();
                return new Promise((resolve, reject) => {
                    const tx = db.transaction(storeName, 'readonly');
                    const store = tx.objectStore(storeName);
                    const request = store.getAll();
                    request.onsuccess = () => resolve(request.result);
                    request.onerror = (e) => reject(e);
                });
            }

            async function clearQuizDrafts() {
                if (!db) await initDB();
                return new Promise((resolve, reject) => {
                    const tx = db.transaction(storeName, 'readwrite');
                    const store = tx.objectStore(storeName);
                    const request = store.clear();
                    request.onsuccess = () => resolve();
                    request.onerror = (e) => reject(e);
                });
            }

            async function handleOfflineSubmit() {
                alert(App.getLocale === 'en' 
                    ? 'You are offline. Your quiz has been saved locally and will submit automatically when you reconnect. Please leave this tab open.' 
                    : 'আপনি অফলাইনে আছেন। আপনার কুইজ লোকালি সেভ করা হয়েছে এবং কানেকশন পেলে নিজে থেকেই সাবমিট হবে। দয়া করে পেজটি বন্ধ করবেন না।');
                
                const formData = new FormData(quizForm);
                await saveQuizDraft(formData);
                nextBtn.disabled = true;
                nextBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> ' + (App.getLocale === 'en' ? 'Waiting for connection...' : 'কানেকশনের অপেক্ষায়...');
            }

            window.addEventListener('online', async () => {
                if (nextBtn.disabled && nextBtn.innerHTML.includes('fa-spin')) {
                    // We were waiting for connection to submit
                    const drafts = await getQuizDrafts();
                    if (drafts.length > 0) {
                        // Submit normal form
                        quizForm.submit();
                    }
                }
            });

            initDB().catch(console.error);

            // --- Setup Next Button Click Handler ---
            function setupNextButton() {
                if (currentQIndex + 1 >= questionsCount) {
                    nextBtn.innerHTML = '{{ __('Submit Quiz') }} <i class="fa-solid fa-paper-plane ml-1"></i>';
                    nextBtn.onclick = () => {
                        feedback.classList.add('hidden');
                        if (!navigator.onLine) {
                            handleOfflineSubmit();
                        } else {
                            quizForm.submit();
                        }
                    };
                } else {
                    nextBtn.innerHTML = '{{ __('Next Question') }} <i class="fa-solid fa-arrow-right ml-1"></i>';
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

            // Get current App locale context helper
            const App = {
                getLocale: '{{ App::getLocale() }}'
            };

            // --- Boot ---
            updateProgressBar();
            setupQuestionInteraction(0);
            startTimer();
        });
    </script>
@endsection
