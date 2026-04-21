@extends('frontend.layouts.master')
@section('title', 'Live Exam - ' . $exam->title)

@section('content')
<!-- Page Header Start -->
<div class="container-xxl py-5 page-header position-relative mb-5" style="user-select: none;">
    <div class="container py-5">
        <h1 class="display-3 text-white animated slideInDown mb-4">{{ $exam->title }}</h1>
        <p class="text-white-50 fs-5">সময়: {{ $exam->duration_minutes }} মিনিট</p>
    </div>
</div>

<div class="container-xxl py-5" style="user-select: none;">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8 wow border border-warning rounded rounded-3 p-4 shadow-sm fadeInUp" data-wow-delay="0.1s">
                
                <!-- Fixed Timer Warning Header -->
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3 position-sticky top-0 bg-white shadow-sm p-3 z-3" style="top: 10px; border-radius: 8px;">
                    <h3 class="mb-0 text-primary"><i class="fa fa-pen me-2"></i> পরীক্ষা চলছে...</h3>
                    <div class="fs-4 fw-bold text-danger pulse-animation-slow">
                        <i class="fa fa-stopwatch me-1"></i> <span id="timer">--:--</span>
                    </div>
                </div>

                <form action="{{ route('live-exams.submit', $exam->id) }}" method="POST" id="liveExamForm">
                    @csrf
                    <input type="hidden" name="tab_switches" id="tabSwitchesInput" value="0">
                    
                    @if($questions->isEmpty())
                        <div class="alert alert-warning">এই পরীক্ষায় কোন প্রশ্ন নেই।</div>
                    @else
                        <!-- Progress Bar Start -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-primary fw-bold" id="progressText">প্রশ্ন ১ / {{ count($questions) }}</span>
                                <span class="text-muted" id="percentageText">০% সম্পন্ন</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-warning" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Progress Bar End -->
                        
                        <!-- Skeleton Loader -->
                        <div id="skeleton-loader" style="display: none;">
                            <div class="skeleton skeleton-title"></div>
                            <div class="skeleton skeleton-option"></div>
                            <div class="skeleton skeleton-option"></div>
                            <div class="skeleton skeleton-option"></div>
                        </div>

                        @foreach($questions as $index => $question)
                            <div class="mb-4 question-container" id="q_{{ $index }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                                <h4><span class="text-primary">প্রশ্ন {{ $index + 1 }}:</span> {{ $question->question_text }}</h4>
                                
                                <div class="mt-4 quiz-options">
                                    <div class="option-item mb-3">
                                        <input type="radio" name="answers[{{ $question->id }}]" id="opt1_{{ $question->id }}" value="{{ $question->option_1 }}" class="btn-check" required>
                                        <label class="btn btn-outline-primary w-100 text-start p-3 fs-5" for="opt1_{{ $question->id }}">
                                            <span class="fw-bold me-2">ক)</span> {{ $question->option_1 }}
                                        </label>
                                    </div>
                                    <div class="option-item mb-3">
                                        <input type="radio" name="answers[{{ $question->id }}]" id="opt2_{{ $question->id }}" value="{{ $question->option_2 }}" class="btn-check">
                                        <label class="btn btn-outline-primary w-100 text-start p-3 fs-5" for="opt2_{{ $question->id }}">
                                            <span class="fw-bold me-2">খ)</span> {{ $question->option_2 }}
                                        </label>
                                    </div>
                                    <div class="option-item mb-3">
                                        <input type="radio" name="answers[{{ $question->id }}]" id="opt3_{{ $question->id }}" value="{{ $question->option_3 }}" class="btn-check">
                                        <label class="btn btn-outline-primary w-100 text-start p-3 fs-5" for="opt3_{{ $question->id }}">
                                            <span class="fw-bold me-2">গ)</span> {{ $question->option_3 }}
                                        </label>
                                    </div>
                                </div>

                                <div class="mt-4 text-end">
                                    @if($index < count($questions) - 1)
                                        <button type="button" class="btn btn-warning text-dark px-4 py-2 next-btn" data-index="{{ $index }}">পরের প্রশ্ন <i class="fa fa-arrow-right ms-2"></i></button>
                                    @else
                                        <button type="button" class="btn btn-success px-5 py-3 fs-5 shadow pulse-animation" onclick="confirmSubmit()">সাবমিট করুন <i class="fa fa-paper-plane ms-2"></i></button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popAudio = new Audio('{{ asset("frontend/sounds/pop.mp3") }}');
        const nextBtns = document.querySelectorAll('.next-btn');
        const options = document.querySelectorAll('.btn-check');

        // Play pop sound on option select
        options.forEach(opt => {
            opt.addEventListener('change', () => {
                popAudio.volume = 0.5;
                popAudio.play().catch(e => {});
            });
        });

        // Pagination Logic
        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIndex = parseInt(this.getAttribute('data-index'));
                const container = document.getElementById('q_' + currentIndex);
                const checkedOption = container.querySelector('input[type="radio"]:checked');
                
                if(!checkedOption) {
                    alert('দয়া করে একটি উত্তর নির্বাচন করুন!');
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
            
            document.getElementById('progressText').innerText = `প্রশ্ন ${current} / ${total}`;
            document.getElementById('percentageText').innerText = `${percentage}% সম্পন্ন`;
            document.getElementById('progressBar').style.width = percentage + '%';
            document.getElementById('progressBar').setAttribute('aria-valuenow', percentage);
        }

        // Initialize progress bar
        if(document.getElementById('progressText')) {
            updateProgressBar(0);
        }

        // Security Features: Disable Copy, Paste, Cut, Right Click
        document.addEventListener('contextmenu', event => event.preventDefault());
        document.addEventListener('copy', event => {
            event.preventDefault();
            alert('কপি করা নিষেধ!');
        });
        document.addEventListener('paste', event => event.preventDefault());
        document.addEventListener('cut', event => event.preventDefault());

        // Timer Logic (Web Worker Upgrade)
        let durationInSeconds = {{ $exam->duration_minutes * 60 }};
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
                        timerDisplay.classList.add('text-danger', 'fw-bolder');
                    }
                } else if (e.data.action === 'finished') {
                    isSubmitting = true;
                    timerDisplay.textContent = "00:00";
                    alert('সময় শেষ! আপনার উত্তর স্বয়ংক্রিয়ভাবে জমা হচ্ছে।');
                    
                    // Prevent further changes
                    document.querySelectorAll('.btn-check:not(:checked)').forEach(opt => opt.disabled = true);
                    
                    form.submit();
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
                    timerDisplay.classList.add('text-danger', 'fw-bolder');
                }

                if (durationInSeconds <= 0) {
                    isSubmitting = true;
                    timerDisplay.textContent = "00:00";
                    alert('সময় শেষ! আপনার উত্তর স্বয়ংক্রিয়ভাবে জমা হচ্ছে।');
                    document.querySelectorAll('.btn-check:not(:checked)').forEach(opt => opt.disabled = true);
                    form.submit();
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
                return "আপনি কি নিশ্চিত যে পেজটি বন্ধ করতে চান? আপনার উত্তর সেভ হবে না।";
            }
        };

        // Custom Submit Function to avoid onbeforeunload firing on purpose
        window.confirmSubmit = function() {
            const containers = document.querySelectorAll('.question-container');
            const lastContainer = containers[containers.length - 1];
            const checkedOption = lastContainer.querySelector('input[type="radio"]:checked');
            
            if(!checkedOption) {
                alert('শেষ প্রশ্নের উত্তর দিন তারপর সাবমিট করুন।');
                return;
            }

            if(confirm('আপনি কি নিশ্চিত যে আপনি সাবমিট করতে চান? সাবমিট করার পর আর পরিবর্তন করা যাবে না।')) {
                isSubmitting = true;
                document.getElementById('liveExamForm').submit();
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
                    alert('সতর্কবার্তা: আপনি ৩ বার ট্যাব পরিবর্তন করেছেন। আপনার পরীক্ষাটি এখন সাবমিট হয়ে যাবে।');
                    form.submit();
                } else {
                    alert('সতর্কবার্তা: পরীক্ষা চলাকালীন অন্য ট্যাব বা উইন্ডোতে যাওয়া নিষেধ! আপনার ' + tabSwitchCount + ' টি সতর্কবার্তা জমা হয়েছে। ' + maxTabSwitches + ' বার এমন করলে পরীক্ষা অটো-সাবমিট হয়ে যাবে।');
                }
            }
        });
    });

</script>
@endsection
