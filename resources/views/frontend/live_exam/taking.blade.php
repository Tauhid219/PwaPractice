@extends('frontend.layouts.master')
@section('title', 'Live Exam - ' . $exam->title)

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-3 text-white animated slideInDown mb-4">{{ $exam->title }}</h1>
        <p class="text-white-50 fs-5">সময়: {{ $exam->duration_minutes }} মিনিট</p>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8 wow border border-warning rounded rounded-3 p-4 shadow-sm fadeInUp" data-wow-delay="0.1s">
                <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-3">
                    <h3 class="mb-0 text-primary"><i class="fa fa-edit me-2"></i> পরীক্ষা চলছে...</h3>
                    <div class="fs-4 fw-bold text-danger">
                        <i class="fa fa-stopwatch me-1"></i> <span id="timer">--:--</span>
                    </div>
                </div>

                <form action="{{ route('live-exams.submit', $exam->id) }}" method="POST" id="liveExamForm">
                    @csrf
                    
                    @if($questions->isEmpty())
                        <div class="alert alert-warning">এই পরীক্ষায় কোন প্রশ্ন নেই।</div>
                    @else
                        @foreach($questions as $index => $question)
                            <div class="mb-5 question-block bg-light p-4 rounded" id="q_{{ $index }}">
                                <h4 class="mb-3"><span class="badge bg-primary me-2">{{ $index + 1 }}</span> {{ $question->question_text }}</h4>
                                
                                <div class="mt-3">
                                    <input type="text" name="answers[{{ $question->id }}]" class="form-control form-control-lg border-primary answer-input" placeholder="আপনার উত্তর এখানে লিখুন..." required>
                                </div>
                            </div>
                        @endforeach
                        
                        <div class="text-center mt-5">
                            <button type="button" class="btn btn-success btn-lg px-5 shadow pulse-animation" onclick="confirmSubmit()">
                                সাবমিট করুন <i class="fa fa-paper-plane ms-2"></i>
                            </button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popAudio = new Audio('{{ asset("frontend/sounds/pop.mp3") }}');
        const inputs = document.querySelectorAll('.answer-input');

        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                popAudio.volume = 0.5;
                popAudio.play().catch(e => {});
            });
        });

        // Timer Logic
        let durationInSeconds = {{ $exam->duration_minutes * 60 }};
        const timerDisplay = document.getElementById('timer');
        const form = document.getElementById('liveExamForm');

        function updateTimer() {
            let minutes = Math.floor(durationInSeconds / 60);
            let seconds = durationInSeconds % 60;

            minutes = minutes < 10 ? '0' + minutes : minutes;
            seconds = seconds < 10 ? '0' + seconds : seconds;

            timerDisplay.textContent = minutes + ':' + seconds;

            if (durationInSeconds <= 0) {
                clearInterval(timerInterval);
                alert('সময় শেষ! আপনার উত্তর স্বয়ংক্রিয়ভাবে জমা হচ্ছে।');
                form.submit();
            } else {
                durationInSeconds--;
            }
        }

        updateTimer();
        const timerInterval = setInterval(updateTimer, 1000);
    });

    function confirmSubmit() {
        if(confirm('আপনি কি নিশ্চিত যে আপনি সাবমিট করতে চান? সাবমিট করার পর আর পরিবর্তন করা যাবে না।')) {
            document.getElementById('liveExamForm').submit();
        }
    }
</script>
@endsection
