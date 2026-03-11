@extends('frontend.layouts.master')
@section('title', $level->name . ' - প্রশ্ন ও উত্তর')

@section('content')
    <!-- Page Header Start -->
    <div class="container-xxl py-5 page-header position-relative mb-5">
        <div class="container py-5">
            <h1 class="display-2 text-white animated slideInDown mb-4">{{ $level->name }}</h1>
            <nav aria-label="breadcrumb animated slideInDown">
                <ol class="breadcrumb justify-content-center mb-0 text-white">
                    <li class="breadcrumb-item"><a href="{{ url('/') }}">হোম</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('category.levels', $category->slug) }}">{{ $category->name }}</a></li>
                    <li class="breadcrumb-item text-white-50 active" aria-current="page">{{ $level->name }}</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- Page Header End -->

    <!-- Questions Start -->
    <div class="container-xxl py-5">
        <div class="container">
            <div class="row g-4">
                @include('frontend.layouts.sidebar')
                <div class="col-lg-9">
                    <div class="text-center mx-auto mb-5 wow fadeInUp" data-wow-delay="0.1s" style="max-width: 600px;">
                        <h1 class="mb-3">{{ $level->name }} - প্রশ্ন ও উত্তর</h1>
                        <p>{{ $category->name }}</p>
                    </div>
            
            <div class="row g-4 justify-content-center">
                <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                    
                    <div class="text-center mb-4">
                        <div class="progress" style="height: 10px;">
                            <div class="progress-bar bg-success" id="progress-bar" role="progressbar" style="width: 0%;"></div>
                        </div>
                        <p class="mt-2 text-muted" id="progress-text">০ / {{ count($questions) }} পড়া হয়েছে</p>
                    </div>

                    <div class="accordion" id="questionsAccordion">
                        @forelse($questions as $index => $question)
                        <div class="accordion-item shadow-sm mb-3 border-0 rounded question-block" data-index="{{ $index }}">
                            <h2 class="accordion-header" id="heading-{{ $index }}">
                                <button class="accordion-button {{ $index == 0 ? '' : 'collapsed' }} rounded fw-bold fs-5 text-dark" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-{{ $index }}" aria-expanded="{{ $index == 0 ? 'true' : 'false' }}" aria-controls="collapse-{{ $index }}">
                                    <span class="text-primary me-2">প্রশ্ন {{ $index + 1 }}:</span> {{ $question->question_text }}
                                    <i class="fa fa-check-circle text-success ms-auto d-none read-check" id="check-{{ $index }}"></i>
                                </button>
                            </h2>
                            <div id="collapse-{{ $index }}" class="accordion-collapse collapse {{ $index == 0 ? 'show' : '' }}" aria-labelledby="heading-{{ $index }}" data-bs-parent="#questionsAccordion">
                                <div class="accordion-body bg-light rounded-bottom text-dark fs-5">
                                    <span class="fw-bold text-success me-2">উত্তর:</span> {{ $question->answer_text }}
                                    
                                    <div class="text-end mt-3">
                                        <button class="btn btn-sm btn-outline-success mark-read-btn" data-index="{{ $index }}">
                                            <i class="fa fa-check me-1"></i> পড়েছি
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-5">
                            <h3>দুঃখিত, এই লেভেলে এখনো কোনো প্রশ্ন যুক্ত করা হয়নি।</h3>
                        </div>
                        @endforelse
                    </div>

                    <div class="mt-5 text-center d-flex flex-column align-items-center">
                        <a href="{{ route('quiz.start', ['category' => $category->slug, 'level' => $level->id]) }}" class="btn btn-success px-5 py-3 fs-5 d-none mb-3 shadow" id="quiz-btn">কুইজ শুরু করুন <i class="fa fa-flag-checkered ms-2"></i></a>
                        <a href="{{ route('category.levels', $category->slug) }}" class="btn btn-outline-primary px-4 py-2"><i class="fa fa-arrow-left me-2"></i> লেভেল তালিকায় ফিরে যান</a>
                    </div>
                </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Questions End -->

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const totalQuestions = {{ count($questions) }};
            const levelId = {{ $level->id }};
            const categoryId = {{ $category->id }};
            const storageKey = `readProgress_cat${categoryId}_l${levelId}`;
            
            let readQuestions = JSON.parse(localStorage.getItem(storageKey)) || [];

            const progressBar = document.getElementById('progress-bar');
            const progressText = document.getElementById('progress-text');
            const quizBtn = document.getElementById('quiz-btn');
            const readButtons = document.querySelectorAll('.mark-read-btn');
            
            // Interaction Sounds
            const popAudio = new Audio('{{ asset("frontend/sounds/pop.mp3") }}');
            const buttons = document.querySelectorAll('.accordion-button');
            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    popAudio.volume = 0.5;
                    popAudio.play().catch(e => {});
                });
            });

            // Initialize UI
            function updateUI() {
                // Update progress bar
                const progressPercentage = (readQuestions.length / totalQuestions) * 100;
                progressBar.style.width = progressPercentage + '%';
                progressText.innerText = readQuestions.length + ' / ' + totalQuestions + ' পড়া হয়েছে';

                // Checkmarks
                readQuestions.forEach(index => {
                    const checkIcon = document.getElementById('check-' + index);
                    if (checkIcon) checkIcon.classList.remove('d-none');
                    
                    const btn = document.querySelector(`.mark-read-btn[data-index="${index}"]`);
                    if (btn) {
                        btn.classList.remove('btn-outline-success');
                        btn.classList.add('btn-success');
                        btn.innerHTML = '<i class="fa fa-check-double me-1"></i> পড়া শেষ';
                        btn.disabled = true;
                    }
                });

                // Show Quiz button if all read
                if (readQuestions.length >= totalQuestions && totalQuestions > 0) {
                    quizBtn.classList.remove('d-none');
                    quizBtn.classList.add('animate__animated', 'animate__pulse', 'animate__infinite');
                }
            }

            updateUI();

            // Mark as read clicks
            readButtons.forEach(btn => {
                btn.addEventListener('click', function() {
                    const index = parseInt(this.getAttribute('data-index'));
                    
                    if (!readQuestions.includes(index)) {
                        readQuestions.push(index);
                        localStorage.setItem(storageKey, JSON.stringify(readQuestions));
                        updateUI();
                        
                        // Collapse current, open next
                        const currentCollapse = document.getElementById('collapse-' + index);
                        const nextCollapse = document.getElementById('collapse-' + (index + 1));
                        
                        if (currentCollapse && window.bootstrap) {
                            new bootstrap.Collapse(currentCollapse, { toggle: true });
                        }
                        if (nextCollapse && window.bootstrap) {
                            new bootstrap.Collapse(nextCollapse, { toggle: true });
                        }
                    }
                });
            });
        });
    </script>
@endsection
