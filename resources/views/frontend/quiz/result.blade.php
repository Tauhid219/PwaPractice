@extends('frontend.layouts.master')
@section('title', 'Quiz Result')

@section('content')
<div class="container-xxl py-5 page-header position-relative mb-5">
    <div class="container py-5">
        <h1 class="display-2 text-white animated slideInDown mb-4">ফলাফল</h1>
    </div>
</div>

<div class="container-xxl py-5">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-8 wow fadeInUp" data-wow-delay="0.1s">
                <div class="bg-light rounded p-5 text-center">
                    
                    @if($attempt->passed)
                        <i class="fa fa-trophy text-success display-1 mb-4 animate__animated animate__tada"></i>
                        <h2 class="text-success mb-3">অভিনন্দন! আপনি পাস করেছেন।</h2>
                    @else
                        <i class="fa fa-times-circle text-danger display-1 mb-4 animate__animated animate__shakeX"></i>
                        <h2 class="text-danger mb-3">দুঃখিত, আপনি পাস করতে পারেননি।</h2>
                    @endif

                    <h4 class="mb-4">আপনার স্কোর: <span class="{{ $attempt->passed ? 'text-success' : 'text-danger' }}">{{ $attempt->score }}</span> / {{ $totalQuestions }}</h4>

                    <div class="mt-5">
                        <a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $level->id]) }}" class="btn btn-primary px-4 py-2 me-2">
                            <i class="fa fa-refresh me-2"></i> আবার পড়ুন
                        </a>
                        
                        @if($attempt->passed)
                            @php
                                $nextLevel = \App\Models\Level::where('id', '>', $level->id)->orderBy('id', 'asc')->first();
                            @endphp

                            @if($nextLevel)
                                <a href="{{ route('level.questions', ['slug' => $category->slug, 'level' => $nextLevel->id]) }}" class="btn btn-success px-4 py-2">
                                    পরবর্তী লেভেল <i class="fa fa-arrow-right ms-2"></i>
                                </a>
                            @else
                                <a href="{{ route('category.levels', $category->slug) }}" class="btn btn-outline-success px-4 py-2">
                                    <i class="fa fa-list me-2"></i> ক্যাটাগরিতে ফিরে যান
                                </a>
                            @endif
                        @else
                           <a href="{{ route('quiz.start', ['slug' => $category->slug, 'level' => $level->id]) }}" class="btn btn-warning px-4 py-2 text-white">
                                <i class="fa fa-play-circle me-2"></i> আবার কুইজ দিন
                           </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        @if($attempt->passed)
            const winAudio = new Audio('{{ asset("frontend/sounds/ting.mp3") }}');
            winAudio.volume = 0.5;
            winAudio.play().catch(e => {});
            
            Swal.fire({
                title: 'অভিনন্দন!',
                text: 'আপনি লেভেলটি সফলভাবে সম্পন্ন করেছেন।',
                icon: 'success',
                timer: 3000,
                confirmButtonText: 'ঠিক আছে'
            });
        @else
            const loseAudio = new Audio('{{ asset("frontend/sounds/buzzer.mp3") }}');
            loseAudio.volume = 0.5;
            loseAudio.play().catch(e => {});

            Swal.fire({
                title: 'ইশ!',
                text: 'পাস করতে হলে অন্তত ৮০% নম্বর পেতে হবে। আবার চেষ্টা করুন!',
                icon: 'error',
                confirmButtonText: 'ঠিক আছে'
            });
        @endif
    });
</script>
@endsection
