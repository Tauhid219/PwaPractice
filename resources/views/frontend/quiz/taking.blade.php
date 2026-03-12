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
                                    @if($index > 0)
                                        <button type="button" class="btn btn-secondary px-4 py-2 me-2 prev-btn" data-index="{{ $index }}">আগের প্রশ্ন</button>
                                    @endif
                                    
                                    @if($index < count($questions) - 1)
                                        <button type="button" class="btn btn-primary px-4 py-2 next-btn" data-index="{{ $index }}">পরের প্রশ্ন</button>
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
        
        const nextBtns = document.querySelectorAll('.next-btn');
        const prevBtns = document.querySelectorAll('.prev-btn');
        const inputs = document.querySelectorAll('.answer-input');

        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                popAudio.volume = 0.5;
                popAudio.play().catch(e => {});
            });
        });

        nextBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIndex = parseInt(this.getAttribute('data-index'));
                const container = document.getElementById('q_' + currentIndex);
                const checkedOption = container.querySelector('input[type="radio"]:checked');
                
                if(!checkedOption) {
                    alert('দয়া করে একটি সঠিক উত্তর নির্বাচন করুন!');
                    return;
                }

                document.getElementById('q_' + currentIndex).style.display = 'none';
                document.getElementById('q_' + (currentIndex + 1)).style.display = 'block';
                
                popAudio.volume = 0.5;
                popAudio.play().catch(e => {});
            });
        });

        prevBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const currentIndex = parseInt(this.getAttribute('data-index'));
                document.getElementById('q_' + currentIndex).style.display = 'none';
                document.getElementById('q_' + (currentIndex - 1)).style.display = 'block';
                
                popAudio.volume = 0.5;
                popAudio.play().catch(e => {});
            });
        });
    });
</script>
@endsection
