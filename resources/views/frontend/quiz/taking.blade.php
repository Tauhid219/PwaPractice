@extends('frontend.layouts.master')
@section('title', $level->name . ' - Quiz')

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">{{ $level->name }} - কুইজ</h1>
        <nav aria-label="breadcrumb animated slideInDown">
            <ol class="breadcrumb justify-content-center mb-0 text-white">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                <li class="breadcrumb-item"><a href="{{ route('category.levels', $category->slug) }}">{{ $category->name }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $level->id]) }}">{{ $level->name }}</a></li>
                <li class="breadcrumb-item text-white-50 active" aria-current="page">কুইজ</li>
            </ol>
        </nav>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5">
                    <h2 class="mb-4 text-center">কুইজ শুরু!</h2>
                    
                    <form action="{{ route('quiz.submit', ['slug' => $category->slug, 'level' => $level->id]) }}" method="POST" id="quizForm">
                        @csrf
                        
                        <!-- Progress Bar Start -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-primary fw-bold" id="progressText">প্রশ্ন ১ / {{ count($questions) }}</span>
                                <span class="text-muted" id="percentageText">০% সম্পন্ন</span>
                            </div>
                            <div class="progress" style="height: 10px; border-radius: 5px;">
                                <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated bg-primary" role="progressbar" style="width: 0%" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                        <!-- Progress Bar End -->

                        @foreach($questions as $index => $question)
                            <div class="mb-4 question-container" id="q_{{ $index }}" data-correct="{{ trim($question->answer_text) }}" style="{{ $index === 0 ? '' : 'display: none;' }}">
                                <h4><span class="text-primary">প্রশ্ন {{ $index + 1 }}:</span> {{ $question->question_text }}</h4>
                                
                                <div class="mt-4 quiz-options">
                                    @php
                                        $options = $question->shuffledOptions();
                                        $labels = ['ক)', 'খ)', 'গ)'];
                                    @endphp
                                    @foreach($options as $optIndex => $option)
                                        <div class="option-item mb-3">
                                            <input type="radio" name="answers[{{ $question->id }}]" id="opt{{ $optIndex }}_{{ $question->id }}" value="{{ $option }}" class="btn-check" required>
                                            <label class="btn btn-outline-primary w-100 text-start p-3 fs-5" for="opt{{ $optIndex }}_{{ $question->id }}">
                                                <span class="fw-bold me-2">{{ $labels[$optIndex] }}</span> {{ $option }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>

                                <div class="mt-4 text-end">
                                    @if($index < count($questions) - 1)
                                        <button type="button" class="btn btn-primary px-4 py-2 next-btn" data-index="{{ $index }}">পরের প্রশ্ন <i class="fa fa-arrow-right ms-2"></i></button>
                                    @else
                                        <button type="submit" class="btn btn-success px-5 py-2">সাবমিট কুইজ <i class="fa fa-paper-plane ms-2"></i></button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Sounds
        const popAudio = new Audio('{{ asset("frontend/sounds/pop.mp3") }}');
        const tingAudio = new Audio('{{ asset("frontend/sounds/ting.mp3") }}');
        const buzzerAudio = new Audio('{{ asset("frontend/sounds/buzzer.mp3") }}');
        
        const nextBtns = document.querySelectorAll('.next-btn');
        const prevBtns = document.querySelectorAll('.prev-btn');
        const quizForm = document.getElementById('quizForm');

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIndex = parseInt(this.getAttribute('data-index'));
                const container = document.getElementById('q_' + currentIndex);
                const checkedOption = container.querySelector('input[type="radio"]:checked');
                
                if(!checkedOption) {
                    alert('দয়া করে একটি সঠিক উত্তর নির্বাচন করুন!');
                    return;
                }

                // Check answer
                const correctAnswer = container.getAttribute('data-correct').toLowerCase().trim();
                const selectedAnswer = checkedOption.value.toLowerCase().trim();

                if (selectedAnswer === correctAnswer) {
                    tingAudio.currentTime = 0;
                    tingAudio.play().catch(e => {});
                } else {
                    buzzerAudio.currentTime = 0;
                    buzzerAudio.play().catch(e => {});
                }

                // Small delay to let the sound start before switching question
                setTimeout(() => {
                    const nextIndex = currentIndex + 1;
                    document.getElementById('q_' + currentIndex).style.display = 'none';
                    document.getElementById('q_' + nextIndex).style.display = 'block';
                    
                    // Update Progress Bar
                    updateProgressBar(nextIndex);
                }, 300);
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
        updateProgressBar(0);

        // Handle Submit sound for the last question
        quizForm.addEventListener('submit', function(e) {
            const containers = document.querySelectorAll('.question-container');
            const lastContainer = containers[containers.length - 1];
            const checkedOption = lastContainer.querySelector('input[type="radio"]:checked');

            if (checkedOption) {
                const correctAnswer = lastContainer.getAttribute('data-correct').toLowerCase().trim();
                const selectedAnswer = checkedOption.value.toLowerCase().trim();

                if (selectedAnswer === correctAnswer) {
                    tingAudio.currentTime = 0;
                    tingAudio.play().catch(e => {});
                } else {
                    buzzerAudio.currentTime = 0;
                    buzzerAudio.play().catch(e => {});
                }
                
                // We don't preventDefault because we want it to submit, 
                // but if we want the sound to be heard, we might need a small delay.
                // However, HTML5 Audio play() starts immediately. 
                // A better UX might be to preventDefault, play sound, then submit.
                e.preventDefault();
                setTimeout(() => {
                    quizForm.submit();
                }, 500);
            }
        });
    });
</script>
@endsection
