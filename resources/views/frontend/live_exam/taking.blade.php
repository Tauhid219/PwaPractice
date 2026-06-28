@extends('frontend.layouts.master')
@section('title', __('Live Exam') . ' - ' . $exam->title)

@section('content')
<!-- Page Header Start -->
<div class="rounded-3xl bg-gradient-to-br from-indigo-300 to-purple-500 nb p-5 text-white mb-6" style="user-select: none;">
    <div class="flex items-center justify-between flex-wrap gap-4">
        <div>
            <p class="text-xs opacity-90 font-extrabold text-white mb-0">{{ __('Live Exam') }}</p>
            <h1 class="text-xl font-extrabold leading-tight text-white mb-0 font-sans">{{ $exam->title }}</h1>
        </div>
        <div class="text-right">
            <span class="px-3 py-1.5 rounded-full bg-purple-600/30 border border-white/20 font-extrabold text-xs">
                ⏱️ {{ __('Time') }}: {{ $exam->duration_minutes }} {{ __('Minutes') }}
            </span>
        </div>
    </div>
</div>

<div class="pb-16" style="user-select: none;">
    <div class="max-w-3xl mx-auto">
        <div class="bg-white rounded-3xl border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] p-5">
            
            <!-- Sticky Timer Warning Header -->
            <div class="sticky top-3 z-30 bg-white rounded-2xl border-3 border-slate-900 shadow-[3px_3px_0px_#0f172a] p-3 flex justify-between items-center mb-6">
                <h3 class="mb-0 text-slate-800 font-extrabold text-sm font-sans flex items-center gap-1.5"><i class="fa-solid fa-pen-nib text-indigo-500"></i> {{ __('Exam in progress...') }}</h3>
                <div class="flex items-center gap-1.5 px-3 py-1 rounded-xl bg-rose-100 border-2 border-slate-900 font-extrabold text-rose-700 text-sm animate-pulse">
                    <i class="fa-solid fa-stopwatch"></i> <span id="timer">--:--</span>
                </div>
            </div>

            <form action="{{ route('live-exams.submit', $exam->id) }}" method="POST" id="liveExamForm">
                @csrf
                <input type="hidden" name="tab_switches" id="tabSwitchesInput" value="0">
                
                @if($questions->isEmpty())
                    <div class="bg-amber-100 border-2 border-slate-900 rounded-2xl p-4 text-center font-extrabold text-amber-850">
                        {{ __('No questions in this exam.') }}
                    </div>
                @else
                    <!-- Progress Bar Start -->
                    <div class="mb-5">
                        <div class="flex justify-between items-center text-xs font-extrabold text-slate-500 mb-1.5">
                            <span id="progressText">{{ __('Question') }} 1 / {{ count($questions) }}</span>
                            <span id="percentageText">0% {{ __('completed') }}</span>
                        </div>
                        <div class="w-full h-4 rounded-full bg-white border-2 border-slate-900 shadow-[2px_2px_0px_#0f172a] overflow-hidden">
                            <div id="progressBar" class="h-full bg-amber-300 transition-all duration-300" style="width: 0%"></div>
                        </div>
                    </div>
                    <!-- Progress Bar End -->
                    
                    <!-- Skeleton Loader -->
                    <div id="skeleton-loader" class="space-y-3 mb-4" style="display: none;">
                        <div class="h-14 bg-slate-50 rounded-2xl border-2 border-slate-200 animate-pulse"></div>
                        <div class="h-16 bg-slate-50 rounded-2xl border-2 border-slate-200 animate-pulse"></div>
                        <div class="h-16 bg-slate-50 rounded-2xl border-2 border-slate-200 animate-pulse"></div>
                        <div class="h-16 bg-slate-50 rounded-2xl border-2 border-slate-200 animate-pulse"></div>
                    </div>

                    @foreach($questions as $index => $question)
                        <div class="question-container" id="q_{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                            <!-- Question Text -->
                            <div class="bg-slate-50 rounded-2xl border-2 border-slate-900 p-4 mb-4">
                                <h4 class="font-extrabold text-sm sm:text-base text-slate-800 leading-snug font-sans">
                                    <span class="text-indigo-600 font-sans">{{ __('Question') }} {{ $index + 1 }}:</span> {{ $question->question_text }}
                                </h4>
                            </div>
                            
                            <!-- Hidden inputs for Laravel backend validation -->
                            <div class="hidden">
                                @php
                                    $options = $question->shuffledOptions();
                                    $labels = App::getLocale() === 'en' ? ['A', 'B', 'C', 'D'] : ['ক', 'খ', 'গ', 'ঘ'];
                                @endphp
                                @foreach($options as $optIndex => $option)
                                    <input type="radio" name="answers[{{ $question->id }}]" id="opt{{ $optIndex }}_{{ $question->id }}" value="{{ $option }}" required>
                                @endforeach
                            </div>

                            <!-- Interactive Neobrutalist Options -->
                            <div class="space-y-3 quiz-options mb-6">
                                @foreach($options as $optIndex => $option)
                                    <button type="button" 
                                        class="opt-btn w-full p-4 rounded-2xl bg-white border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] hover:-translate-y-0.5 active:translate-y-0 transition-all text-slate-800 font-extrabold text-left flex items-center gap-3 outline-none cursor-pointer"
                                        data-val="{{ $option }}" 
                                        data-radio-id="opt{{ $optIndex }}_{{ $question->id }}"
                                    >
                                        <span class="label-badge w-9 h-9 rounded-xl bg-indigo-50 border-2 border-slate-900 flex items-center justify-center text-slate-950 font-extrabold shrink-0 font-sans text-xs">
                                            {{ $labels[$optIndex] }}
                                        </span>
                                        <span class="flex-1 text-sm sm:text-base font-sans">{{ $option }}</span>
                                    </button>
                                @endforeach
                            </div>

                            <!-- Control Button -->
                            <div class="text-end">
                                @if($index < count($questions) - 1)
                                    <button type="button" class="px-5 py-2.5 rounded-2xl bg-amber-300 hover:bg-amber-400 border-2 border-slate-900 text-slate-900 font-extrabold text-xs transition-all shadow-[2px_2px_0px_#000000] active:translate-y-0.5 active:shadow-none next-btn cursor-pointer" data-index="{{ $index }}">
                                        {{ __('Next Question') }} <i class="fa-solid fa-arrow-right ms-1"></i>
                                    </button>
                                @else
                                    <button type="button" class="px-6 py-3 rounded-2xl bg-emerald-400 hover:bg-emerald-500 border-2 border-slate-900 text-white font-extrabold text-sm transition-all shadow-[3px_3px_0px_#000000] active:translate-y-0.5 active:shadow-none cursor-pointer pulse-animation" onclick="confirmSubmit()">
                                        {{ __('Submit Exam') }} <i class="fa-solid fa-paper-plane ms-1"></i>
                                    </button>
                                @endif
                            </div>
                        </div>
                    @endforeach
                @endif
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const nextBtns = document.querySelectorAll('.next-btn');
        const optButtons = document.querySelectorAll('.opt-btn');

        // Play pop sound using synthesized sound on option select, and update selected states
        optButtons.forEach(btn => {
            btn.addEventListener('click', function() {
                if (window.tone) {
                    window.tone('triangle', 300, 0.08); // Play audio synth chime
                }

                const radioId = this.getAttribute('data-radio-id');
                const radioInput = document.getElementById(radioId);
                if (radioInput) {
                    radioInput.checked = true;
                }

                // Remove highlight from all option buttons in the same question container
                const container = this.closest('.question-container');
                const siblings = container.querySelectorAll('.opt-btn');
                siblings.forEach(sib => {
                    sib.className = "opt-btn w-full p-4 rounded-2xl bg-white border-3 border-slate-900 shadow-[4px_4px_0px_#0f172a] hover:-translate-y-0.5 active:translate-y-0 transition-all text-slate-800 font-extrabold text-left flex items-center gap-3 outline-none cursor-pointer";
                    const badge = sib.querySelector('.label-badge');
                    if (badge) {
                        badge.className = "label-badge w-9 h-9 rounded-xl bg-indigo-50 border-2 border-slate-900 flex items-center justify-center text-slate-950 font-extrabold shrink-0 font-sans text-xs";
                    }
                });

                // Add active neobrutalist styling to the clicked button
                this.className = "opt-btn w-full p-4 rounded-2xl bg-amber-300 border-3 border-slate-900 shadow-[2px_2px_0px_#0f172a] translate-y-0.5 transition-all text-slate-900 font-extrabold text-left flex items-center gap-3 outline-none cursor-pointer";
                const badge = this.querySelector('.label-badge');
                if (badge) {
                    badge.className = "label-badge w-9 h-9 rounded-xl bg-amber-400 border-2 border-slate-900 flex items-center justify-center text-slate-900 shrink-0 font-sans text-xs";
                }

                // Continuously save to draft in background
                if (typeof saveExamDraft === 'function') {
                    const formData = new FormData(document.getElementById('liveExamForm'));
                    saveExamDraft(formData).catch(e => console.error("Draft save failed", e));
                }
            });
        });

        // Pagination Logic
        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIndex = parseInt(this.getAttribute('data-index'));
                const container = document.getElementById('q_' + currentIndex);
                const checkedOption = container.querySelector('input[type="radio"]:checked');
                
                if(!checkedOption) {
                    alert('{{ __('Please select an answer!') }}');
                    return;
                }

                // Move to next question immediately with a brief skeleton effect
                const nextIndex = currentIndex + 1;
                const skeleton = document.getElementById('skeleton-loader');
                const nextQuestion = document.getElementById('q_' + nextIndex);

                document.getElementById('q_' + currentIndex).style.display = 'none';
                skeleton.style.display = 'block';

                setTimeout(() => {
                    skeleton.style.display = 'none';
                    nextQuestion.style.display = 'block';
                    // Update Progress Bar after switch
                    updateProgressBar(nextIndex);
                }, 400);
            });
        });

        function updateProgressBar(index) {
            const total = {{ count($questions) }};
            const current = index + 1;
            const percentage = Math.round((index / total) * 100);
            
            const questionText = '{{ __('Question') }}';
            const completedText = '{{ __('completed') }}';
            document.getElementById('progressText').innerText = `${questionText} ${current} / ${total}`;
            document.getElementById('percentageText').innerText = `${percentage}% ${completedText}`;
            document.getElementById('progressBar').style.width = percentage + '%';
        }

        // Initialize progress bar
        if(document.getElementById('progressText')) {
            updateProgressBar(0);
        }

        // Security Features: Disable Copy, Paste, Cut, Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => {
            event.preventDefault();
            alert('{{ __('Copying is prohibited!') }}');
        });
        document.addEventListener('paste', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());

        // --- IndexedDB for Live Exam Drafts ---
        const dbName = 'PwaLiveExamDB';
        const storeName = 'offline_live_exams';
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

        async function saveExamDraft(formData) {
            if (!db) await initDB();
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readwrite');
                const store = tx.objectStore(storeName);
                const dataObj = {};
                formData.forEach((value, key) => dataObj[key] = value);
                
                // Keep only one draft per exam ID
                const examId = '{{ $exam->id }}';
                dataObj._exam_id = examId;
                
                store.put({
                    id: examId, // Overwrite existing draft for this exam
                    url: form.action,
                    data: dataObj,
                    timestamp: new Date().getTime()
                });
                tx.oncomplete = () => resolve();
                tx.onerror = (e) => reject(e);
            });
        }

        async function getExamDrafts() {
            if (!db) await initDB();
            return new Promise((resolve, reject) => {
                const tx = db.transaction(storeName, 'readonly');
                const store = tx.objectStore(storeName);
                const request = store.getAll();
                request.onsuccess = () => resolve(request.result);
                request.onerror = (e) => reject(e);
            });
        }

        async function handleExamSubmit() {
            if (!navigator.onLine) {
                // Offline fallback
                const formData = new FormData(form);
                await saveExamDraft(formData);
                alert('{{ App::getLocale() === 'en' ? 'You are offline. Your exam has been saved locally and will submit automatically when you reconnect. Please leave this tab open.' : 'আপনি অফলাইনে আছেন। আপনার পরীক্ষা লোকালি সেভ করা হয়েছে এবং কানেকশন পেলে নিজে থেকেই সাবমিট হবে। দয়া করে পেজটি বন্ধ করবেন না।' }}');
                
                // Disable everything and show waiting UI
                document.querySelectorAll('.opt-btn, .next-btn').forEach(btn => btn.disabled = true);
                const submitBtn = document.querySelector('[onclick="confirmSubmit()"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> {{ App::getLocale() === 'en' ? 'Waiting for connection...' : 'কানেকশনের অপেক্ষায়...' }}';
                }
            } else {
                form.submit();
            }
        }

        window.addEventListener('online', async () => {
            if (isSubmitting) {
                const drafts = await getExamDrafts();
                if (drafts.length > 0) {
                    form.submit();
                }
            }
        });

        initDB().catch(console.error);

        // Timer Logic (Web Worker Upgrade)
        @php
            $durationSeconds = $exam->duration_minutes * 60;
            $remainingWindowSeconds = now()->diffInSeconds($exam->end_time, false);
            $actualSecondsLeft = min($durationSeconds, $remainingWindowSeconds);
        @endphp
        let durationInSeconds = {{ max(0, $actualSecondsLeft) }};
        const timerDisplay = document.getElementById('timer');
        const form = document.getElementById('liveExamForm');
        let isSubmitting = false;

        if (window.Worker && timerDisplay) {
            const timerWorker = new Worker('{{ asset("frontend/js/timer-worker.js") }}');
            
            timerWorker.postMessage({ action: 'start', seconds: durationInSeconds });

            timerWorker.onmessage = function(e) {
                if (isSubmitting) return;

                if (e.data.action === 'tick') {
                    let secondsRemaining = e.data.secondsRemaining;
                    
                    let minutes = Math.floor(secondsRemaining / 60);
                    let seconds = secondsRemaining % 60;

                    minutes = minutes < 10 ? '0' + minutes : minutes;
                    seconds = seconds < 10 ? '0' + seconds : seconds;

                    timerDisplay.textContent = minutes + ':' + seconds;

                    if (secondsRemaining <= 120) {
                        timerDisplay.parentNode.classList.remove('bg-rose-100', 'text-rose-700');
                        timerDisplay.parentNode.classList.add('bg-rose-500', 'text-white');
                    }
                } else if (e.data.action === 'finished') {
                    isSubmitting = true;
                    timerDisplay.textContent = "00:00";
                    alert('{{ __('Time Up! Your answers are being submitted automatically.') }}');
                    
                    // Prevent further changes
                    document.querySelectorAll('.opt-btn').forEach(btn => btn.disabled = true);
                    
                    handleExamSubmit();
                }
            };
        } else if (timerDisplay) {
            // Fallback for browsers without Web Worker support
            function updateTimer() {
                if (isSubmitting) return;

                let minutes = Math.floor(durationInSeconds / 60);
                let seconds = durationInSeconds % 60;

                minutes = minutes < 10 ? '0' + minutes : minutes;
                seconds = seconds < 10 ? '0' + seconds : seconds;

                timerDisplay.textContent = minutes + ':' + seconds;

                if (durationInSeconds <= 120) {
                    timerDisplay.parentNode.classList.remove('bg-rose-100', 'text-rose-700');
                    timerDisplay.parentNode.classList.add('bg-rose-500', 'text-white');
                }

                if (durationInSeconds <= 0) {
                    isSubmitting = true;
                    timerDisplay.textContent = "00:00";
                    alert('{{ __('Time Up! Your answers are being submitted automatically.') }}');
                    document.querySelectorAll('.opt-btn').forEach(btn => btn.disabled = true);
                    handleExamSubmit();
                } else {
                    durationInSeconds--;
                }
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        }
        
        // Prevent accidental page leave
        window.onbeforeunload = function() {
            if (!isSubmitting) {
                return "{{ __('Are you sure you want to leave? Your progress will not be saved.') }}";
            }
        };

        // Custom Submit Function to avoid onbeforeunload firing on purpose
        window.confirmSubmit = function() {
            const containers = document.querySelectorAll('.question-container');
            const lastContainer = containers[containers.length - 1];
            const checkedOption = lastContainer.querySelector('input[type="radio"]:checked');
            
            if(!checkedOption) {
                alert('{{ __('Please answer the last question before submitting.') }}');
                return;
            }

            if(confirm('{{ __('Confirm Submission') }}')) {
                isSubmitting = true;
                handleExamSubmit();
            }
        }

        // Anti-Cheat: Tab Switch Detection
        let tabSwitchCount = 0;
        const maxTabSwitches = 3;

        document.addEventListener('visibilitychange', function() {
            if (document.visibilityState === 'hidden') {
                if (isSubmitting) return;
                
                tabSwitchCount++;
                document.getElementById('tabSwitchesInput').value = tabSwitchCount;

                if (tabSwitchCount >= maxTabSwitches) {
                    isSubmitting = true;
                    alert('{{ __('Warning: You have switched tabs 3 times. Your exam will be submitted now.') }}');
                    handleExamSubmit();
                } else {
                    alert('{{ __('Warning: Switching tabs or windows during the exam is prohibited! You have ') }}' + tabSwitchCount + '{{ __(' warning(s). ') }}' + maxTabSwitches + '{{ __(' times will auto-submit the exam.') }}');
                }
            }
        });
    });
</script>
@endsection
